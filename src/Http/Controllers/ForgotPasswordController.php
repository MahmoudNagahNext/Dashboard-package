<?php

namespace nextdev\nextdashboard\Http\Controllers;

use App\Models\PasswordOtp;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use nextdev\nextdashboard\Http\Requests\Auth\ResetPasswordRequest;
use nextdev\nextdashboard\Http\Requests\Auth\sendOtpRequest;
use nextdev\nextdashboard\Models\Admin;

class ForgotPasswordController extends Controller
{
    public function sendOtp(sendOtpRequest $request)
    {
        $data = $request->validated();

        $otp = rand(100000, 999999);
        
        PasswordOtp::updateOrCreate(
            ['email' => $data['email']],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        $user = Admin::where('email', $data['email'])->first();
        // Mail::to($user->email)->send(new SendOtpMail($otp, $user));
        Mail::raw("You OTP is: $otp", function ($message) use ($data) {
            $message->to($data['email'])
                    ->subject('OTP to recover password');
        });

        return $this->showMessage(['message'=> "OTP Send successflly", "otp" => $otp]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
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
