<?php

namespace nextdev\nextdashboard\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordOtp extends Model
{
    protected $fillable = [
        "admin_id",
        "otp",
        "expires_at"
    ];
}
