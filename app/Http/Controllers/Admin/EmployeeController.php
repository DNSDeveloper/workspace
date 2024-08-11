<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\Department;
use App\Employee;
use App\Http\Controllers\Controller;
use App\Position;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;

use function Ramsey\Uuid\v1;

class EmployeeController extends Controller
{
    public function index() {
        $data = [
            'employees' => Employee::all()
        ];
        return view('admin.employees.index')->with($data);
    }
    public function create() {
        $data = [
            'departments' => Department::all(),
            'positions' => Position::get()
        ];
        return view('admin.employees.create')->with($data);
    }

    public function update_attendance(Request $request,$id) {
        $attend = Attendance::where('id',$id)->first();
        $attend->update([
            'keterangan'=> $request->keterangan
        ]);
        return redirect()->back()->with('success','Berhasil Merubah Absen');

    }

    public function store(Request $request) {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'sex' => 'required',
            'position_id' => 'required',
            'department_id' => 'required',
            'salary' => 'required|numeric',
            'email' => 'required|email',
            'photo' => 'image|nullable',
            'password' => 'required|confirmed|min:6'
        ]);
        
        $user = new User();
        $user->name = $request->first_name.' '.$request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $getLastUsername = User::orderBy('created_at','DESC')->pluck('username')->first();
        $lastXYZ = substr($getLastUsername,0,2);
        $trimUsername = strtoupper(substr($request->first_name,0,3));
        
        switch ($lastXYZ) {
            case 'XY':
                $user->username = 'YZ'. $trimUsername;
                break;
            case 'YZ':
                $user->username = 'ZX'. $trimUsername;
                break;
            case 'ZX':
                $user->username = 'XY'. $trimUsername;
                break;
        }
        $user->save();
                
        $employeeRole = Role::where('name', 'employee')->first();
        $user->roles()->attach($employeeRole);
        $employeeDetails = [
            'user_id' => $user->id, 
            'first_name' => $request->first_name, 
            'last_name' => $request->last_name,
            'sex' => $request->sex, 
            'dob' => $request->dob, 
            'join_date' => $request->join_date,
            'position_id' => $request->position_id, 
            'department_id' => $request->department_id, 
            'salary' => $request->salary, 
            'photo'  => 'user.png'
        ];
        // Photo upload
        if ($request->hasFile('photo')) {
            // GET FILENAME
            $filename_ext = $request->file('photo')->getClientOriginalName();
            // GET FILENAME WITHOUT EXTENSION
            $filename = pathinfo($filename_ext, PATHINFO_FILENAME);
            // GET EXTENSION
            $ext = $request->file('photo')->getClientOriginalExtension();
            //FILNAME TO STORE
            $filename_store = $filename.'_'.time().'.'.$ext;
            // UPLOAD IMAGE
            $path = $request->file('photo')->storeAs('public'.DIRECTORY_SEPARATOR.'employee_photos', $filename_store);
            // add new file name
            $image = $request->file('photo');
            $image_resize = Image::make($image->getRealPath());              
            $image_resize->resize(300, 300);
            // $image_resize->save(public_path(DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'employee_photos'.DIRECTORY_SEPARATOR.$filename_store));
            $employeeDetails['photo'] = $filename_store;
        }
        
        $tes = Employee::create($employeeDetails);
        
        $request->session()->flash('success', 'Karyawan berhasil ditambahkan!');
        return back();
    }

    public function attendance(Request $request) {
        $data = [
            'date' => null
        ];
        if($request->all()) {
            $date = Carbon::create($request->date);
            $employees = $this->attendanceByDate($date);
            $data['date'] = $date->format('d M, Y');
        } else {
            $employees = $this->attendanceByDate(Carbon::now());
        }
        $data['employees'] = $employees;
        // dd($employees->get(4)->attendanceToday->id);
        return view('admin.employees.attendance')->with($data);
    }

    public function attendanceByDate($date) {
        $employees = DB::table('employees')->select('id', 'first_name', 'last_name', 'position_id', 'department_id')->get();
        $attendances = Attendance::all()->filter(function($attendance, $key) use ($date){
            return $attendance->created_at->dayOfYear == $date->dayOfYear;
        });
        return $employees->map(function($employee, $key) use($attendances) {
            $attendance = $attendances->where('employee_id', $employee->id)->first();
            $employee->attendanceToday = $attendance;
            $employee->department = Department::find($employee->department_id)->name;
            return $employee;
        });
    }

    public function destroy($employee_id) {
        $employee = Employee::findOrFail($employee_id);
        $user = User::findOrFail($employee->user_id);
        // detaches all the roles
        DB::table('leaves')->where('employee_id', '=', $employee_id)->delete();
        DB::table('attendances')->where('employee_id', '=', $employee_id)->delete();
        DB::table('expenses')->where('employee_id', '=', $employee_id)->delete();
        $employee->delete();
        $user->roles()->detach();
        // deletes the users
        $user->delete();
        request()->session()->flash('success', 'Karyawan berhasil dihapus!');
        return back();
    }

    public function attendanceDelete($attendance_id) {
        $attendance = Attendance::findOrFail($attendance_id);
        $attendance->delete();
        request()->session()->flash('success', 'Riwayat Absensi berhasil dihapus!');
        return back();
    }

    public function employeeProfile($employee_id) {
        $employee = Employee::findOrFail($employee_id);
        return view('admin.employees.profile')->with('employee', $employee);
    }

    public function toogleWfo(Request $request,$employee_id) { 
        $employee = Employee::find($employee_id);
        $employee->update([
            'is_wfo' => $request->is_wfo
        ]);
        return response()->json([
           'success' => true
        ]);
    }
}