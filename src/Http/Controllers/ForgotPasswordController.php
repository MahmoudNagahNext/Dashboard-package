<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use nextdev\nextdashboard\Http\Requests\Auth\ResetPasswordRequest;
use nextdev\nextdashboard\Http\Requests\Auth\sendOtpRequest;
use nextdev\nextdashboard\Mail\SendOtpMail;
use nextdev\nextdashboard\Models\Admin;
use nextdev\nextdashboard\Models\PasswordOtp;

class ForgotPasswordController extends Controller
{
    public function sendOtp(sendOtpRequest $request): JsonResponse
    {
        $data = $request->validated();

        $admin = Admin::where('email', $data['email'])->first();
        if (! $admin) {
            return Response::json([
                'success' => false,
                'message' => 'Email not found.',
                'data' => []
            ], 422);
        }
        $otp = rand(100000, 999999);

        PasswordOtp::updateOrCreate(
            ['admin_id' => $admin->id],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        Mail::to($admin->email)->send(new SendOtpMail($otp, $admin));

        return Response::json([
            'success' => true,
            'message'=> "OTP Send successflly",
            'data' => []
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $admin = Admin::where('email', $data['email'])->first();
        if (! $admin) {
            return Response::json([
                'success' => false,
                'message' => 'Email not found.',
                'data' => []
            ], 422);
        }

        $record = PasswordOtp::where('admin_id', $admin->id)
            ->where('otp', $data['otp'])
            ->where('expires_at', '>', now())
            ->first();

        if (! $record) {
            return Response::json([
                'success' => false,
                'message' => 'OTP is invalid or expired.',
                'data' => []
            ], 422);
        }

        $admin->update([
            'password' => Hash::make($data['password']),
        ]);

        $record->delete();

        return Response::json([
            'success' => true,
            'message' => "Password Updated Successflly",
            'data' => []
        ]);
    }
}
