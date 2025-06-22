<?php 

namespace nextdev\nextdashboard\Observers;

use Illuminate\Support\Facades\Auth;
use nextdev\nextdashboard\Models\Admin;

class AdminObserver
{
    public function created(Admin $admin)
    {
        activity('admin')
            ->causedBy(Auth::guard('admin')->user())
            ->performedOn($admin)
            ->log("Admin created: {$admin->email}");
    }

    public function updated(Admin $admin)
    {
        activity('admin')
            ->causedBy(Auth::guard('admin')->user())
            ->performedOn($admin)
            ->withProperties([
                'old' => $admin->getOriginal(),
                'new' => $admin->getChanges(),
            ])
            ->log("Admin updated: {$admin->email}");
    }

    public function deleted(Admin $admin)
    {
        activity('admin')
            ->causedBy(Auth::guard('admin')->user())
            ->performedOn($admin)
            ->log("Admin deleted: {$admin->email}");
    }
}
