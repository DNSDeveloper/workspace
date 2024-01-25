<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service;
use App\Unit;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index() {
        $services = Service::get();
        $units = Unit::get();
        return view('admin.services.index',compact('services','units'));
    }

    public function store(Request $request) {
        $service = Service::create([
            'unit_id'=> $request->unit_id,
            'name'=> $request->name
        ]);
        return redirect()->back()->with('success','Berhasil Menambah Service');
    }

    public function update(Request $request, $id) {
        $service = Service::where('id',$id)->first();
        $service->update([
            'unit_id'=> $request->unit_id,
            'name'=> $request->name
        ]);
        return redirect()->back()->with('success','Berhasil Mengupdate Service');
    }
}