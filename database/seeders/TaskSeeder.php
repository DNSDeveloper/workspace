<?php

namespace Database\Seeders;

use App\Subtask;
use App\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::create([
            'unit_id' => 1,
            'service_id' => 1,
            'employee_id' => 1,
            'user_id' => 1,
            'category' => 'periodik perminggu',
            'is_approved' => 1,
            'task' => 'Create Aplikasi Absen',
            'deadline' => '2024-02-01 17:00:00',
            'note' => 'Jangan Lupa dikerjain',
            'status' => 'open',
            'is_priority' => 1,
        ]);
        Task::create([
            'unit_id' => 2,
            'service_id' => 4,
            'employee_id' => 2,
            'user_id' => 1,
            'category' => 'periodik perbulan',
            'is_approved' => 1,
            'task' => 'Edit Video',
            'deadline' => '2024-02-01 17:00:00',
            'note' => 'Jangan Lupa dikerjain',
            'status' => 'open',
            'is_priority' => 0,
        ]);

        Subtask::create([
            'task_id' => 1,
            'employee_id'=> 2,
            'description'=> 'tes',
            'deadline'=>'2024-02-01 17:00:00' ,
            'status'=> 'open'
        ]);
        Subtask::create([
            'task_id' => 2,
            'employee_id'=> 1,
            'description'=> 'tes',
            'deadline'=>'2024-02-01 17:00:00' ,
            'status'=> 'open'
        ]);
    }
}