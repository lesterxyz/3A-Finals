<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Subject;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            // Place any interactive commands here only if not running tests
            $this->command->info('Seeding data...');
        }

        // Seed students and subjects without interaction
        Student::factory()
            ->count(10)
            ->has(Subject::factory()->count(5), 'subjects')
            ->create();
    }
}
