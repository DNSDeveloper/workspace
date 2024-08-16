<?php

namespace App\Http\Controllers\Employee;

use App\Event;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index(Request $request)
    {
        return view('employee.event.index');
    }

    public function renderEvent()
    {
        $data = Event::get();
        foreach ($data as $d) {
            $events[] = [
                'id'   => $d->id,
                'title' => $d->title,
                'start' => Carbon::parse($d->start_date)->addDay(),
                'end' => Carbon::parse($d->end_date)->addDay(),
                'allDay' => true,
                'color' => $d->color ?? '#3788d8'   
            ];
        }
        return response()->json($events);
    }


    public function ajax(Request $request)
    {

        switch ($request->type) {
            case 'add':
                $event = Event::create([
                    'title' => $request->title,
                    'start_date' => $request->start_date,
                    'end_date' => $request->start_date,
                    'color' => $request->color,
                ]);

                return response()->json($event);
                break;

            case 'update':
                $event = Event::find($request->id);

                if ($event) {
                    $event->update([
                        'title' => $request->title,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                    ]);
                    Log::info($event);
                    return response()->json($event); // Mengembalikan model yang diperbarui
                } else {
                    return response()->json(['error' => 'Event not found'], 404); // Handle jika event tidak ditemukan
                }
                break;


            case 'delete':
                $event = Event::find($request->id)->delete();

                return response()->json($event);
                break;

            default:
                break;
        }
    }
}
