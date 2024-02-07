<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\Department;
use App\Employee;
use App\Http\Controllers\Controller;
use App\Leave;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
        $leave->decline_reason = $request->decline_reason != null ? $request->decline_reason : null;
        $start = date('d',strtotime($leave->start_date));        
        $end = $leave->end_date == null ? null : date('d',strtotime($leave->end_date));
        if($leave->status == 'approved') {
            if($end == null) {
                $attendance = Attendance::create([
                    'employee_id'=> $leave->employee_id,
                    'status'=> $leave->reason,
                    'registered'=> 'leave',
                    'created_at'=> $leave->start_date,
                ]);
            } else {
                $startPeriod = Carbon::parse($leave->start_date);
                $endPeriod   = Carbon::parse($leave->end_date);
                $period = CarbonPeriod::create($startPeriod, '1 day', $endPeriod);
                foreach ($period as $date) {
                    if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                        $attendance = Attendance::create([
                            'employee_id' => $leave->employee_id,
                            'status' => $leave->reason,
                            'registered' => 'leave',
                            'created_at' => $date->format('Y-m-d H:i')
                        ]);
                    }
                }
            }
        }
        $leave->save();
        $request->session()->flash('success', ' Status Cuti Berhasil Diubah');
        
        return back();
    }
}