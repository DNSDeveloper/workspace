<?php

namespace App\Http\Controllers\Employee;

use App\Attendance;
use App\Department;
use App\Employee;
use App\Http\Controllers\Controller;
use App\Subtask;
use App\Task;
use App\User;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index() {
        $tasks = Task::where('employee_id',Auth::user()->employee->id)
        ->whereIn('status',['open','on progress'])
        ->get();
        $subtask = Subtask::where('employee_id',Auth::user()->employee->id)
        ->whereIn('status',['open','on progress'])
        ->get();
        $attendance = Attendance::where('employee_id',auth()->user()->employee->id)
        ->whereDate('created_at',Carbon::now())
        ->first();
        $data = [
            'employee' => Auth::user()->employee,
            'tasks'=> $tasks,
            'attendance'=> $attendance,
            'subtask'=> $subtask
        ];
        return view('employee.index')->with($data);
    }

    public function profile() {
        $data = [
            'employee' => Auth::user()->employee
        ];
        return view('employee.profile')->with($data);
    }

    public function profile_edit($employee_id) {
        $data = [
            'employee' => Employee::findOrFail($employee_id),
            'departments' => Department::all(),
            'desgs' => ['Manajer', 'Asistent Manajer', 'Projek Manajer', 'Staff']
        ];
        Gate::authorize('employee-profile-access', intval($employee_id));
        return view('employee.profile-edit')->with($data);
    }

    public function profile_update(Request $request, $employee_id) {
        Gate::authorize('employee-profile-access', intval($employee_id));
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'photo' => 'image|nullable'
        ]);
        $employee = Employee::findOrFail($employee_id);
        $employee->first_name = $request->first_name;
        $employee->last_name = $request->last_name;
        $employee->dob = $request->dob;
        $employee->sex = $request->gender;
        $employee->join_date = $request->join_date;
        $employee->position_id = $request->position_id;
        $employee->department_id = $request->department_id;
        if ($request->hasFile('photo')) {
            $filename_ext = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filename_ext, PATHINFO_FILENAME);
            $ext = $request->file('photo')->getClientOriginalExtension();
            $filename_store =  strtolower($request->first_name) .'.'.$ext;
            $image = $request->file('photo');
            $image_resize = Image::make($image->getRealPath());              
            $image_resize->resize(300, 300);
            $image_resize->save($filename_store);
            $employee->photo = $filename_store;
        }
        $employee->save();
        $request->session()->flash('success', 'Profil Anda Berhasil diupdate !');
        return redirect()->route('employee.profile');
    }
    public function reset_password() {
        return view('auth.reset-password-employee');
    }

    public function update_password(Request $request) {
        $user = Auth::user();
        if(!Hash::check($request->old_password, auth()->user()->password)){
            $request->session()->flash('error','Password Lama Salah');
            return redirect()->back();
        } else{
            User::where('id',auth()->user()->id)->update([
                'password' => Hash::make($request->password)
            ]);
            $request->session()->flash('success','Password Berhasil Dirubah');
            return redirect()->back();
        }
    }
}