<?php 
namespace nextdev\nextdashboard\Events;

use Illuminate\Queue\SerializesModels;
use nextdev\nextdashboard\Models\Admin;
use Spatie\Permission\Models\Role;

class RoleAssignedToAdmin
{
    use SerializesModels;

    public Admin $admin;
    public Role $role;

    public function __construct(Admin $admin, Role $role)
    {
        $this->admin = $admin;
        $this->role = $role;
    }
}
