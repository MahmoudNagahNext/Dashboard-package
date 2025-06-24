{{-- resources/views/emails/otp.blade.php --}}

@component('mail::message')
# Hello {{ $user->name ?? 'User' }},

You have requested to reset your password.

Your **One-Time Password (OTP)** is:

@component('mail::panel')
**{{ $otp }}**
@endcomponent

> This code will expire in 10 minutes.

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
