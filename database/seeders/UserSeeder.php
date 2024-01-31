<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Attendance;
use App\Department;
use \DateTime as DateTime;
use App\Role;
use App\User;
use App\Employee;
use App\Position;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeRole = Role::where('name', 'employee')->first();
        $adminRole =  Role::where('name', 'admin')->first();

        $admin = User::create([
            'name' => 'Admin User',
            'username'=> 'XYADM',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password')
        ]);

        $employee = User::create([
            'name' => 'Dwi Arya Putra',
            'username'=> 'ZXDWI',
            'email' => 'arya@gmail.com',
            'password' => Hash::make('password')
        ]);
        $employee1 = User::create([
            'name' => 'Reza Aditya',
            'username'=> 'ZXREZ',
            'email' => 'reza@gmail.com',
            'password' => Hash::make('password')
        ]);
        $employee2 = User::create([
            'name' => 'Vio Manich',
            'username'=> 'ZXVIO',
            'email' => 'vio@gmail.com',
            'password' => Hash::make('password')
        ]);
        $employee3 = User::create([
            'name' => 'Jeni',
            'username'=> 'ZXJEN',
            'email' => 'jeni@gmail.com',
            'password' => Hash::make('password')
        ]);
        $employee4 = User::create([
            'name' => 'Nazla',
            'username'=> 'ZXNAZ',
            'email' => 'nazla@gmail.com',
            'password' => Hash::make('password')
        ]);
        $employee5 = User::create([
            'name' => 'Silvi',
            'username'=> 'ZXSIL',
            'email' => 'silvi@gmail.com',
            'password' => Hash::make('password')
        ]);
        $employee6 = User::create([
            'name' => 'HENDRA',
            'username'=> 'ZXHEN',
            'email' => 'hendra@gmail.com',
            'password' => Hash::make('password')
        ]);
        $employee7 = User::create([
            'name' => 'SAMSUL',
            'username'=> 'ZXSAM',
            'email' => 'samsul@gmail.com',
            'password' => Hash::make('password')
        ]);
        $employee8 = User::create([
            'name' => 'ABID',
            'username'=> 'ZXABI',
            'email' => 'abid@gmail.com',
            'password' => Hash::make('password')
        ]);
        $employee9 = User::create([
            'name' => 'SITI',
            'username'=> 'ZXSIT',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password')
        ]);

        Position::create([
            'name'=> 'Leader',
        ]);

        Position::create([
            'name'=> 'Karyawan'
        ]);

        $employee->roles()->attach($employeeRole);
        $employee1->roles()->attach($employeeRole);
        $employee2->roles()->attach($employeeRole);
        $employee3->roles()->attach($employeeRole);
        $employee4->roles()->attach($employeeRole);
        $employee5->roles()->attach($employeeRole);
        $employee6->roles()->attach($employeeRole);
        $employee7->roles()->attach($employeeRole);
        $employee8->roles()->attach($employeeRole);
        $employee9->roles()->attach($employeeRole);
        
        $dob = new DateTime('1997-09-15');
        $join = new DateTime('2020-01-15');
        $admin->roles()->attach($adminRole);
        
        $employee = Employee::create([
            'user_id' => $employee->id,
            'first_name' => 'Dwi Arya',
            'last_name' => 'Putra',
            'dob' => $dob->format('Y-m-d'),
            'sex' => 'Male',
            'position_id' => '1',
            'department_id' => '1',
            'join_date' => $join->format('Y-m-d'),
            'salary' => 10520.75
        ]);
        Employee::create([
            'user_id' => $employee1->id,
            'first_name' => 'Reza',
            'last_name' => 'Adithya',
            'dob' => $dob->format('Y-m-d'),
            'sex' => 'Male',
            'position_id' => '2',
            'department_id' => '1',
            'join_date' => $join->format('Y-m-d'),
            'salary' => 10520.75
        ]);
        Employee::create([
            'user_id' => $employee2->id,
            'first_name' => 'Vio',
            'last_name' => 'Manich',
            'dob' => $dob->format('Y-m-d'),
            'sex' => 'Male',
            'position_id' => '2',
            'department_id' => '1',
            'join_date' => $join->format('Y-m-d'),
            'salary' => 10520.75
        ]);
        Employee::create([
            'user_id' => $employee3->id,
            'first_name' => 'Jen',
            'last_name' => 'nifer',
            'dob' => $dob->format('Y-m-d'),
            'sex' => 'Male',
            'position_id' => '2',
            'department_id' => '1',
            'join_date' => $join->format('Y-m-d'),
            'salary' => 10520.75
        ]);
        Employee::create([
            'user_id' => $employee4->id,
            'first_name' => 'Nazla ',
            'last_name' => 'Sania',
            'dob' => $dob->format('Y-m-d'),
            'sex' => 'Male',
            'position_id' => '2',
            'department_id' => '1',
            'join_date' => $join->format('Y-m-d'),
            'salary' => 10520.75
        ]);
        Employee::create([
            'user_id' => $employee5->id,
            'first_name' => 'Nazla ',
            'last_name' => 'Sania',
            'dob' => $dob->format('Y-m-d'),
            'sex' => 'Male',
            'position_id' => '2',
            'department_id' => '1',
            'join_date' => $join->format('Y-m-d'),
            'salary' => 10520.75
        ]);
        Employee::create([
            'user_id' => $employee6->id,
            'first_name' => 'Nazla ',
            'last_name' => 'Sania',
            'dob' => $dob->format('Y-m-d'),
            'sex' => 'Male',
            'position_id' => '2',
            'department_id' => '1',
            'join_date' => $join->format('Y-m-d'),
            'salary' => 10520.75
        ]);
        Employee::create([
            'user_id' => $employee7->id,
            'first_name' => 'Nazla ',
            'last_name' => 'Sania',
            'dob' => $dob->format('Y-m-d'),
            'sex' => 'Male',
            'position_id' => '2',
            'department_id' => '1',
            'join_date' => $join->format('Y-m-d'),
            'salary' => 10520.75
        ]);
        Employee::create([
            'user_id' => $employee8->id,
            'first_name' => 'Nazla ',
            'last_name' => 'Sania',
            'dob' => $dob->format('Y-m-d'),
            'sex' => 'Male',
            'position_id' => '2',
            'department_id' => '1',
            'join_date' => $join->format('Y-m-d'),
            'salary' => 10520.75
        ]);
        Employee::create([
            'user_id' => $employee9->id,
            'first_name' => 'Nazla ',
            'last_name' => 'Sania',
            'dob' => $dob->format('Y-m-d'),
            'sex' => 'Male',
            'position_id' => '2',
            'department_id' => '1',
            'join_date' => $join->format('Y-m-d'),
            'salary' => 10520.75
        ]);

        Department::create(['name' => 'Developer']);
        Department::create(['name' => 'Finance']);
        Department::create(['name' => 'Talent']);
        Department::create(['name' => 'Editor']);
    }
}