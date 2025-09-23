<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'Scholar', 'description' => 'Postgraduate research student'],
            ['name' => 'Supervisor', 'description' => 'Guides Scholars and verifies data'],
            ['name' => 'HOD', 'description' => 'Approves submissions and manages DRC minutes'],
            ['name' => 'Dean', 'description' => 'Approves academic decisions'],
            ['name' => 'DR', 'description' => 'Ensures compliance with research standards'],
            ['name' => 'HVC', 'description' => 'Gives final approvals'],
            ['name' => 'AR', 'description' => 'Supports research office operations'],
            ['name' => 'DA', 'description' => 'Dean\'s Assistant - First level approval for capacity increases'],
            ['name' => 'SO', 'description' => 'Section Officer - Second level approval for capacity increases'],
            ['name' => 'Research Section', 'description' => 'Coordinates thesis workflows'],
        ]);
    }
}
