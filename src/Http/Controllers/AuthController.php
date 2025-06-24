<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use nextdev\nextdashboard\Traits\ApiResponseTrait;
use nextdev\nextdashboard\Http\Requests\Auth\LoginRequest;
use nextdev\nextdashboard\Http\Resources\AdminResource;
use nextdev\nextdashboard\Services\AuthService;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected AuthService $authService
    ){}

    public function login(LoginRequest $request)
    {
        $admin = $this->authService->login($request->validated());

        return $this->successResponse([
            'admin' => AdminResource::make($admin),
            'token' => $admin['api_token'],
            'token_type' => 'Bearer'
        ], 'Login successful');
    }
}