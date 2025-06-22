<?php

namespace Database\seeders;

use Illuminate\Database\Seeder;
use nextdev\nextdashboard\Models\TicketPriority;

class TicketPrioritySeeder extends Seeder
{
   /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = [
            ['en' => 'Low',     'ar' => 'منخفض'],
            ['en' => 'Medium',  'ar' => 'متوسط'],
            ['en' => 'High',    'ar' => 'عالٍ'],
            ['en' => 'Urgency', 'ar' => 'عاجل'], // أصل الكلمة الصحيحة "Urgency" مش "Ergency"
        ];

        foreach ($priorities as $name) {
            TicketPriority::query()->updateOrCreate([
                'name->en' => $name['en'], // شرط البحث بالترجمة الإنجليزية
            ], [
                'name' => $name, // الحفظ بكل الترجمات
            ]);
        }
    }
}
