<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\DailyReport;
use App\Employee;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index() {
        // $employees = Employee::with('attendance')->whereHas('attendance',function($q) {
        //     $q->latest()->whereDate('created_at','=', Carbon::now());
        // })->get();
        // dd($employees);

        $todayAttendances = Attendance::whereDate('created_at',Carbon::now())->get();

        
        // dd(Attendance::whereDate('created_at',Carbon::now())->get());
        $reports = DailyReport::whereDate('created_at',today())->get();
        $attendances = Attendance::get();
        $days = Carbon::now()->month(date('m'))->daysInMonth; // 28
        return view('admin.index',compact('todayAttendances','days','reports','attendances'));
    }

    public function reset_password() {
        return view('auth.reset-password');
    }

    public function update_password(Request $request) {
        $user = Auth::user();
        dd($user->password);
        if($user->password == Hash::make($request->old_password)) {
            dd($request->all());
        } else {
            $request->session()->flash('error', 'Password Salah');
            return back();
        }
    }
}