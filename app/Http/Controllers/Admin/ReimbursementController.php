<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Reimbursement;
use Illuminate\Http\Request;
use Image;

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
            $input['file'] = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('/reimbursement');
            $imgFile = \Image::make($image->getRealPath());
            $imgFile->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $input['file']);
            $image->move($destinationPath, $input['file']);
            $data = [
                "file_admin" => $input['file']
            ];
        }
        $data['status'] = $request->status;
        $reimbursements->update($data);

        return redirect()->back();
    }
}
