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
    public function kalkulasiUangHarian()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $startDate = Carbon::create($currentYear, $currentMonth, 1)->startOfMonth();
        $endDate = Carbon::create($currentYear, $currentMonth, 1)->endOfMonth();

        $totalWeeks = $startDate->diffInWeeks($endDate) + 1;
        $employees = Employee::whereNotIn('id', [5, 6])->get();

        $result = [];
        foreach ($employees as $employee) {
            $detail = [];
            $totalEarnings = 0;

            for ($week = 1; $week <= $totalWeeks; $week++) {
                // Set startOfWeek pada hari Senin
                $startOfWeek = $startDate->copy()->addWeeks($week - 1)->startOfWeek();
                // Set endOfWeek pada hari Minggu
                $endOfWeek = $startOfWeek->copy()->endOfWeek();

                // Jika endOfWeek lebih dari akhir bulan, atur ke akhir bulan
                if ($endOfWeek->greaterThan($endDate)) {
                    $endOfWeek = $endDate;
                }

                // Jika startOfWeek adalah hari Sabtu, tambahkan 25000 di minggu berikutnya
                if ($startOfWeek->dayOfWeek === Carbon::SATURDAY) {
                    $startOfWeek->addWeek();
                    $endOfWeek->addWeek();

                    // Jika endOfWeek lebih dari akhir bulan, atur ke akhir bulan
                    if ($endOfWeek->greaterThan($endDate)) {
                        $endOfWeek = $endDate;
                    }
                    continue; // Skip minggu ini
                }

                $daysInWeek = $startOfWeek->diffInDays($endOfWeek);

                $attendances = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->get();

                $attendanceByDate = []; // Array untuk menyimpan kehadiran per tanggal dalam seminggu
                $totalEarningsThisWeek = 0;
                $totalAttendanceThisWeek = 0;
                foreach ($attendances as $attendance) {
                    if ($attendance->status == 'hadir' || $attendance->status == 'terlambat') {
                        $totalEarnings += 25000;
                        $totalEarningsThisWeek += 25000;
                        $totalAttendanceThisWeek++;

                        $earningDate = $attendance->created_at->format('d');
                        $attendanceByDate[$earningDate] = 25000;
                    }
                }
                $detail[] = [
                    'week' => $week,
                    'total' => $totalEarningsThisWeek,
                    'attendance_count' => $totalAttendanceThisWeek,
                    'days_in_week' => $daysInWeek,
                    'days' => $attendanceByDate, 

                ];
            }

            $result[] = [
                'nama_employee' => $employee->first_name . ' ' . $employee->last_name,
                'total_permonth' => $totalEarnings,
                'detail' => $detail,
            ];
        }
        return $result;
    }
    public function index()
    {
        $tasks = Task::where('employee_id', Auth::user()->employee->id)
            ->whereIn('status', ['open', 'on progress'])
            ->get();
        $subtask = Subtask::where('employee_id', Auth::user()->employee->id)
            ->whereIn('status', ['open', 'on progress'])
            ->get();
        $attendance = Attendance::where('employee_id', auth()->user()->employee->id)
            ->whereDate('created_at', Carbon::now())
            ->first();

            $availableSeats = array_diff(range(1,10));
            $randomSeat = array_rand($availableSeats);
            
        $uangHarians = $this->kalkulasiUangHarian();
        $data = [
            'employee' => Auth::user()->employee,
            'tasks' => $tasks,
            'attendance' => $attendance,
            'subtask' => $subtask,
            'uangHarians' => $uangHarians
        ];
        return view('employee.index')->with($data);
    }

    public function profile()
    {
        $data = [
            'employee' => Auth::user()->employee
        ];
        return view('employee.profile')->with($data);
    }

    public function profile_edit($employee_id)
    {
        $data = [
            'employee' => Employee::findOrFail($employee_id),
            'departments' => Department::all(),
            'desgs' => ['Manajer', 'Asistent Manajer', 'Projek Manajer', 'Staff']
        ];
        Gate::authorize('employee-profile-access', intval($employee_id));
        return view('employee.profile-edit')->with($data);
    }

    public function profile_update(Request $request, $employee_id)
    {
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
            $filename_store =  strtolower($request->first_name) . '.' . $ext;
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
    public function reset_password()
    {
        return view('auth.reset-password-employee');
    }

    public function update_password(Request $request)
    {
        $user = Auth::user();
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            $request->session()->flash('error', 'Password Lama Salah');
            return redirect()->back();
        } else {
            User::where('id', auth()->user()->id)->update([
                'password' => Hash::make($request->password)
            ]);
            $request->session()->flash('success', 'Password Berhasil Dirubah');
            return redirect()->back();
        }
    }
}