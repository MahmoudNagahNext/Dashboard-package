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
            "email" => "admin@admin.com",
            "password" => Hash::make("admin@123"),
        ];

       Admin::query()->create($data);
    }
}
