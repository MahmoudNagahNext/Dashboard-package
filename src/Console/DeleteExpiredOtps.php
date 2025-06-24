<?php 

namespace nextdev\nextdashboard\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeleteExpiredOtps extends Command
{
    protected $signature = 'otps:delete-expired';
    protected $description = 'Delete expired OTP records';

    public function handle()
    {
        $deleted = DB::table('otps')
            ->where('expires_at', '<', Carbon::now())
            ->delete();

        $this->info("Deleted $deleted expired OTPs.");
    }
} 
