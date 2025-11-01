<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = [
            ['name' => 'Science', 'code' => 'SCI', 'description' => 'Faculty of Science'],
            ['name' => 'Arts', 'code' => 'ART', 'description' => 'Faculty of Arts'],
            ['name' => 'Commerce', 'code' => 'COM', 'description' => 'Faculty of Commerce'],
            ['name' => 'Engineering', 'code' => 'ENG', 'description' => 'Faculty of Engineering'],
            ['name' => 'Medicine', 'code' => 'MED', 'description' => 'Faculty of Medicine'],
            ['name' => 'Law', 'code' => 'LAW', 'description' => 'Faculty of Law'],
            ['name' => 'Management', 'code' => 'MGT', 'description' => 'Faculty of Management'],
        ];

        foreach ($faculties as $faculty) {
            \App\Models\Faculty::updateOrCreate(
                ['name' => $faculty['name']],
                $faculty
            );
        }
    }
}
