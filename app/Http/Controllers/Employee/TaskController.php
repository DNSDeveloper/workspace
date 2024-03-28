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
        ->whereNotIn('status',['done','cancel'])
        ->get();
        $subtasks = Subtask::where('employee_id',auth()->user()->employee->id)
        ->whereNotIn('status',['done','cancel'])
        ->get();
        $employees = Employee::whereNotIn('id',[$id])->get();
        $units = Unit::get();
        return view('employee.task.index', compact('units','tasks', 'employees','subtasks'));
    }

    public function update_task(Request $request, $id)
    {
        if($request->file('file')) {
            $destinationPath = 'task/done';
            $typefile = $request->file->getClientOriginalExtension();
            $filename = $destinationPath . '/' . uniqid() . '.'. $typefile;  
            $request->file->move(public_path($destinationPath), $filename);
        }
        $task = Task::where('id', $id)->first();

        if($request->status == 'on progress') {
            $task->update([
                'status'=> $request->status
            ]);
            $request->session()->flash('success', 'Task Successfully Updated');
            return redirect()->back();  
        } else {
            $checkSubtask = Subtask::where('task_id',$task->id)
            ->whereIn('status',['open','on progress'])
            ->first();
            if(!$checkSubtask) {
                $task->update([
                    'status' => $request->status,
                    'completed_time' => $request->status == 'done' ? date('Y-m-d H:i:s') : null,
                    'attach_done'=> $request->file ? $filename : null,
                    'report_done'=> $request->report
                ]);
                $phone = $task->employee->phone;
                $name = str_replace(' ', "%20", $task->employee->first_name . ' '. $task->employee->last_name);
                $service = "M009";
                $taskId = $task->id;
                $taskName = str_replace(" ", "%20", $task->task);
                $taskCreator = str_replace(" ", "%20", $task->user->name);
                
                $client = new Client();
                $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator;
                $storeToBot = $client->post($url);
        
                $request->session()->flash('success', 'Task Successfully Updated');
                return redirect()->back();   
            } else {
                $request->session()->flash('error','Ada Subtask yang masih belum selesai');
                return redirect()->back();
            }   
        }
    }
    public function update_subtask(Request $request, $id)
    {
        if($request->file('file')) {
            $destinationPath = 'subtask/done';
            $typefile = $request->file->getClientOriginalExtension();
            $filename = $destinationPath . '/' . uniqid() . '.'. $typefile;  
            $request->file->move(public_path($destinationPath), $filename);
        }
        $task = Subtask::where('id', $id)->first();
        $task->update([
            'status' => $request->status,
            'completed_time' => $request->status == 'done' ? date('Y-m-d H:i:s') : null,
            'attach_done'=> $request->status == 'done' ? $filename : null,
            'report_done'=> $request->report
        ]);
        $request->session()->flash('success', 'Task Successfully Updated');

        return redirect()->back();
    }

    public function detail($id) {
        $task = Task::with('subtasks')->where('id',$id)->first();
        return view('employee.task.detail',compact('task'));
    }

    public function store(Request $request) {
        $task = Task::create([
            'employee_id' => auth()->user()->employee->id,
            'user_id' => auth()->user()->id,
            'task' => $request->task,
            'note' => $request->note,
            'category'=> $request->category,
            'deadline' => date('Y-m-d H:i:s', strtotime($request->deadline)),
            'is_priority' => $request->is_priority,
            'unit_id' => $request->unit,
            'service_id'=> $request->service,
            'status'=> 'open',
            'is_approved'=> '0',
        ]);
        $phone = $task->employee->phone;
        $name = str_replace(' ', "%20", $task->employee->first_name . ' '. $task->employee->last_name);
        $service = "M001";
        $taskId = $task->id;
        $taskName = str_replace(" ", "%20", $task->task);
        $taskCreator = str_replace(" ", "%20", $task->user->name);
        
        $client = new Client();
        $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator;
        $storeToBot = $client->post($url);
        
        if ($task) {
            $request->session()->flash('success', "Task Berhasil ditambahkan, tunggu atasan Approved");
        } else {
            $request->session()->flash('error', "Task Gagal Ditambahkan");
        }

        return redirect()->back();
    }

    public function store_subtask(Request $request) {
        if($request->file('file')) {
            $destinationPath = 'task/subtask';
            $typefile = $request->file->getClientOriginalExtension();
            $filename = $destinationPath . '/' . uniqid() . '.'. $typefile;  
            $request->file->move(public_path($destinationPath), $filename);
        }
        
        $subtask = Subtask::create([
            'task_id'=> $request->task_id,
            'employee_id'=> $request->employee_id,
            'deadline'=> date('Y-m-d H:i:s', strtotime($request->deadline)),
            'description'=> $request->description,
            'status'=> 'open',
            'file'=> $request->file != null ? $filename : null,
        ]);

        $phone = $subtask->employee->phone;
        $name = str_replace(' ', "%20", $subtask->employee->first_name . ' '. $subtask->employee->last_name);
        $service = "M006";
        $taskId = $subtask->id;
        $taskName = str_replace(" ", "%20", $subtask->task->task . " | " . $subtask->description);
        $taskCreator = str_replace(" ", "%20", $subtask->task->employee->first_name . ' '. $subtask->task->employee->last_name);
        
        $client = new Client();
        $url = env("API_URL")  . $phone . '?' . 'name=' . $name . '&service=' . $service . '&taskId=' . $taskId . '&task=' . $taskName . '&taskCreator=' . $taskCreator;
        $storeToBot = $client->post($url);
        
        $request->session()->flash('success','Subtask Successfully Added');
        return redirect()->back();
    }
    
    public function history() {
        $tasks = Task::where('employee_id',auth()->user()->employee->id)
        ->where('status','done')
        ->get();

        $subtasks = Subtask::where('employee_id',auth()->user()->employee->id)
        ->where('status','done')
        ->get();

        return view('employee.task.history',compact('tasks','subtasks'));
    }
}