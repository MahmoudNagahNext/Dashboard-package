<?php

namespace nextdev\nextdashboard\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Log;

trait ApiResponseTrait
{
    protected function getResponseStructure(): array
    {
        return [
            'success' => true,
            'message' => '',
            'data' => null,
            'errors' => [],
            'meta' => [],
        ];
    }

    protected function getDefaultMessages(): array
    {
        return [
            'success' => 'Operation completed successfully',
            'created' => 'Resource created successfully',
            'updated' => 'Resource updated successfully',
            'deleted' => 'Resource deleted successfully',
            'not_found' => 'Resource not found',
            'validation_error' => 'Validation failed',
            'unauthorized' => 'Unauthorized access',
            'forbidden' => 'Forbidden access',
            'server_error' => 'Internal server error',
            'no_content' => 'No content available',
        ];
    }

    protected function buildResponse(bool $success, string $message, mixed $data = null, array $errors = [], int $status = 200, array $meta = []): JsonResponse {
        $structure = $this->getResponseStructure();
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
        ];

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $status);
    }

    /**
     * ===================================================
     * success Responses
     * ===================================================
     */

    public function successResponse( mixed $data = null, string $message = '', int $status = 200, array $meta = []): JsonResponse 
    {
        $defaultMessages = $this->getDefaultMessages();
        $message = $message ?: $defaultMessages['success'];
        return $this->buildResponse(true, $message, $data, [], $status, $meta);
    }

    public function createdResponse( mixed $data = null, string $message = '' ): JsonResponse 
    {
        $defaultMessages = $this->getDefaultMessages();
        $message = $message ?: $defaultMessages['created'];
        return $this->buildResponse(true, $message, $data, [], Response::HTTP_CREATED);
    }

    public function updatedResponse( mixed $data = null, string $message = '' ): JsonResponse 
    {
        $defaultMessages = $this->getDefaultMessages();
        $message = $message ?: $defaultMessages['updated'];
        return $this->buildResponse(true, $message, $data, [], Response::HTTP_OK);
    }

    public function deletedResponse(string $message = ''): JsonResponse
    {
        $defaultMessages = $this->getDefaultMessages();
        $message = $message ?: $defaultMessages['deleted'];
        return $this->buildResponse(true, $message, null, [], Response::HTTP_OK);
    }

    public function noContentResponse(): JsonResponse
    {
        $defaultMessages = $this->getDefaultMessages();
        return response()->json([
            'success' => true,
            'message' => $defaultMessages['no_content']
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * ===================================================
     * Pagination Responses
     * ===================================================
     */

    public function paginatedResponse( AbstractPaginator $paginator, string $message = '', array $additionalMeta = [] ): JsonResponse {
        $data = $paginator->items();
        $meta = [
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ]
        ];

        if (!empty($additionalMeta)) {
            $meta = array_merge($meta, $additionalMeta);
        }

        return $this->successResponse($data, $message, Response::HTTP_OK, $meta);
    }

    public function paginatedCollectionResponse(AbstractPaginator $paginator, string $message = '', array $additionalMeta = [],?string $resourceClass = null): JsonResponse {
        // Apply resource transformation if a resource class is provided
        $data = $resourceClass
            ? $resourceClass::collection($paginator->items())
            : $paginator->items();
    
        // Build meta
        $meta = [
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ]
        ];
    
        // Merge additional meta if provided
        if (!empty($additionalMeta)) {
            $meta = array_merge($meta, $additionalMeta);
        }
    
        return $this->successResponse($data, $message, Response::HTTP_OK, $meta);
    }

    /**
     * ===================================================
     * Error Responses
     * ===================================================
     */

    public function notFoundResponse( string $message = '', array $errors = [] ): JsonResponse 
    {
        $defaultMessages = $this->getDefaultMessages();
        $message = $message ?: $defaultMessages['not_found'];
        return $this->buildResponse(false, $message, null, $errors, Response::HTTP_NOT_FOUND);
    }

    public function validationErrorResponse( array $errors, string $message = ''): JsonResponse 
    {
        $defaultMessages = $this->getDefaultMessages();
        $message = $message ?: $defaultMessages['validation_error'];
        return $this->buildResponse(false, $message, null, $errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function unauthorizedResponse(string $message = '', array $errors = []): JsonResponse 
    {
        $defaultMessages = $this->getDefaultMessages();
        $message = $message ?: $defaultMessages['unauthorized'];
        return $this->buildResponse(false, $message, null, $errors, Response::HTTP_UNAUTHORIZED);
    }

    public function forbiddenResponse(string $message = '',array $errors = []): JsonResponse 
    {
        $defaultMessages = $this->getDefaultMessages();
        $message = $message ?: $defaultMessages['forbidden'];
        return $this->buildResponse(false, $message, null, $errors, Response::HTTP_FORBIDDEN);
    }

    public function errorResponse(string $message, int $status = 500, array $errors = [], bool $logError = true): JsonResponse 
    {
        if ($logError) {
            Log::error("API Error: $message", [
                'status' => $status,
                'errors' => $errors,
                'request' => request()->all(),
                'url' => request()->fullUrl(),
            ]);
        }

        return $this->buildResponse(false, $message, null, $errors, $status);
    }

    /**
     * ===================================================
     * Exception Responses
     * ===================================================
     */

    public function handleException(\Throwable $e): JsonResponse
    {
        $status = method_exists($e, 'getStatusCode') 
            ? $e->getStatusCode() 
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        $message = $e->getMessage();
        $errors = [];

        $defaultMessages = $this->getDefaultMessages();

        if ($e instanceof \Illuminate\Validation\ValidationException) {
            $status = Response::HTTP_UNPROCESSABLE_ENTITY;
            $errors = $e->errors();
            $message = $defaultMessages['validation_error'];
        } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $status = Response::HTTP_NOT_FOUND;
            $message = $defaultMessages['not_found'];
        } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
            $status = Response::HTTP_UNAUTHORIZED;
            $message = $defaultMessages['unauthorized'];
        } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
            $status = Response::HTTP_FORBIDDEN;
            $message = $defaultMessages['forbidden'];
        }

        return $this->errorResponse($message, $status, $errors, false);
    }
}