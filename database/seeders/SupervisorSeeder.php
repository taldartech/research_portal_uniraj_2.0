<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all()->keyBy('name');
        $department = Department::first();
        $supervisorUser1 = User::create([
            'name' => 'Supervisor 4',
            'email' => 'supervisor4@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['Supervisor']->id,
            'user_type' => 'supervisor',
        ]);
        Supervisor::create([
            'user_id' => $supervisorUser1->id,
            'employee_id' => 'EMP0004',
            'department_id' => $department->id,
            'designation' => 'Associate Professor',
            'research_specialization' => 'Artificial Intelligence',
            'supervisor_type' => 'associate',
        ]);

        $supervisorUser2 = User::create([
            'name' => 'Supervisor 5',
            'email' => 'supervisor5@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['Supervisor']->id,
            'user_type' => 'supervisor',
        ]);
        Supervisor::create([
            'user_id' => $supervisorUser2->id,
            'employee_id' => 'EMP0005',
            'department_id' => $department->id,
            'designation' => 'Assistant Professor',
            'research_specialization' => 'Machine Learning',
            'supervisor_type' => 'assistant',
        ]);

        $supervisorUser3 = User::create([
            'name' => 'Supervisor 6',
            'email' => 'supervisor6@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['Supervisor']->id,
            'user_type' => 'supervisor',
        ]);
        Supervisor::create([
            'user_id' => $supervisorUser3->id,
            'employee_id' => 'EMP0006',
            'department_id' => $department->id,
            'designation' => 'Professor',
            'research_specialization' => 'Artificial Intelligence',
            'supervisor_type' => 'professor',
        ]);

        $supervisorUser4 = User::create([
            'name' => 'Supervisor 7',
            'email' => 'supervisor7@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['Supervisor']->id,
            'user_type' => 'supervisor',
        ]);
        Supervisor::create([
            'user_id' => $supervisorUser4->id,
            'employee_id' => 'EMP0007',
            'department_id' => $department->id,
            'designation' => 'Professor',
            'research_specialization' => 'Artificial Intelligence',
            'supervisor_type' => 'professor',
        ]);

        $supervisorUser5 = User::create([
            'name' => 'Supervisor 8',
            'email' => 'supervisor8@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['Supervisor']->id,
            'user_type' => 'supervisor',
        ]);
        Supervisor::create([
            'user_id' => $supervisorUser5->id,
            'employee_id' => 'EMP0008',
            'department_id' => $department->id,
            'designation' => 'Professor',
            'research_specialization' => 'Artificial Intelligence',
            'supervisor_type' => 'professor',
        ]);
    }
}
