<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::get();
        return view('admin.position.index', compact('positions'));
    }

    public function store(Request $request)
    {
        $positions = Position::create([
            'name' => $request->name
        ]);
        return redirect()->back()->with('success', 'Berhasil Menambah Position');
    }

    public function update(Request $request, $id)
    {
        $positions = Position::where('id', $id)->first();
        $positions->update([
            'name' => $request->name
        ]);
        return redirect()->back()->with('success', 'Berhasil Mengupdate Position');
    }
}
