<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Task;
use Illuminate\Http\Request;

class APIController extends Controller
{
    public function task($status, $token)
    {
        if ($token  == 'DnST3ch2024!') {
            $replace = str_replace('-', ' ', $status);
            $tasks = Task::where('status', $replace)->get();

            $data = [];
            if ($tasks->count() > 0) {
                $data['count'] = $tasks->count();
                foreach ($tasks as $task) {
                    $data_task['id'] = $task->id;
                    $data_task['task'] = $task->task;
                    $data['detail'][$task->employee->first_name . ' ' . $task->employee->last_name][] = $data_task;
                }
                return response()->json([
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'message' => "Task with status $replace not available"
            ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
    }
}