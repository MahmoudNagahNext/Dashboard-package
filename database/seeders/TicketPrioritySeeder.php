<?php

namespace Database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use nextdev\nextdashboard\Models\TicketPriority;

class TicketPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proirities = ['Low', 'Medium', 'High', 'Ergency'];

        foreach ($proirities as $proirity) {
            TicketPriority::query()->updateOrCreate([
                'name' => $proirity
            ]);
        }
    }
}
