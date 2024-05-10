<?php

namespace App\Http\Controllers\Employee;

use App\Attendance;
use App\Employee;
use App\Http\Controllers\Controller;
use App\Leave;
use App\Rules\DateRange;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Image;

class LeaveController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;
        $data = [
            'employee' => $employee,
            'leaves' => $employee->leave,
        ];
        return view('employee.leaves.index')->with($data);
    }
    public function create()
    {
        $employee = Auth::user()->employee;
        $countLeave = Leave::where('employee_id', $employee)->count();
        $data = [
            'employee' => $employee,
            'count' => 12 - $countLeave
        ];

        return view('employee.leaves.create')->with($data);
    }

    public function store(Request $request, $employee_id)
    {
        $red = route('employee.leaves.create');
        $date = date('Y-m-d', strtotime($request->input('date')));

        [$start, $end] = explode(' - ', $request->input('date_range'));
        $start = date('Y-m-d', strtotime(Carbon::parse($start)));
        $end = date('Y-m-d', strtotime(Carbon::parse($end)));
        $today = date('Y-m-d');

        if ($request->reason == 'cuti' && $request->input('multiple-days') == 'yes') {
            if ($start <= $today || $end <= $start) {
                $request->session()->flash('error', 'Pengajuan Cuti hanya bisa dilakukan maksimal H-1 dari tanggal ketidakhadiran');
                return redirect()->back();
            }
        } elseif ($request->reason == 'cuti') {
            if ($date <= $today) {
                $request->session()->flash('error', 'Pengajuan Cuti hanya bisa dilakukan maksimal H-1 dari tanggal ketidakhadiran');
                return redirect()->back();
            }
        }
        $data = [
            'employee' => Auth::user()->employee
        ];
        if ($request->input('multiple-days') == 'yes') {
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

        $hakCuti = Employee::where('user_id', auth()->user()->id)->first();

        if ($request->input('multiple-days') == 'yes') {
            [$start, $end] = explode(' - ', $request->input('date_range'));
            $values['start_date'] = Carbon::parse($start);
            $values['end_date'] = Carbon::parse($end);
            $period = CarbonPeriod::create($values['start_date'], '1 day', $values['end_date']);
            $count = 0;
            foreach ($period as $date) {
                if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                    $count += 1;
                }
            }
            if ($period->count() >= $hakCuti->hak_cuti) {
                $request->session()->flash('error', "Waktu cuti anda hanya tersisa " . ($hakCuti->hak_cuti));
                return redirect()->back();
            }
            $hakCuti->update([
                'hak_cuti' => $hakCuti->hak_cuti - $count
            ]);
        } else {
            $values['start_date'] = Carbon::parse($request->input('date'));
            $hakCuti->update([
                'hak_cuti' => $hakCuti->hak_cuti - 1
            ]);
        }
        if ($hakCuti->hak_cuti < 1) {
            return redirect()->back()->with('error', 'Hak Cuti Anda sudah Habis');
        } else {
            Leave::create($values);
        }
        try {
            $client = new Client();
            $headers = ["Content-Type" => "application/json"];
            $dataBot = [
                "name" => auth()->user()->employee->first_name . ' ' . auth()->user()->employee->last_name,
                "reason" => ucfirst($request->reason) . ' - ' . $request->description,
                "code" => "C001"
            ];
            $url = env("API_URL") . 'call-service/' . "6281387297959";
            $client->post($url, [
                "headers" => $headers,
                "json" => $dataBot
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('employee.leaves.create')->with($data)->with('success', 'Pengajuan Cuti Anda berhasil, tunggu persetujuan atasan. Bot Notifikasi tidak Terkirim');
        }
        return redirect()->route('employee.leaves.create')->with($data)->with('success', 'Pengajuan Cuti Anda berhasil, tunggu persetujuan atasan.');
    }

    public function edit($leave_id)
    {
        $leave = Leave::findOrFail($leave_id);
        // Gate::authorize('employee-leaves-access', $leave);
        return view('employee.leaves.edit')->with('leave', $leave);
    }

    public function update(Request $request, $leave_id)
    {
        $leave = Leave::findOrFail($leave_id);
        // Gate::authorize('employee-leaves-access', $leave);
        if ($request->input('multiple-days') == 'yes') {
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
        if ($request->input('multiple-days') == 'yes') {
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $input['file'] = time() . '.' . $image->getClientOriginalExtension();

                $destinationPath = public_path('/cuti');
                $imgFile = \Image::make($image->getRealPath());
                $imgFile->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);
                $image->move($destinationPath, $input['file']);
                $leave->file = $input['file'];
            }

            [$start, $end] = explode(' - ', $request->input('date_range'));
            $start = Carbon::parse($start);
            $end = Carbon::parse($end);
            $leave->start_date = $start;
            $leave->end_date = $end;
        } else {
            $leave->start_date = Carbon::parse($request->input('date'));
        }

        $leave->save();
        return redirect()->route('employee.leaves.index')->with('success', 'Update Pengajuan Cuti Anda berhasil');
    }

    public function destroy($leave_id)
    {
        $leave = Leave::findOrFail($leave_id);
        // Gate::authorize('employee-leaves-access', $leave);
        $leave->delete();
        request()->session()->flash('success', 'Pengajuan Cuti Anda berhasil dihapus');

        return redirect()->route('employee.leaves.index');
    }
}