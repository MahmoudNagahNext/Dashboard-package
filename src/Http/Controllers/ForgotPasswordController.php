<?php

namespace nextdev\nextdashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use nextdev\nextdashboard\Http\Requests\Auth\ResetPasswordRequest;
use nextdev\nextdashboard\Http\Requests\Auth\sendOtpRequest;
use nextdev\nextdashboard\Mail\SendOtpMail;
use nextdev\nextdashboard\Models\Admin;
use nextdev\nextdashboard\Models\PasswordOtp;

class ForgotPasswordController extends Controller
{
    public function sendOtp(sendOtpRequest $request)
    {
        $data = $request->validated();

        $otp = rand(100000, 999999);
        $admin = Admin::where('email', $data['email'])->first();
        if (! $admin) {
            return response()->json(['message' => 'Email not found.'], 422);
        }

        PasswordOtp::updateOrCreate(
            ['admin_id' => $admin->id],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        // TODO:: create mail class to send otp
        Mail::to($admin->email)->send(new SendOtpMail($otp, $admin));

        return response()->json(['message'=> "OTP Send successflly", "otp" => $otp]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        // TODO :: last otp for admin , expair for exist otp for same admin
        // TODO :: add cron job to delete expair otps
        $data = $request->validated();

        $record = PasswordOtp::where('email', $data['email'])
            ->where('otp', $data['otp'])
            ->where('expires_at', '>', now())
            ->first();

        if (! $record) {
            return response()->json(['message' => 'OTP is invalid or expired.'], 422);
        }

        // تحديث الباسورد
        Admin::where('email', $data['email'])->update([
            'password' => Hash::make($data['password']),
        ]);

        // حذف الـ OTP
        $record->delete();

        return response()->json(['message' => "Password Updated Successflly"]);
    }
}
