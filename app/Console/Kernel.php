<?php

namespace App\Console;

use App\Attendance;
use App\Employee;
use App\Subtask;
use App\Task;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $tasks = Task::with('subtasks') 
            ->where('category', 'periodik perminggu')
                ->where('is_approved', 1)
                ->where('status', 'done')
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique('task');
            dump($tasks);
            foreach ($tasks as $task) {
                Task::create([
                    'task' => $task->task,
                    'employee_id' => $task->employee_id,
                    'user_id' => $task->user_id,
                    'unit_id' => $task->unit_id,
                    "service_id" => $task->service_id,
                    "is_priority" => 1,
                    "status" => "open",
                    "category" => "periodik perminggu",
                    "deadline" => date('Y-m-d H:i:s', strtotime('+7 day', strtotime($task->deadline))),
                ]);
                foreach ($task->subtasks as $subtask) {
                    Subtask::create([
                        'task_id'=> $subtask->task_id,
                        'employee_id'=> $subtask->employee_id,
                        'description'=> $subtask->description,
                        'deadline'=> date('Y-m-d H:i:s', strtotime('+7 day', strtotime($subtask->deadline))),
                        'status'=> 'open'
                    ]);
                }
            }
        })->weeklyOn(1,"8:00");
        $schedule->call(function () {
            $tasks = Task::with('subtasks') 
            ->where('category', 'periodik perbulan')
                ->where('is_approved', 1)
                ->where('status', 'done')
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique('task');
            foreach ($tasks as $task) {
                Task::create([
                    'task' => $task->task,
                    'employee_id' => $task->employee_id,
                    'user_id' => $task->user_id,
                    'unit_id' => $task->unit_id,
                    "service_id" => $task->service_id,
                    "is_priority" => 1,
                    "status" => "open",
                    "category" => "periodik perbulan",
                    "deadline" => date('Y-m-d H:i:s', strtotime('+1 month', strtotime($task->deadline))),
                ]);
                foreach ($task->subtasks as $subtask) {
                    Subtask::create([
                        'task_id'=> $subtask->task_id,
                        'employee_id'=> $subtask->employee_id,
                        'description'=> $subtask->description,
                        'deadline'=> date('Y-m-d H:i:s', strtotime('+1 month', strtotime($subtask->deadline))),
                        'status'=> 'open'
                    ]);
                }
            }
        })->monthly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}