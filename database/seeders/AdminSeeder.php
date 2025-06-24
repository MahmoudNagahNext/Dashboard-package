<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use nextdev\nextdashboard\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            "name"=> "Super admin",
            "email" => "admin@admin.com",
            "password" => Hash::make("admin@123"),
        ];

        $admin = Admin::query()->create($data);
        
        $admin->assignRole("super_admin");

    }
}
