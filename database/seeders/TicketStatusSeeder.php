<?php

namespace Database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use nextdev\nextdashboard\Models\TicketStatus;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Open', 'In Progress', 'Resolved', 'Closed'];

        foreach ($statuses as $status) {
            TicketStatus::query()->updateOrCreate([
                'name' => $status
            ]);
        }
    }
}
