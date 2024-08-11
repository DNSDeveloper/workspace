<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Exports\SheetTask;
use App\Http\Controllers\Controller;
use App\Service;
use App\Subtask;
use App\Task;
use App\Unit;
use App\User;
use Exception;
use GuzzleHttp\Client;
use Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TaskController extends Controller
{
    public function index()
    {
        $units = Unit::with('tasks')
            ->get();
        $employees = Employee::get();
        $needConfirmed = Task::where('is_approved', 0)->get();
        $subtasks = Subtask::orderBy('created_at', 'desc')->get();
        return view('admin.task.index', compact('units', 'employees', 'needConfirmed', 'subtasks'));
    }

    public function create()
    {
        $employees = Employee::get();
        $units = Unit::get();

        return view('admin.task.create', compact('employees', 'units'));
    }

    public function fetchService(Request $request)
    {
        $services = Service::where('unit_id', $request->unit_id)->get();

        return response()->json($services);
    }

    public function detail($id)
    {
        $task = Task::with('subtasks')->where('id', $id)->first();
        return view('admin.task.detail', compact('task'));
    }

    public function history()
    {
        $units = Unit::with('tasks')
            ->get();
        return view('admin.task.history', compact('units'));
    }

    public function store(Request $request)
    {
        $user = User::where('name', $request->user_id)->first();
        $employee = Employee::where('id', $request->employee_id)->first();
        $task = Task::create([
            'employee_id' => $request->employee_id,
            'user_id' => $user->id,
            'task' => $request->task,
            'note' => $request->note,
            'category' => $request->category,
            'deadline' => date('Y-m-d H:i:s', strtotime($request->deadline)),
            'is_priority' => $request->is_priority,
            'unit_id' => $request->unit,
            'service_id' => $request->service,
            'status' => 'open'
        ]);
        try {
            $phone = $employee->phone;
            $name = str_replace(' ', "%20", $employee->first_name . ' ' . $employee->last_name);
            $service = "M002";
            $taskId = $task->id;
            $taskName = str_replace(" ", "%20", $request->task);
            $taskCreator = str_replace(" ", "%20", $user->name);
            $client = new Client();

            $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator;
            $storeToBot = $client->post($url);
            if ($task) {
                return redirect()->back()->with('success', "Task Berhasil ditambahkan ke $employee->first_name");
            } else {
                return redirect()->back()->with('error', "Task Gagal Ditambahkan");
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('success', "Task Berhasil ditambahkan ke $employee->first_name, Bot Notifikasi tidak Terkirim");
        }
    }

    public function cancel_task(Request $request, $id)
    {
        $task = Task::where('id', $id)->first();
        $task->update([
            'status' => 'cancel',
            'note' => $request->note,
        ]);
        try {
            $phone = $task->employee->phone;
            $name = str_replace(' ', "%20", $task->employee->first_name . ' ' . $task->employee->last_name);
            $service = "M007";
            $taskId = $task->id;
            $taskName = str_replace(" ", "%20", $task->task);
            $taskCreator = str_replace(" ", "%20", $task->user->name);

            $client = new Client();
            $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator;
            $storeToBot = $client->post($url);

            return redirect()->back()->with('success', 'Task Berhasil Di Cancel');
        } catch (\Throwable $th) {
            return redirect()->back()->with('success', 'Task Berhasil Di Cancel, Bot Notifikasi tidak Terkirim');
        }
    }

    public function approved_task($id)
    {
        $task = Task::where('id', $id)->first();
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
            $taskCreator = str_replace(" ", "%20", $task->user->name);
            $deadline = $task->deadline;

            $client = new Client();
            $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator . '&deadlineDate=' . $deadline;
            $storeToBot = $client->post($url);

            return redirect()->route('admin.task.index')->with('success', 'Task Berhasil di Approved');
        } catch (\Throwable $th) {
            return redirect()->route('admin.task.index')->with('success', 'Task Berhasil di Approve, Bot Notifikasi tidak Terkirim ');
        }
    }
}
