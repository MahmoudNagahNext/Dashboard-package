<?php 

namespace nextdev\nextdashboard\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Response;

class HandleAuthorizationException
{
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (AuthorizationException|AccessDeniedHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: You do not have permission to perform this action.',
            ], Response::HTTP_FORBIDDEN);
        }
    }
}
