<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Reimbursement;
use Illuminate\Http\Request;
use Image;


class ReimbursementController extends Controller
{
    public function index()
    {
        $reimbursements = Reimbursement::where('employee_id', auth()->user()->employee->id)
            ->orderBy('created_at', 'DESC')
            ->get();
        return view('employee.reimbursements.index', compact('reimbursements'));
    }

    public function store(Request $request)
    {
        $nominal = str_replace('.', '', $request->nominal);
        $nominal = str_replace("Rp ", "", $nominal);
        $this->validate($request, [
            'file' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);
        $image = $request->file('file');
        $input['file'] = time() . '.' . $image->getClientOriginalExtension();

        $destinationPath = public_path('/reimbursement');
        $imgFile = \Image::make($image->getRealPath());
        $imgFile->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $input['file']);
        $image->move($destinationPath, $input['file']);

        $reimbursements = Reimbursement::create([
            'employee_id' => auth()->user()->employee->id,
            'tanggal_reimbursement' => $request->tgl_reimbursement,
            'minggu' => $request->minggu,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'nominal' => $nominal,
            'tanggal_transfer' => $request->tgl_transfer,
            'status' => 'requested',
            'file_employee' => $input['file']
        ]);
        if ($reimbursements) {
            $request->session()->flash('success', 'Berhasil Menambah Reimbursement');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $nominal = str_replace('.', '', $request->nominal);
        $nominal = str_replace("Rp ", "", $nominal);
        $reimbursements = Reimbursement::where('id', $id)->first();
        $reimbursements->update([
            'employee_id' => auth()->user()->employee->id,
            'tanggal_reimbursement' => $request->tgl_reimbursement,
            'minggu' => $request->minggu,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'nominal' => $nominal,
            'tanggal_transfer' => $request->tgl_transfer,
            'status' => 'requested',
        ]);

        $request->session()->flash('success','Berhasil Mengupdate Reimbursement');
        return redirect()->back();
    }
}