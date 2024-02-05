<?php

namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;

use App\Leave;
use App\Rules\DateRange;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LeaveController extends Controller
{
    public function index() {
        $employee = Auth::user()->employee;
        $data = [
            'employee' => $employee,
            'leaves' => $employee->leave
        ];
        return view('employee.leaves.index')->with($data);
    }
    public function create() {
        $employee = Auth::user()->employee;
        $data = [
            'employee' => $employee
        ];

        return view('employee.leaves.create')->with($data);
    }

    public function store(Request $request, $employee_id) {
        $red=route('employee.leaves.create');
        // $date = date('Y-m-d',strtotime($request->input('date')));
        
        // [$start, $end] = explode(' - ', $request->input('date_range'));
        // $start = date('Y-m-d',strtotime(Carbon::parse($start)));
        // $end = date('Y-m-d',strtotime(Carbon::parse($end)));
        // $today = date('Y-m-d');
        
        // if($request->input('multiple-days') == 'yes') {
        //     if($start <= $today || $end <= $start ) {
        //         $request->session()->flash('error','Pengajuan Cuti hanya bisa dilakukan maksimal H-1 dari tanggal ketidakhadiran');
        //         return redirect()->back();
        //     }
        // } else {
        //     if($date <= $today) {
        //         $request->session()->flash('error','Pengajuan Cuti hanya bisa dilakukan maksimal H-1 dari tanggal ketidakhadiran');
        //         return redirect()->back();    
        //     }
        // }
        $data = [
            'employee' => Auth::user()->employee
        ];
        if($request->input('multiple-days') == 'yes') {
            $this->validate($request, [
                'reason' => 'required',
                'description' => 'required',
                'date_range' => new DateRange
            ]);
        } else {
            $this->validate($request, [
                'reason' => 'required',
                'description' => 'required'
            ]);
        }
        
        $values = [
            'employee_id' => $employee_id,
            'reason' => $request->input('reason'),
            'description' => $request->input('description'),
            'half_day' => $request->input('half-day')
        ];
        if($request->input('multiple-days') == 'yes') {
            [$start, $end] = explode(' - ', $request->input('date_range'));
            $values['start_date'] = Carbon::parse($start);
            $values['end_date'] = Carbon::parse($end);
        } else {
            $values['start_date'] = Carbon::parse($request->input('date'));
        }
        Leave::create($values);
        $request->session()->flash('success', 'Pengajuan Cuti Anda berhasil, tunggu persetujuan atasan.'); 
        return redirect()->route('employee.leaves.create')->with($data); 
    // }
    }

    public function edit($leave_id) {
        $leave = Leave::findOrFail($leave_id);
        Gate::authorize('employee-leaves-access', $leave);
        return view('employee.leaves.edit')->with('leave', $leave);
    }

    public function update(Request $request, $leave_id) {
        $leave = Leave::findOrFail($leave_id);
        Gate::authorize('employee-leaves-access', $leave);
        if($request->input('multiple-days') == 'yes') {
            $this->validate($request, [
                'reason' => 'required',
                'description' => 'required',
                'date_range' => new DateRange
            ]);
        } else {
            $this->validate($request, [
                'reason' => 'required',
                'description' => 'required'
            ]);
        }

        $leave->reason = $request->reason;
        $leave->description = $request->description;
        $leave->half_day = $request->input('half-day');
        if($request->input('multiple-days') == 'yes') {
            [$start, $end] = explode(' - ', $request->input('date_range'));
            $start = Carbon::parse($start);
            $end = Carbon::parse($end);
            $leave->start_date = $start;
            $leave->end_date = $end;
        } else {
            $leave->start_date = Carbon::parse($request->input('date'));
        }

        $leave->save();

        $request->session()->flash('success', 'Update Pengajuan Cuti Anda berhasil');
        return redirect()->route('employee.leaves.index');
    }

    public function destroy($leave_id) {
        $leave = Leave::findOrFail($leave_id);
        Gate::authorize('employee-leaves-access', $leave);
        $leave->delete();
        request()->session()->flash('success', 'Pengajuan Cuti Anda berhasil dihapus');

        return redirect()->route('employee.leaves.index');
    }
}