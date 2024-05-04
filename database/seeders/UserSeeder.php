<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supervisor;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use App\Models\DepartmentSupervisor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     //for user table
    //     User::create([
    //         'name' => 'Admin',
    //         'email' => 'admin@example.com',
    //         'password' => Hash::make('admin123'), // Hash the password
    //         'role' => 'admin',
    //     ]);


        
    // }

    //for organization table
    // public function run()
    // {
    //     $organizations = [
    //         [
    //             'name' => 'IITA',
    //             'email' => 'iita@example.com',
    //             'phone' => '123456789',
    //             'address' => '123 Main St, City',
    //             'password' => Hash::make('password'),
    //             'role' => 'organization',
    //         ],
    //         [
    //             'name' => 'Inzideout',
    //             'email' => 'inzideout@example.com',
    //             'phone' => '987654321',
    //             'address' => '456 Park Ave, Town',
    //             'password' => Hash::make('password'),
    //             'role' => 'organization',
    //         ],
    //         [
    //             'name' => 'Skillsforge',
    //             'email' => 'skillsforge@example.com',
    //             'phone' => '555555555',
    //             'address' => '789 Oak St, Village',
    //             'password' => Hash::make('password'),
    //             'role' => 'organization',
    //         ],
    //         [
    //             'name' => 'HIIT',
    //             'email' => 'hiit@example.com',
    //             'phone' => '111111111',
    //             'address' => '101 Pine St, Town',
    //             'password' => Hash::make('password'),
    //             'role' => 'organization',
    //         ],
    //         [
    //             'name' => 'Resolve Tech',
    //             'email' => 'resolve@example.com',
    //             'phone' => '999999999',
    //             'address' => '246 Elm St, City',
    //             'password' => Hash::make('password'),
    //             'role' => 'organization',
    //         ],
    //     ];

    //     // Insert organizations into the database
    //     foreach ($organizations as $organization) {
    //         Organization::create($organization);
    //     }
    // }


    //for industrial based supervisors

    public function run (){

        $IBS = [
            [
                'name'=> 'Johnson James',
                'email'=> 'jj@testing.com',
                'phone'=> '09123456788',
                'password'=> Hash::make('password'),
                'organization_id'=> '1',
                'role'=> 'supervisor'
            ],

            [
                'name'=> 'Joshua Ade',
                'email'=> 'Adej@testing.com',
                'phone'=> '000121212',
                'password'=> Hash::make('password'),
                'organization_id'=> '1',
                'role'=> 'supervisor'
            ],

            [
                'name'=> 'Shile Basorun',
                'email'=> 'Shba@testing.com',
                'phone'=> '981218271272',
                'password'=> Hash::make('password'),
                'organization_id'=> '2',
                'role'=> 'supervisor'
            ],

            [
                'name'=> 'Basorunga Ola',
                'email'=> 'Baola@testing.com',
                'phone'=> '9813617617233',
                'password'=> Hash::make('password'),
                'organization_id'=> '2',
                'role'=> 'supervisor'
            ],

            [
                'name'=> '',
                'email'=> '',
                'phone'=> '',
                'password'=> Hash::make('password'),
                'organization_id'=> '3',
                'role'=> 'supervisor'
            ],

            [
                'name'=> '',
                'email'=> '',
                'phone'=> '',
                'password'=> Hash::make('password'),
                'organization_id'=> '3',
                'role'=> 'supervisor'
            ],

            [
                'name'=> '',
                'email'=> '',
                'phone'=> '',
                'password'=> Hash::make('password'),
                'organization_id'=> '4',
                'role'=> 'supervisor'
            ],

            [
                'name'=> '',
                'email'=> '',
                'phone'=> '',
                'password'=> Hash::make('password'),
                'organization_id'=> '4',
                'role'=> 'supervisor'
            ],

            [
                'name'=> '',
                'email'=> '',
                'phone'=> '',
                'password'=> Hash::make('password'),
                'organization_id'=> '5',
                'role'=> 'supervisor'
            ],

            [
                'name'=> '',
                'email'=> '',
                'phone'=> '',
                'password'=> Hash::make('password'),
                'organization_id'=> '5',
                'role'=> 'supervisor'
            ],
            
            ];
            foreach ($IBS as $IBSs) {
                Supervisor::create($IBSs);
            }
    }

    // public function run()
    // {
    //     $supervisors = [
    //         [
    //             'name' => 'John Doe',
    //             'email' => 'john.doe@example.com',
    //             'department_id' => 1, // Assuming department with ID 1
    //             'phone' => '123456789',
    //             'password' => Hash::make('password'),
    //             'role' => 'department',
    //         ],
    //         [
    //             'name' => 'Jane Smith',
    //             'email' => 'jane.smith@example.com',
    //             'department_id' => 2, // Assuming department with ID 2
    //             'phone' => '987654321',
    //             'password' => Hash::make('password'),
    //             'role' => 'department',
    //         ],
    //         [
    //             'name' => 'Alice Johnson',
    //             'email' => 'alice.johnson@example.com',
    //             'department_id' => 3, // Assuming department with ID 3
    //             'phone' => '555555555',
    //             'password' => Hash::make('password'),
    //             'role' => 'department',
    //         ],
    //         [
    //             'name' => 'Bob Wilson',
    //             'email' => 'bob.wilson@example.com',
    //             'department_id' => 4, // Assuming department with ID 4
    //             'phone' => '111111111',
    //             'password' => Hash::make('password'),
    //             'role' => 'department',
    //         ],
    //         [
    //             'name' => 'Eva Brown',
    //             'email' => 'eva.brown@example.com',
    //             'department_id' => 5, // Assuming department with ID 5
    //             'phone' => '999999999',
    //             'password' => Hash::make('password'),
    //             'role' => 'department',
    //         ],
    //     ];

    //     // Insert supervisors into the database
    //     foreach ($supervisors as $supervisor) {
    //         DepartmentSupervisor::create($supervisor);
    //     }
    // }
}
