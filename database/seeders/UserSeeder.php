<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Scholar;
use App\Models\Supervisor;
use App\Models\Department;
use App\Models\DRC;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default department
        $department = Department::firstOrCreate(
            ['name' => 'Computer Science'],
            ['hod_id' => null, 'dean_id' => null] // HOD and Dean will be assigned after users are created
        );

        $roles = Role::all()->keyBy('name');

        // Scholar User
        $scholarUser = User::create([
            'name' => 'Scholar User',
            'email' => 'scholar@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['Scholar']->id,
            'user_type' => 'scholar',
        ]);

        // Create a dummy admission record for the initial scholar
        $scholarAdmission = \App\Models\Admission::create([
            'department_id' => $department->id, // Assign to the default department
            'merit_list_file' => null,
            'admission_date' => now()->subMonths(6),
            'status' => 'admitted',
        ]);

        Scholar::create([
            'user_id' => $scholarUser->id,
            'admission_id' => $scholarAdmission->id,
            'first_name' => 'Scholar',
            'last_name' => 'User',
            'status' => 'pending_profile_completion',
        ]);

        // Supervisor Users - Create multiple supervisors with different types
        $supervisorUser1 = User::create([
            'name' => 'Professor Supervisor',
            'email' => 'professor@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['Supervisor']->id,
            'user_type' => 'supervisor',
        ]);
        Supervisor::create([
            'user_id' => $supervisorUser1->id,
            'employee_id' => 'EMP001',
            'department_id' => $department->id,
            'designation' => 'Professor',
            'research_specialization' => 'Artificial Intelligence',
            'supervisor_type' => 'professor',
        ]);

        $supervisorUser2 = User::create([
            'name' => 'Associate Supervisor',
            'email' => 'associate@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['Supervisor']->id,
            'user_type' => 'supervisor',
        ]);
        Supervisor::create([
            'user_id' => $supervisorUser2->id,
            'employee_id' => 'EMP002',
            'department_id' => $department->id,
            'designation' => 'Associate Professor',
            'research_specialization' => 'Machine Learning',
            'supervisor_type' => 'associate',
        ]);

        $supervisorUser3 = User::create([
            'name' => 'Assistant Supervisor',
            'email' => 'assistant@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['Supervisor']->id,
            'user_type' => 'supervisor',
        ]);
        Supervisor::create([
            'user_id' => $supervisorUser3->id,
            'employee_id' => 'EMP003',
            'department_id' => $department->id,
            'designation' => 'Assistant Professor',
            'research_specialization' => 'Data Science',
            'supervisor_type' => 'assistant',
        ]);

        // HOD User
        $hodUser = User::create([
            'name' => 'HOD User',
            'email' => 'hod@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['HOD']->id,
            'user_type' => 'hod',
        ]);
        // Update the department with HOD ID
        $department->update(['hod_id' => $hodUser->id]);

        // Create a default DRC after HOD is created
        DRC::firstOrCreate(
            ['department_id' => $department->id],
            ['hod_id' => $hodUser->id, 'minutes_file' => 'initial_drc_minutes.pdf', 'meeting_date' => now(), 'status' => 'active']
        );

        // Dean User
        $deanUser = User::create([
            'name' => 'Dean User',
            'email' => 'dean@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['Dean']->id,
            'user_type' => 'dean',
        ]);
        // Update the department with Dean ID
        $department->update(['dean_id' => $deanUser->id]);

        // DR User
        User::create([
            'name' => 'DR User',
            'email' => 'dr@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['DR']->id,
            'user_type' => 'dr',
        ]);

        // HVC User
        User::create([
            'name' => 'HVC User',
            'email' => 'hvc@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['HVC']->id,
            'user_type' => 'hvc',
        ]);

        // DA (Dean's Assistant) User
        User::create([
            'name' => 'DA User',
            'email' => 'da@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['DA']->id,
            'user_type' => 'da',
        ]);

        // SO (Section Officer) User
        User::create([
            'name' => 'SO User',
            'email' => 'so@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['SO']->id,
            'user_type' => 'so',
        ]);

        // AR (Assistant Registrar) User
        User::create([
            'name' => 'AR User',
            'email' => 'ar@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['AR']->id,
            'user_type' => 'ar',
        ]);


        // Research Section User (assuming this is a general staff type for now)
        User::create([
            'name' => 'Research Section User',
            'email' => 'researchsection@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['Research Section']->id,
            'user_type' => 'staff',
        ]);

        // Expert User (for thesis evaluation)
        User::create([
            'name' => 'Expert User',
            'email' => 'expert@example.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['Supervisor']->id, // Experts might share supervisor role or have a dedicated one
            'user_type' => 'staff',
        ]);
    }
}
