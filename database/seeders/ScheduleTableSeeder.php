<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schedule::create([
            'day_name' => 'Saturday'
        ]);

        Schedule::create([
            'day_name' => 'Sunday'
        ]);

        Schedule::create([
            'day_name' => 'Monday'
        ]);

        Schedule::create([
            'day_name' => 'Tuesday'
        ]);

        Schedule::create([
            'day_name' => 'Wednesday'
        ]);

        Schedule::create([
            'day_name' => 'Thursday'
        ]);

        Schedule::create([
            'day_name' => 'Friday'
        ]);

    }
}
