<?php

namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;

use App\Attendance;
use App\DailyReport;
use App\Holiday;
use App\Rules\DateRange;
use App\Subtask;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Location;
use Intervention\Image\Facades\Image;
class AttendanceController extends Controller
{
    public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
    }
    public function location(Request $request) {
        
        $response = Http::get('https://nominatim.openstreetmap.org/reverse?format=geojson&lat='.$request->lat.'&lon='.$request->lon);
        // dd();
        // $result = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $request->lat . ',' . $request->lon . '&key=AIzaSyC_spXZlR87VF9qq073nAhFGZ-f3K6enqk';
        // $file_contents = file_get_contents($result);

        // $json_decode = json_decode($file_contents);
        // echo  $json_decode->results[0]->formatted_address;
        // $response = array(
        //     'status' => 'success',
        //     'result' => $json_decode
        // );
        return $response->json()['features'][0]['properties']['display_name'];
    }

    // Opens view for attendance register form
    public function create() {
        $employee = Auth::user()->employee;
        $tasks = Task::where('employee_id',$employee->id)
        ->whereIn('status',['on progress','done','open'])
        ->where('is_approved',1)
        ->where(function ($query) {
            $query->whereNull('completed_time')
                ->orWhereDate('completed_time', '=', date('Y-m-d'));
        })
        ->get();

        $subtasks = Subtask::where('employee_id',$employee->id)
        ->whereIn('status',['on progress','done','open'])
        ->where(function ($query) {
            $query->whereNull('completed_time')
                ->orWhereDate('completed_time', '=', date('Y-m-d'));
        })
        ->whereHas('task',function($q) {
            $q->where('is_approved',1);
        })
        ->get();
        $data = [
            'employee' => $employee,
            'attendance' => null,
            'registered_attendance' => null,
            'tasks'=> $tasks,
            'subtasks'=> $subtasks
        ];
        // $last_attendance = $employee->attendance;
        $last_attendance = Attendance::where('employee_id',auth()->user()->employee->id)
        ->whereDate('created_at',Carbon::now())
        ->first();
        if($last_attendance) {
            if($last_attendance->created_at->format('d') == Carbon::now()->format('d')){
                $data['attendance'] = $last_attendance;
                if($last_attendance->registered)
                    $data['registered_attendance'] = 'yes';
            }
        }
        return view('employee.attendance.create')->with($data);   
    }
    
    public function store(Request $request, $employee_id) {
    $lat1 = deg2rad('-6.191374');
    $long1 = deg2rad('106.836418');
    $lat2 = deg2rad("$request->lat");
    $long2 = deg2rad("$request->long");
    
       //Haversine Formula
       $radius = 6371000;
       // Selisih latitud dan longitud
       $dlat = $lat2 - $lat1;
       $dlon = $long2 - $long1;
   
       // Haversine formula
       $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
       $c = 2 * atan2(sqrt($a), sqrt(1-$a));
   
       // Jarak dalam meter
       $distance = $radius * $c;
       $km = $distance/1000;
    //    dd($distance,$km);

    // dd($km);
       if($km > 20) {
        $request->session()->flash('error','Kamu diluar Kantor, ke Kantor Sekarang!😡🤬');
        return redirect()->back();
       }else {
        $img = $request->image;
        $folderPath = "img_present/";
        
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';
        
        $file = $folderPath . $fileName;
        file_put_contents(public_path().'/img_present/'.$fileName,$image_base64);

        $selectedSeats = Attendance::whereDate('created_at',Carbon::now())
        ->pluck('no_kursi')
        ->toArray(); 

        $kel1 = [1,2,3,4,5];
        $kel2 = [6,7];
        $kel3 = [9,10];

        $except = [1,2,8];
        $no_kursi = '';
        if(empty($selectedSeats)) {
            $availableSeats = array_diff(range(1,10), $except, $selectedSeats);
            $randomSeat = array_rand($availableSeats);
            if($employee_id == "6") {
                $no_kursi = 1;
            } else if ($employee_id == "5") {
                $no_kursi = 2;   
            }else {
                $no_kursi = $availableSeats[$randomSeat];
            }
            Log::info('kondisi 1 '. $no_kursi);
        } else {
            if(in_array($selectedSeats[0],$kel1)) {
                if(end($selectedSeats)>= 9) {
                    $availableSeats = array_diff($kel2, $selectedSeats);
                } else if((end($selectedSeats) == 6 ) || (end($selectedSeats) == 7) ) {
                    $availableSeats = array_diff($kel1, $except, $selectedSeats);
                } else{
                    $availableSeats = array_diff($kel3, $selectedSeats);
                };
            } else if(in_array($selectedSeats[0],$kel2)) {
                if(end($selectedSeats)>= 9) {
                    $availableSeats = array_diff($kel2, $selectedSeats);
                } else if((end($selectedSeats) >= 3) && (end($selectedSeats) <= 5) ) {
                    $availableSeats = array_diff($kel3, $selectedSeats);
                } else{
                    $availableSeats = array_diff($kel1, $except,$selectedSeats);
                };
            }  else if(in_array($selectedSeats[0],$kel3)) {
                if((end($selectedSeats) == 6) || (end($selectedSeats) == 7)) {
                    $availableSeats = array_diff($kel3, $selectedSeats);
                } else if((end($selectedSeats) >= 3) && (end($selectedSeats) <= 5) ) {
                    $availableSeats = array_diff($kel2, $selectedSeats);
                } else{
                    $availableSeats = array_diff($kel1, $except, $selectedSeats);
                };
            } 
            if(empty($availableSeats)) {
                $availableSeats = array_diff(range(1,10),$except, $selectedSeats);
                $randomSeat = array_rand($availableSeats);
                if($employee_id == "6") {
                    $no_kursi = 1;
                } else if ($employee_id == "5") {
                    $no_kursi = 2;   
                } else {
                    $no_kursi = $availableSeats[$randomSeat];
                }
                Log::info('kondisi 2 '. $no_kursi);
            }
            $randomSeat = array_rand($availableSeats);
            if($employee_id == "6") {
                $no_kursi = 1;
            } else if ($employee_id == "5") {
                $no_kursi = 2;   
            }else {
                $no_kursi = $availableSeats[$randomSeat];
            }
            Log::info('kondisi 3 '. $no_kursi);
        }
        
        $attendance = Attendance::create([
            'employee_id' => $employee_id,
            'entry_ip' => $request->ip(),
            'time' => date('h'),
            'entry_location' => $request->entry_location,
            'jam_masuk'=> date('H:i'),
            'img_present'=> $file,
            'no_kursi'=> $no_kursi,
            'status'=>date('H:i') > '09:00' ? 'terlambat' : 'hadir'
        ]);
        
        if(date('H:i')<='09:30') {
         $request->session()->flash('success', "Keren! Kamu Datang Tepat Waktu 🤩, Silahkan duduk di kursi $attendance->no_kursi");
         } else {
            $request->session()->flash('success', "Opps Kamu Terlambat 🥲, Silahkan duduk di kursi $attendance->no_kursi");
         }
        return redirect()->route('employee.attendance.create')->with('employee', Auth::user()->employee)->with('masuk','Selamat Bekerja');
    }
       }

    

    public function update(Request $request, $attendance_id) {
        $reports = new DailyReport();
        $reports->report = $request->report;
        $reports->employee_id = $request->employee_id;
        
        $tasks = json_encode($request->task);
        $reports->task = $tasks;
        $reports->save();
        
        $attendance = Attendance::findOrFail($attendance_id);
        $attendance->exit_ip = $request->ip();
        $attendance->exit_location = $request->exit_location;
        $attendance->registered = 'yes';
        $attendance->jam_pulang = date('H:i');
        $attendance->save();
        $request->session()->flash('success', 'Absensi Anda berhasil diakhiri');
        return redirect()->route('employee.attendance.create')->with('employee', Auth::user()->employee)->with('pulang','Selamat Jalan');
    }

    public function getUserIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        return $ip;
    }

    public function index() {
        $employee = Auth::user()->employee;
        $attendances = $employee->attendance;
        $filter = false;
        if(request()->all()) {
            $this->validate(request(), ['date_range' => new DateRange]);
            if($attendances) {
                [$start, $end] = explode(' - ', request()->input('date_range'));
                $start = Carbon::parse($start);
                $end = Carbon::parse($end)->addDay();
                $filtered_attendances = $this->attendanceOfRange($attendances, $start, $end);
                $leaves = $this->leavesOfRange($employee->leave, $start, $end);
                $holidays = $this->holidaysOfRange(Holiday::all(), $start, $end);
                $attendances = collect();
                $count = $filtered_attendances->count();
                if($count) {
                    $first_day = $filtered_attendances->first()->created_at->dayOfYear;
                    $attendances = $this->get_filtered_attendances($start, $end, $filtered_attendances, $first_day, $count, $leaves, $holidays);
                }
                else{
                    while($start->lessThan($end)) {
                        $attendances->add($this->attendanceIfNotPresent($start, $leaves, $holidays));
                        $start->addDay();
                    }
                }
                $filter = true;
            }   
        }
        if ($attendances)
            $attendances = $attendances->reverse()->values();
        $data = [
            'employee' => $employee,
            'attendances' => $attendances,
            'filter' => $filter
        ];
        return view('employee.attendance.index')->with($data);
    }

    public function get_filtered_attendances($start, $end, $filtered_attendances, $first_day, $count, $leaves, $holidays) {
        $found_start = false;
        $key = 1;
        $attendances = collect();
        while($start->lessThan($end)) {
            if (!$found_start) {
                if($first_day == $start->dayOfYear()) {
                    $found_start = true;
                    $attendances->add($filtered_attendances->first());
                } else {
                    $attendances->add($this->attendanceIfNotPresent($start, $leaves, $holidays));
                }
            } else {
                // iterating over the 2nd to .. n dates
                if ($key < $count) {
                    if($start->dayOfYear() != $filtered_attendances->get($key)->created_at->dayOfYear) {
                        $attendances->add($this->attendanceIfNotPresent($start, $leaves, $holidays));
                    }
                    else {
                        $attendances->add($filtered_attendances->get($key));
                        $key++;
                    }
                }
                else {
                    $attendances->add($this->attendanceIfNotPresent($start, $leaves, $holidays));
                }
            }
            $start->addDay();
        }

        return $attendances;
    }

    public function checkLeave($leaves, $date) {
        if ($leaves->count() != 0) {
            $leaves = $leaves->filter(function($leave, $key) use ($date) {
                // checks if the end date has a value
                if($leave->end_date) {
                    // if it does then checks if the $date falls between the leave range
                    $condition1 = intval($date->dayOfYear) >= intval($leave->start_date->dayOfYear);
                    $condition2 = intval($date->dayOfYear) <= intval($leave->end_date->dayOfYear);
                    return $condition1 && $condition2;
                }
                // else checks if this day is a leave
                return $date->dayOfYear == $leave->start_date->dayOfYear;
            });
        }
        return $leaves->count();
    }

    public function checkHoliday($holidays, $date) {
        if ($holidays->count() != 0) {
            $holidays = $holidays->filter(function($holiday, $key) use ($date) {
                // checks if the end date has a value
                if($holiday->end_date) {
                    // if it does then checks if the $date falls between the holiday range
                    $condition1 = intval($date->dayOfYear) >= intval($holiday->start_date->dayOfYear);
                    $condition2 = intval($date->dayOfYear) <= intval($holiday->end_date->dayOfYear);
                    return $condition1 && $condition2;
                }
                // else checks if this day is a holiday
                return $date->dayOfYear == $holiday->start_date->dayOfYear;
            });
        }
        return $holidays->count();
    }

    public function attendanceIfNotPresent($start, $leaves, $holidays) {
        $attendance = new Attendance();
        $attendance->created_at = $start;
        if($this->checkHoliday($holidays, $start)) {
            $attendance->registered = 'hari libur';
        } elseif($start->dayOfWeek == 0) {
            $attendance->registered = 'minggu';
        } elseif($this->checkLeave($leaves, $start)) {
            $attendance->registered = 'cuti';
        } else {
            $attendance->registered = 'absen';
        }

        return $attendance;
    }

    public function leavesOfRange($leaves, $start, $end) {
        return $leaves->filter(function($leave, $key) use ($start, $end) {
            // checks if the start date is between the range
            $condition1 = (intval($start->dayOfYear) <= intval($leave->start_date->dayOfYear)) && (intval($end->dayOfYear) >= intval($leave->start_date->dayOfYear));
            // checks if the end date is between the range
            $condition2 = false;
            if($leave->end_date)
                $condition2 = (intval($start->dayOfYear) <= intval($leave->end_date->dayOfYear)) && (intval($end->dayOfYear) >= intval($leave->end_date->dayOfYear));
            // checks if the leave status is approved
            $condition3 = $leave->status == 'diterima';
            // combining all the conditions
            return  ($condition1 || $condition2) && $condition3;
        });
    }

    public function attendanceOfRange($attendances, $start, $end) {
        return $attendances->filter(function($attendance, $key) use ($start, $end) {
                    $date = Carbon::parse($attendance->created_at);
                    if ((intval($date->dayOfYear) >= intval($start->dayOfYear)) && (intval($date->dayOfYear) <= intval($end->dayOfYear)))
                        return true;
                })->values();
    }

    public function holidaysOfRange($holidays, $start, $end) {
        return $holidays->filter(function($holiday, $key) use ($start, $end) {
            // checks if the start date is between the range
            $condition1 = (intval($start->dayOfYear) <= intval($holiday->start_date->dayOfYear)) && (intval($end->dayOfYear) >= intval($holiday->start_date->dayOfYear));
            // checks if the end date is between the range
            $condition2 = false;
            if($holiday->end_date)
                $condition2 = (intval($start->dayOfYear) <= intval($holiday->end_date->dayOfYear)) && (intval($end->dayOfYear) >= intval($holiday->end_date->dayOfYear));
            return  ($condition1 || $condition2);
        });
    }

}