<?php

namespace App\Console\Commands;

use App\Task;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoApprovedTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'approved:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = Task::where('is_approved', 0)->get();
        foreach ($tasks as $task) {
            $created_at = Carbon::parse($task->created_at)->addDay()->format('Y-m-d H:i');
            $today = date('Y-m-d H:i', strtotime(Carbon::now()));
            if ($created_at == $today) {
                $task->update([
                    'is_approved' => 1,
                    'status' => 'confirmed'
                ]);
                try {
                    $phone = $task->employee->phone;
                    $name = str_replace(' ', "%20", $task->employee->first_name . ' ' . $task->employee->last_name);
                    $service = "M008";
                    $taskId = $task->id;
                    $taskName = str_replace(" ", "%20", $task->task);
                    $taskCreator = "SYSTEM";
                    $deadline = $task->deadline;

                    $client = new Client();
                    $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator . '&deadlineDate=' . $deadline;
                    $storeToBot = $client->post($url);
                } catch (\Throwable $th) {
                    Log::error($th);
                }
                Log::info($task->task . 'approved by system');
            }
        }
    }
}
