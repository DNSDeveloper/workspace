<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Service;
use App\Task;
use App\Unit;
use App\User;
use Str;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $units = Unit::with('tasks')->get();
        return view('admin.task.index', compact('units'));
    }

    public function create()
    {
        $employees = Employee::get();
        $units = Unit::get();
        
        return view('admin.task.create', compact('employees','units'));
    }

    public function fetchService(Request $request) {
        $services = Service::where('unit_id',$request->unit_id)->get();
        
        return response()->json($services);
    }
    
    public function detail($id)
    {
        $task = Task::with('subtasks')->where('id',$id)->first();
        return view('admin.task.detail',compact('task'));
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
            'category'=> $request->category,
            'deadline' => date('Y-m-d H:i:s', strtotime($request->deadline)),
            'is_priority' => $request->is_priority,
            'unit_id' => $request->unit,
            'service_id'=> $request->service,
            'status'=> 'open'
        ]);
        if ($task) {
            $request->session()->flash('success', "Task Berhasil ditambahkan ke $employee->first_name");
        } else {
            $request->session()->flash('error', "Task Gagal Ditambahkan");
        }

        return redirect()->back();
    }

    public function cancel_task(Request $request,$id) {
        $task = Task::where('id',$id)->first();
        $task->update([
           'status'=> 'cancel',
           'note'=> $request->note, 
        ]);
        $request->session()->flash('success', "Task Berhasil Di Cancel");

        return redirect()->back();
    }
}