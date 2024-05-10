<?php

namespace App\Console;

use App\Attendance;
use App\Employee;
use App\Jobs\TesJobs;
use App\Subtask;
use App\Task;
use Carbon\Carbon;
use GuzzleHttp\Client;
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
        // $schedule->call(function () {
        //     $tasks = Task::with('subtasks')
        //         ->where('category', 'periodik perminggu')
        //         ->where('is_approved', 1)
        //         ->where('status', 'done')
        //         ->orderBy('created_at', 'desc')
        //         ->get()
        //         ->unique('task');
        //     dump($tasks);
        //     foreach ($tasks as $task) {
        //         $newTask = Task::create([
        //             'task' => $task->task,
        //             'employee_id' => $task->employee_id,
        //             'user_id' => $task->user_id,
        //             'unit_id' => $task->unit_id,
        //             "service_id" => $task->service_id,
        //             "is_priority" => 1,
        //             "status" => "open",
        //             "category" => "periodik perminggu",
        //             "deadline" => date('Y-m-d H:i:s', strtotime('+7 day', strtotime($task->deadline))),
        //         ]);
        //         foreach ($task->subtasks as $subtask) {
        //             Subtask::create([
        //                 'task_id' => $newTask->id,
        //                 'employee_id' => $subtask->employee_id,
        //                 'description' => $subtask->description,
        //                 'deadline' => date('Y-m-d H:i:s', strtotime('+7 day', strtotime($subtask->deadline))),
        //                 'status' => 'open'
        //             ]);
        //         }
        //     }
        // })->everyMinute();
        // $schedule->call(function () {
        //     $tasks = Task::with('subtasks')
        //         ->where('category', 'periodik perbulan')
        //         ->where('is_approved', 1)
        //         ->where('status', 'done')
        //         ->orderBy('created_at', 'desc')
        //         ->get()
        //         ->unique('task');
        //     foreach ($tasks as $task) {
        //         Task::create([
        //             'task' => $task->task,
        //             'employee_id' => $task->employee_id,
        //             'user_id' => $task->user_id,
        //             'unit_id' => $task->unit_id,
        //             "service_id" => $task->service_id,
        //             "is_priority" => 1,
        //             "status" => "open",
        //             "category" => "periodik perbulan",
        //             "deadline" => date('Y-m-d H:i:s', strtotime('+1 month', strtotime($task->deadline))),
        //         ]);
        //         foreach ($task->subtasks as $subtask) {
        //             Subtask::create([
        //                 'task_id' => $subtask->task_id,
        //                 'employee_id' => $subtask->employee_id,
        //                 'description' => $subtask->description,
        //                 'deadline' => date('Y-m-d H:i:s', strtotime('+1 month', strtotime($subtask->deadline))),
        //                 'status' => 'open'
        //             ]);
        //         }
        //     }
        // })->monthly();

        // $schedule->call(function () {
        //     $tasks = Task::where('is_approved', 1)
        //         ->whereIn('status', ['open', 'confirmed', 'on progress'])
        //         ->get();
        //         try {
        //             Log::info('success');
        //             foreach ($tasks as $task) {
        //                 $today = date('Y-m-d H:i',strtotime(Carbon::now()));
        //                 $phone = $task->employee->phone;
        //                 $name = str_replace(' ', "%20", $task->employee->first_name . ' '. $task->employee->last_name);
        //                 $taskId = $task->id;
        //                 $taskName = str_replace(" ", "%20", $task->task);
        //                 $taskCreator = str_replace(" ", "%20", $task->user->name);
        //                 $client = new Client();
        //                 $deadline = Carbon::parse($task->deadline);
        //                 if(date('Y-m-d H:i',strtotime($deadline->subDay(1))) === $today) {
        //                     $service = "M003";
        //                     $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator;
        //                     $storeToBot = $client->post($url);
        //                     Log::info('H-1 TERKIRIM KE '.$task->employee->first_name);
        //                 } else if (date('Y-m-d',strtotime($deadline)).' 00:00' === $today.' 00:00') {
        //                     $service = "M004";
        //                     $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator;
        //                     $storeToBot = $client->post($url);
        //                     Log::info('H0 TERKIRIM KE '.$task->employee->first_name);
        //                 } else if (date('Y-m-d',strtotime($deadline->addDay(1))).' 00:00' === $today.' 00:00') {
        //                     $service = "M005";
        //                     $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator;
        //                     $storeToBot = $client->post($url);
        //                     Log::info('H+1 TERKIRIM KE '.$task->employee->first_name);
        //                 }
        //             }
        //         } catch (\Throwable $th) {
        //             Log::info($th);
        //             return;
        //         }
        // })->everyMinute();
        
        $schedule->command('reminder:masuk')->dailyAt('10:36')->withoutOverlapping();


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