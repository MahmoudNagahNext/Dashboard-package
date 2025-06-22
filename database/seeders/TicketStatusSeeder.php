<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use nextdev\nextdashboard\Models\TicketStatus;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['en' => 'Open', 'ar' => 'مفتوح'],
            ['en' => 'In Progress', 'ar' => 'قيد المعالجة'],
            ['en' => 'Resolved', 'ar' => 'تم الحل'],
            ['en' => 'Closed', 'ar' => 'مغلق'],
        ];

        foreach ($statuses as $name) {
            TicketStatus::query()->updateOrCreate([
                'name->en' => $name['en'],
            ], [
                'name' => $name,
            ]);
        }
    }
}
