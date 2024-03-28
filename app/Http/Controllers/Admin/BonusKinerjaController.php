<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\Employee;
use App\Holiday;
use App\Http\Controllers\Controller;
use App\Subtask;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonusKinerjaController extends Controller
{
    public static function calculateWorkingDays()
    {
        $holidays = Holiday::whereMonth('created_at', date('m'))->get();
        $totalWorkingDays = 0;
        foreach ($holidays as $holiday) {
            $startDate = Carbon::parse($holiday->start_date);
            $endDate = $holiday->end_date ? Carbon::parse($holiday->end_date) : null;
            if ($endDate !== null) {
                $totalWorkingDays += $startDate->diffInWeekdays($endDate);
            } else {
                $totalWorkingDays += 1;
            }
        }
        return $totalWorkingDays;
    }
    public function jumlahHariKerja()
    {
        $bulan = date('m');
        $tahun = date('Y');
        $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1);
        $tanggalAkhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
        $jumlahHariKerja = 0;
        for ($tanggal = $tanggalAwal; $tanggal->lte($tanggalAkhir); $tanggal->addDay()) {
            if ($tanggal->isWeekday()) {
                $jumlahHariKerja++;
            }
        }
        $holiday = $this->calculateWorkingDays();
        $total = $jumlahHariKerja - $holiday;
        return $total;
    }

    public function attendance()
    {
        $attendances = Employee::select('id', 'first_name', 'last_name')->withCount([
            'attendance as attendance_hadir' => function ($query) {
                $query->where('status', 'hadir');
            },
            'attendance as attendance_terlambat' => function ($query) {
                $query->where('status', 'terlambat');
            },
            'attendance as attendance_cuti' => function ($query) {
                $query->whereIn('status', ['cuti', 'sakit']);
            },
            'tasks as task_tepat_waktu' => function ($query) {
                $query->select(DB::raw('COUNT(*)'))
                    ->from('tasks')
                    ->whereColumn('employee_id', 'employees.id')
                    ->whereColumn('completed_time', '<', 'deadline');
            },
            'tasks as task_terlambat' => function ($query) {
                $query->select(DB::raw('COUNT(*)'))
                    ->from('tasks')
                    ->whereMonth('created_at', date('m'))
                    ->whereColumn('employee_id', 'employees.id')
                    ->whereColumn('completed_time', '>', 'deadline');
            }, 'tasks' => function ($query) {
                $query->whereNotIn('status', ['cancel']);
            }
        ])->whereHas('attendance', function ($q) {
            $q->whereMonth('created_at', date('m'));
        })->orderBy('attendance_hadir', 'DESC')
            ->get();
        return $attendances;
    }
    public function index()
    {
        $jumlahHariKerja = $this->jumlahHariKerja();
        $attendances = $this->attendance();
        $attendances->transform(function ($item) use ($jumlahHariKerja) {
            $subtasks = Subtask::where('employee_id', $item->id)
                ->whereMonth('created_at', date('m'))
                ->get();
            $totTepatWaktuSubtask = Subtask::where('employee_id', $item->id)
                ->whereMonth('created_at', date('m'))
                ->where(function ($q) {
                    $q->whereNotNull('completed_time')
                        ->whereColumn('completed_time', '<', 'deadline');
                })
                ->get();
            $totTerlambatSubtask = Subtask::where('employee_id', $item->id)
                ->whereMonth('created_at', date('m'))
                ->where(function ($q) {
                    $q->whereNotNull('completed_time')
                        ->whereColumn('completed_time', '>', 'deadline');
                })
                ->get();
            $item->subtask_tepat_waktu = $totTepatWaktuSubtask->count();
            $item->subtask_terlambat = $totTerlambatSubtask->count();
            $item->subtasks = $subtasks->count();
            $item->persentase_hadir = round(($item->attendance_hadir / $jumlahHariKerja) * 100);
            if ($item->tasks_count == 0 && $item->subtasks) {
                $item->persentase_task =  0;
            } else {
                $total = $subtasks->count() + $item->tasks_count;
                $totTepatWaktu = $item->subtask_tepat_waktu + $item->task_tepat_waktu;
                $totTerlambat = $item->task_terlambat + $item->subtask_terlambat;
                $totalTask = round((($totTepatWaktu / $total * 110) + ($totTerlambat / $total * 80)));
                $item->tot_tepat_waktu = $totTepatWaktu;
                $item->tot_terlambat = $totTerlambat;
                $item->persentase_task =  $totalTask / 2;
            }
            return $item;
        });
        return view('admin.bkinerja.index', compact('attendances', 'jumlahHariKerja'));
    }

    public function detail($id)
    {
        $attendances = Attendance::where('employee_id', $id)
            ->whereMonth('created_at', date('m'))
            ->get();

        $tasks = Task::where('employee_id', $id)
            ->whereIn('status', ['done'])
            ->whereMonth('created_at', date('m'))
            ->get();

        $subtasks = Subtask::with('task')->where('employee_id', $id)
            ->whereIn('status', ['done'])
            ->whereMonth('created_at', date('m'))
            ->get();
        return view('admin.bkinerja.detail', compact('attendances', 'tasks', 'subtasks'));
    }
}
