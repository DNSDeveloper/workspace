<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\Department;
use App\Employee;
use App\Http\Controllers\Controller;
use App\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index() {
        $leaves = Leave::all();
        $leaves = $leaves->map(function($leave, $key) {
            $employee = Employee::find($leave->employee_id);
            $employee->department = Department::find($employee->department_id)->name;
            $leave->employee = $employee;
            return $leave;
        });
        return view('admin.leaves.index')->with('leaves', $leaves);
    }

    public function update(Request $request, $leave_id){
        $this->validate($request, [
            'status' => 'required'
        ]);
        $leave = Leave::find($leave_id);
        $leave->status = $request->status;
        
        $start = date('d',strtotime($leave->start_date));        
        $end = $leave->end_date == null ? null : date('d',strtotime($leave->end_date));
        
        $m = date('m',strtotime($leave->start_date));
        $y = date('Y',strtotime($leave->start_date)); 
        
        if($leave->status == 'approved') {
            if($end == null) {
                $attendance = Attendance::create([
                    'employee_id'=> $leave->employee_id,
                    'status'=> $leave->reason,
                    'registered'=> 'leave',
                    'created_at'=> $leave->start_date,
                ]);
            } else {
                for ($start; $start <= $end; $start++) {
                    $attendance = Attendance::create([
                        'employee_id'=> $leave->employee_id,
                        'status'=> $leave->reason,
                        'registered'=> 'leave',
                        'created_at'=> $y . '-' . $m . '-'. $start.' '. date('H:i:s'),
                    ]);
                }
            }
        }
        $leave->save();
        $request->session()->flash('success', ' Status Cuti Berhasil Diubah');
        
        return back();
    }
}