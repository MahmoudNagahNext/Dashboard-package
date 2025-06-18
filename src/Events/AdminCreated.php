<?php 
namespace nextdev\nextdashboard\Events;

use Illuminate\Queue\SerializesModels;
use nextdev\nextdashboard\Models\Admin;

class AdminCreated
{
    use SerializesModels;

    public Admin $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }
}
