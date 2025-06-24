<?php 

namespace nextdev\nextdashboard\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use nextdev\nextdashboard\Models\PasswordOtp;

class DeleteExpiredOtps extends Command
{
    protected $signature = 'otps:delete-expired';
    protected $description = 'Delete expired OTP records';

    public function handle()
    {
        PasswordOtp::where('expires_at', '<', now())->delete();

        $this->info("Expired OTPs Deleted Successfully.");

    }
} 
