<?php

namespace App\Http\Controllers\Employee;

use App\Employee;
use App\EmployeeTaskToEmployee;
use App\Http\Controllers\Controller;
use App\Subtask;
use App\Task;
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
        return view('employee.task.index', compact('tasks', 'employees','subtasks'));
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
        $task->update([
            'status' => $request->status,
            'completed_time' => $request->status == 'done' ? date('Y-m-d H:i:s') : null,
            'attach_done'=> $request->status == 'done' ? $filename : null,
            'report_done'=> $request->report
        ]);
        $request->session()->flash('success', 'Task Successfully Updated');
        return redirect()->back();
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
        if($request->file('file')) {
            $destinationPath = 'task/subtask';
            $typefile = $request->file->getClientOriginalExtension();
            $filename = $destinationPath . '/' . uniqid() . '.'. $typefile;  
            $request->file->move(public_path($destinationPath), $filename);
        }
        $tasks = Subtask::create([
            'task_id'=> $request->task_id,
            'employee_id'=> $request->employee_id,
            'deadline'=> date('Y-m-d H:i:s', strtotime($request->deadline)),
            'description'=> $request->description,
            'status'=> 'open',
            'file'=> $request->file != null ? $filename : null,
        ]);

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