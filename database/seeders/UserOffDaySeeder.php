<?php

namespace Database\Seeders;

use App\Models\UserOffDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserOffDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserOffDay::factory()
            ->count(10)
            ->create();
    }
}
