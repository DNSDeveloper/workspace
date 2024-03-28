<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReimbursementExport;
use App\Exports\SheetReimbursement;
use App\Exports\TesExport;
use App\Http\Controllers\Controller;
use App\Reimbursement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Image;
use Maatwebsite\Excel\Facades\Excel;

class ReimbursementController extends Controller
{
    public function index()
    {
        $reimbursements = Reimbursement::orderBy('created_at', 'DESC')->get();
        return view('admin.reimbursements.index', compact('reimbursements'));
    }

    public function update(Request $request, $id)
    {
        $reimbursements = Reimbursement::where('id', $id)->first();
        if ($request->status == 'pending') {
            $data = [
                "catetan" => $request->catetan
            ];
        } else {
            $this->validate($request, [
                'file' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg,pdf|max:2048',
            ]);
            $image = $request->file('file');
            if ($image != null) {
                $input['file'] = $image == null ? null : time() . '.' . $image->getClientOriginalExtension();

                $destinationPath = public_path('/reimbursement');
                $imgFile = \Image::make($image->getRealPath());
                $imgFile->resize(150, 150, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['file']);
                $image->move($destinationPath, $input['file']);
            } else {
                $input['file'] = null;
            }

            $data = [
                "file_admin" => $input['file'],
                "tanggal_transfer" => $request->tgl_transfer
            ];
        }
        $data['status'] = $request->status;
        $reimbursements->update($data);

        return redirect()->back()->with('success', 'Berhasil Mengupdate Reimburserment');
    }

    public function export_excel(Request $request)
    {
        $bulan = Carbon::now()->isoFormat('MMMM');
        $minggu = $request->minggu;
        return Excel::download(new SheetReimbursement($minggu), "Report Reimbursement Minggu ke-$request->minggu bulan $bulan.xlsx");
    }
}
