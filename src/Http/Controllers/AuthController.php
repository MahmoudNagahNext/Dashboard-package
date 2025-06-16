<?php

namespace nextdev\nextdashboard\Http\Controllers;

use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use nextdev\nextdashboard\DTOs\AdminDTO;
use nextdev\nextdashboard\Traits\ApiResponseTrait;
use nextdev\nextdashboard\Http\Requests\Auth\LoginRequest;
use nextdev\nextdashboard\Http\Requests\Auth\RegisterRequest;
use nextdev\nextdashboard\Http\Requests\Auth\ResetPasswordRequest;
use nextdev\nextdashboard\Http\Requests\Auth\SendResetLinkEmailRequest;
use nextdev\nextdashboard\Http\Resources\AdminResource;
use nextdev\nextdashboard\Models\Admin;
use nextdev\nextdashboard\Services\AuthService;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected AuthService $authService
    ){}

    public function login(LoginRequest $request)
    {
        try {
            $admin = $this->authService->login($request->validated());

            return $this->successResponse([
                'admin' => AdminResource::make($admin),
                'token' => $admin['api_token'],
                'token_type' => 'Bearer'
            ], 'Login successful');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $adminDTO = AdminDTO::fromRequest($request->validated());
            $admin = $this->authService->register($adminDTO);

            return $this->createdResponse([
                'user' => AdminResource::make($admin),
                'token' => $admin['api_token'],
                'token_type' => 'Bearer'
            ], 'User Created Successfully');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function sendResetLinkEmail(SendResetLinkEmailRequest $request)
    {
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return response()->json(['error' => 'Email not found'], 404);
        }

        $token = Password::broker('admins')->createToken($admin);
        
        $admin->notify(new AdminResetPasswordNotification($token));

        return response()->json([
            'message' => 'Reset token sent to your email address.'
        ]);
    }

    //  public function sendResetLinkEmail(SendResetLinkEmailRequest $request)
    // {
    //     $status = Password::broker('admins')->sendResetLink($request->validated());

    //     return $status === Password::RESET_LINK_SENT
    //         ? response()->json(['message' => __($status)])
    //         : response()->json(['error' => __($status)], 400);
    // }


    public function reset(ResetPasswordRequest $request)
    {
        $status = Password::broker('admins')->reset(
            $request->validated(),
            function ($admin, $password) {
                $admin->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $admin->save();
                event(new PasswordReset($admin));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)])
            : response()->json(['error' => __($status)], 400);
    }
}