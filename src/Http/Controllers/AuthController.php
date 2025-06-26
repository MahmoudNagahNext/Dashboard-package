<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use nextdev\nextdashboard\Http\Requests\Auth\LoginRequest;
use nextdev\nextdashboard\Http\Resources\AdminResource;
use nextdev\nextdashboard\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ){}

    public function login(LoginRequest $request):JsonResponse
    {
        $admin = $this->authService->login($request->validated());

        return Response::json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'admin' => AdminResource::make($admin),
                'token' => $admin['api_token'],
                'token_type' => 'Bearer'
            ],
        ], 200);
    }
}