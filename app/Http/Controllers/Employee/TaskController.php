<?php

namespace App\Http\Controllers\Employee;

use App\Employee;
use App\EmployeeTaskToEmployee;
use App\Http\Controllers\Controller;
use App\Subtask;
use App\Task;
use App\Unit;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $id = auth()->user()->employee->id;
        $tasks = Task::where('employee_id', $id)
            ->whereNotIn('status', ['done', 'cancel'])
            ->get();
        $subtasks = Subtask::where('employee_id', auth()->user()->employee->id)
            ->whereNotIn('status', ['done', 'cancel'])
            ->get();
        $employees = Employee::whereNotIn('id', [$id])->get();
        $units = Unit::get();
        return view('employee.task.index', compact('units', 'tasks', 'employees', 'subtasks'));
    }

    public function update_task(Request $request, $id)
    {
        if ($request->file('file')) {
            $destinationPath = 'task/done';
            $typefile = $request->file->getClientOriginalExtension();
            $filename = $destinationPath . '/' . uniqid() . '.' . $typefile;
            $request->file->move(public_path($destinationPath), $filename);
        }
        $task = Task::where('id', $id)->first();

        if ($request->status == 'on progress') {
            $task->update([
                'status' => $request->status
            ]);
            return redirect()->back()->with('success', 'Task Berhasil di Update');
        } else {
            $checkSubtask = Subtask::where('task_id', $task->id)
                ->whereIn('status', ['open', 'on progress'])
                ->first();
            if (!$checkSubtask) {
                $task->update([
                    'status' => $request->status,
                    'completed_time' => $request->status == 'done' ? date('Y-m-d H:i:s') : null,
                    'attach_done' => $request->file ? $filename : null,
                    'report_done' => $request->report
                ]);
                try {
                    $phone = $task->employee->phone;
                    $name = str_replace(' ', "%20", $task->employee->first_name . ' ' . $task->employee->last_name);
                    $service = "M009";
                    $taskId = $task->id;
                    $taskName = str_replace(" ", "%20", $task->task);
                    $taskCreator = str_replace(" ", "%20", $task->user->name);

                    $client = new Client();
                    $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator;
                    $storeToBot = $client->post($url);
                    return redirect()->back()->with('success', 'Task Berhasil di Update');
                } catch (\Throwable $th) {
                    return redirect()->back()->with('success', 'Task Berhasil di Update, Bot Notifikasi tidak Terkirim');
                }
            } else {
                return redirect()->back()->with('error', 'Ada Subtask yang masih belum selesai');
            }
        }
    }
    public function update_subtask(Request $request, $id)
    {
        if ($request->file('file')) {
            $destinationPath = 'subtask/done';
            $typefile = $request->file->getClientOriginalExtension();
            $filename = $destinationPath . '/' . uniqid() . '.' . $typefile;
            $request->file->move(public_path($destinationPath), $filename);
        }
        $task = Subtask::where('id', $id)->first();
        $task->update([
            'status' => $request->status,
            'completed_time' => $request->status == 'done' ? date('Y-m-d H:i:s') : null,
            'attach_done' => $request->file != null ? $filename : null,
            'report_done' => $request->report
        ]);
        if($request->status == 'done') {
            try {
                $client = new Client();
                $url = env("API_URL") . 'call-service/' . $task->employee->phone;
                $headers = [
                    'Content-Type' => 'application/json',
                ];
                $data = [
                    'name' => $task->employee->first_name . ' ' . $task->employee->last_name,
                    'id' => $task->id,
                    'subtask' => $task->description,
                    'code'=> 'S001'
                ];
                $storeToBot = $client->post($url, [
                    'headers' => $headers,
                    'json' => $data,
                ]);
                return redirect()->back()->with('success','Subtask Berhasil Diselesaikan');
            } catch (\Throwable $th) {
                return redirect()->back()->with('success','Subtask Berhasil Diselesaikan, Bot Notifikasi tidak Terkirim');
            }
        }
        return redirect()->back()->with('success', 'Subtask Berhasil di Update');
    }

    public function detail($id)
    {
        $task = Task::with('subtasks')->where('id', $id)->first();
        return view('employee.task.detail', compact('task'));
    }

    public function store(Request $request)
    {
        $task = Task::create([
            'employee_id' => auth()->user()->employee->id,
            'user_id' => auth()->user()->id,
            'task' => preg_replace( "/\r|\n/", "",trim($request->task)),
            'note' => $request->note,
            'category' => $request->category,
            'deadline' => date('Y-m-d H:i:s', strtotime($request->deadline)),
            'is_priority' => $request->is_priority,
            'unit_id' => $request->unit,
            'service_id' => $request->service,
            'status' => 'open',
            'is_approved' => '0',
        ]);
        try {
            $phone = $task->employee->phone;
            $name = str_replace(' ', "%20", $task->employee->first_name . ' ' . $task->employee->last_name);
            $service = "M001";
            $taskId = $task->id;
            $taskName = str_replace(" ", "%20", $task->task);
            $taskCreator = str_replace(" ", "%20", $task->user->name);

            $client = new Client();
            $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator;
            $storeToBot = $client->post($url);
            if ($task) {
                return redirect()->back()->with('success', "Task Berhasil ditambahkan, tunggu atasan Approved");
            } else {
                return redirect()->back()->with('error', "Task Gagal Ditambahkan");
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('success', "Task Berhasil ditambahkan, Bot Notifikasi tidak Terkirim, Silahkan Hubungi Admin");
        }
    }

    public function store_subtask(Request $request)
    {
        if ($request->file('file')) {
            $destinationPath = 'task/subtask';
            $typefile = $request->file->getClientOriginalExtension();
            $filename = $destinationPath . '/' . uniqid() . '.' . $typefile;
            $request->file->move(public_path($destinationPath), $filename);
        }
        $subtask = Subtask::create([
            'task_id' => $request->task_id,
            'employee_id' => $request->employee_id,
            'deadline' => date('Y-m-d H:i:s', strtotime($request->deadline)),
            'description' => $request->description,
            'status' => 'open',
            'file' => $request->file != null ? $filename : null,
        ]);

        try {
            $phone = $subtask->employee->phone;
            $name = str_replace(' ', "%20", $subtask->employee->first_name . ' ' . $subtask->employee->last_name);
            $service = "M006";
            $taskId = $subtask->id;
            $taskName = str_replace(" ", "%20", $subtask->task->task . " | " . $subtask->description);
            $taskCreator = str_replace(" ", "%20", $subtask->task->employee->first_name . ' ' . $subtask->task->employee->last_name);

            $client = new Client();
            $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator;
            $storeToBot = $client->post($url);

            return redirect()->back()->with('success', 'Subtask Berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('success', 'Subtask Berhasil ditambahkan, Bot Notifikasi tidak Terkirim');
        }
    }

    public function history()
    {
        $tasks = Task::where('employee_id', auth()->user()->employee->id)
            ->where('status', 'done')
            ->get();

        $subtasks = Subtask::where('employee_id', auth()->user()->employee->id)
            ->where('status', 'done')
            ->get();

        return view('employee.task.history', compact('tasks', 'subtasks'));
    }
}