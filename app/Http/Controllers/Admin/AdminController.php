<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\DailyReport;
use App\Employee;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index() {
        $startDay = Carbon::now()->startOfWeek(); // Mengambil awal minggu ini
        $endDay = Carbon::now()->endOfWeek(); // Mengambil akhir minggu ini

        $start = date('Y-m-d',strtotime($startDay));
        $end = date('Y-m-d',strtotime($endDay));
        
        $ranksAttendances = Attendance::select('employee_id',
                DB::raw('AVG(TIME_TO_SEC(created_at)) as average'),
                DB::raw('count(*) as total_data'),
                DB::raw('SUM(IF(status = "hadir", 1, 0)) as hadir'),
                DB::raw('SUM(IF(status = "terlambat", 1, 0)) as terlambat'),
                DB::raw('SUM(IF(registered = "leave", 1, 0)) as cuti'),
            )
            ->groupBy('employee_id')
            ->orderBy('total_data','desc')
            ->orderBy('hadir','desc')
            ->orderBy('terlambat','asc')
            ->orderBy('average','asc')
            ->whereBetween('created_at', [$start, $end])
            ->get();
        $todayAttendances = Attendance::whereDate('created_at',Carbon::now())->get();
        
        $reports = DailyReport::whereDate('created_at',today())->get();
        $attendances = Attendance::whereMonth('created_at',date('m'))->get();
        $days = Carbon::now()->month(date('m'))->daysInMonth; // 28
        return view('admin.index',compact('ranksAttendances','todayAttendances','days','reports','attendances'));
    }

    public function reset_password() {
        return view('auth.reset-password');
    }

    public function change_profile(Request $request) {
        if($request->file('profile')) {
            $typefile = $request->profile->getClientOriginalExtension();
            $filename = auth()->user()->name . '.'. $typefile;  
            $request->profile->move(public_path(), $filename);
        }
        $admin = User::where('id',auth()->user()->id)->first();
        $admin->update([
            'profile'=> $filename
        ]);
        $request->session()->flash('success','Berhasil Merubah Profile');
        return redirect()->back();
    }

    public function update_password(Request $request) {
        $user = Auth::user();
        if(!Hash::check($request->old_password, auth()->user()->password)){
            $request->session()->flash('error','Password Lama Salah');
            return redirect()->back();
        } else{
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->password)
            ]);
            $request->session()->flash('success','Password Berhasil Dirubah');
            return redirect()->back();
        }
    }
}