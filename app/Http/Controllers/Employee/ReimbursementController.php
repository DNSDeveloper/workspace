<?php

namespace App\Http\Controllers\Employee;

use App\Exports\ReimbursementExport;
use App\Exports\SheetReimbursement;
use App\Http\Controllers\Controller;
use App\Reimbursement;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Image;
use Maatwebsite\Excel\Facades\Excel;

class ReimbursementController extends Controller
{
    public function thisWeek()
    {
        $specificDate = Carbon::now();
        $startOfMonth = $specificDate->copy()->startOfMonth();
        $firstDayOfMonth = $startOfMonth->dayOfWeek;
        $currentWeek = ceil(($specificDate->day + $firstDayOfMonth) / 7);
        return intval($currentWeek);
    }

    public function index()
    {
        if (auth()->user()->employee->id == 3) {
            $reimbursements = Reimbursement::whereMonth('created_at', date('m'))->orderBy('created_at', 'DESC')
                ->get();
        } else {
            $reimbursements = Reimbursement::where('employee_id', auth()->user()->employee->id)
                ->orderBy('created_at', 'DESC')
                ->get();
        }
        $week = $this->thisWeek();
        return view('employee.reimbursements.index', compact('reimbursements', 'week'));
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

        $tujuan = $request->tujuan;
        if($tujuan == 'Lainnya') {
            $tujuan = $request->tujuan_lainnya;
        }

        $reimbursements = Reimbursement::create([
            'employee_id' => auth()->user()->employee->id,
            'tanggal_reimbursement' => $request->tgl_reimbursement,
            'minggu' => $request->minggu,
            'jenis' => $request->jenis,
            'tujuan'=> $tujuan,
            'tipe'=> $request->tipe,
            'deskripsi' => $request->deskripsi,
            'nominal' => $nominal,
            'status' => 'requested',
            'file_employee' => $input['file']
        ]);
        // try {
        //     $header = [
        //         'Content-Type' => 'application/json',
        //     ];
        //     $data = [
        //         "name" => auth()->user()->employee->first_name . ' ' . auth()->user()->employee->last_name,
        //         "deskripsi" => preg_replace("/\r|\n/", "", $request->deskripsi),
        //         "nominal" => $nominal,
        //         'code' => "R001"
        //     ];
        //     $client = new Client();
        //     $url = env("API_URL") . 'call-service/' . '6281387297959';
        //     $storeToBot = $client->post($url, [
        //         "headers" => $header,
        //         "json" => $data
        //     ]);
        //     return redirect()->back()->with('success', 'Berhasil Menambah Reimbursement');
        // } catch (\Throwable $th) {
        //     return redirect()->back()->with('success', 'Berhasil Menambah Reimbursement, Bot Notifikasi tidak Terkirim');
        // }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $nominal = str_replace('.', '', $request->nominal);
        $nominal = str_replace("Rp ", "", $nominal);
        $reimbursements = Reimbursement::where('id', $id)->first();

        $tujuan = $request->tujuan;
        if($tujuan == 'Lainnya') {
            $tujuan = $request->tujuan_lainnya;
        }
        $reimbursements->update([
            'employee_id' => auth()->user()->employee->id,
            'tanggal_reimbursement' => $request->tgl_reimbursement,
            'minggu' => $request->minggu,
            'jenis' => $request->jenis,
            'tujuan'=> $tujuan,
            'tipe'=> $request->tipe,
            'deskripsi' => $request->deskripsi,
            'nominal' => $nominal,
            'status' => 'requested',
        ]);

        $request->session()->flash('success', 'Berhasil Mengupdate Reimbursement');
        return redirect()->back();
    }
    public function export_excel(Request $request)
    {
        $bulan = Carbon::now()->isoFormat('MMMM');
        $minggu = $request->minggu;
        return Excel::download(new SheetReimbursement($minggu), "Report Reimbursement Minggu ke-$request->minggu bulan $bulan.xlsx");
    }

    public function destroy($id)
    {
        $reimbursement = Reimbursement::find($id);
        if ($reimbursement->file_employee) {
            $imagePath = public_path($reimbursement->file_employee);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $reimbursement->delete();
        return response()->json(['success' => 'Berhasil Menghapus Reimbursement']);
    }
}