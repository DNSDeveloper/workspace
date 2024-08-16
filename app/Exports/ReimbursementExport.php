<?php

namespace App\Exports;

use App\Reimbursement;
use App\Attendance;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;

class ReimbursementExport implements FromQuery, WithCustomStartCell, WithEvents, WithHeadings, WithMapping, WithTitle
{
    use Exportable;

    private $minggu;
    private $employee;
    public function __construct($minggu, $employee, $bulan, $tahun)
    {
        $this->minggu = $minggu;
        $this->employee = $employee;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function title(): string
    {
        return $this->employee;
    }

    public function query()
    {
        $minggu = $this->minggu;
        $employee = $this->employee;
        $bulan = $this->bulan;
        $tahun = $this->tahun;

        $reimbursements = Reimbursement::query()
            ->where('minggu', $this->minggu)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->whereHas('employee', function ($q) use ($employee) {
                $q->where('first_name', $employee);
            })->orderBy('jenis', 'ASC')->orderBy('tanggal_reimbursement');
        return $reimbursements;
    }
    public function startCell(): string
    {
        return 'A4';
    }
    public function headings(): array
    {
        return  [
            'Tgl Reimbursement',
            'Jenis',
            'Deskripsi',
            'Nominal',
            'Tgl Transfer',
            'Status',
            'Catetan',
            'Bukti Pembayaran',
        ];
    }
    public function map($reimbursement): array
    {
        return [
            $reimbursement->tanggal_reimbursement == null ? '' : date('d M Y', strtotime($reimbursement->tanggal_reimbursement)),
            ucfirst($reimbursement->jenis),
            ucfirst($reimbursement->deskripsi),
            $reimbursement->nominal == null ? '' : $reimbursement->nominal,
            $reimbursement->tanggal_transfer == null ? '' : date('d M Y', strtotime($reimbursement->tanggal_transfer)),
            ucfirst($reimbursement->status),
            ucfirst($reimbursement->catetan),
            'https://workspace.dnstech.co.id/reimbursement/' . $reimbursement->file_employee,
        ];
    }


    public function registerEvents(): array
    {
        $minggu = $this->minggu;
        $employee = $this->employee;
        $bulan = $this->bulan;
        $tahun = $this->tahun;

        $startDate = Carbon::now()->startOfMonth()->addWeeks($minggu - 1)->startOfWeek();
        $endDate = Carbon::now()->startOfMonth()->addWeeks($minggu - 1)->endOfWeek();

        if ($endDate->month != Carbon::now()->month) {
            $endDate = Carbon::now()->endOfMonth();
        }

        $reimbursementsCount = Reimbursement::with('employee')->where('minggu', $this->minggu)
            ->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)
            ->whereHas('employee', function ($q) use ($employee) {
                $q->where('first_name', $employee);
            })->get();

        $dailyEarnings = Attendance::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('employee_id', [1, 2, 3, 4])
            ->whereHas('employee', function ($q) use ($employee) {
                $q->where('first_name', $employee);
            })->get();

        $count = 6 + $reimbursementsCount->count();
        $countAfterReimburs = $count + 1;
        $bulan = Carbon::createFromFormat('m', $bulan)->isoFormat('MMMM');
        $totalDailyEarnings = $dailyEarnings->sum(function ($attendance) {
            return $attendance->status == 'terlambat' ? 12500 : ($attendance->status == 'hadir' ? 25000 : 0);
        });
        return [
            BeforeSheet::class => function (BeforeSheet $event) use ($minggu, $bulan, $employee) {
                $event->sheet->getDelegate()->mergeCells('A1:H1');
                $event->sheet->getDelegate()->mergeCells('A2:H2');
                $event->sheet->getDelegate()->setCellValue('A1', "Rekap Reimbursement Bulan $bulan")
                    ->getStyle('A1')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(20);
                $event->sheet->getDelegate()->setCellValue('A2', "Minggu Ke-$minggu")
                    ->getStyle('A2')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(16);
                $event->sheet->getDelegate()->setCellValue('A3', "Nama Karyawan : $employee")
                    ->getStyle('A3')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(12);

                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle('A3:H3')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->mergeCells('A3:H3');

                $event->sheet->getDelegate()->getStyle('A3')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('3d98d5');
                $event->sheet->getDelegate()->getStyle('A3')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(12)
                    ->getColor()
                    ->setARGB('ffffff');
            },
            AfterSheet::class => function (AfterSheet $event) use ($count, $reimbursementsCount, $countAfterReimburs, $totalDailyEarnings) {
                $newCount = $countAfterReimburs + 1;
                if ($reimbursementsCount->count() > 0) {
                    $event->sheet->getDelegate()->setCellValue("D$count", $reimbursementsCount->sum('nominal'))
                        ->getStyle("D$count")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);

                    $event->sheet->getDelegate()->setCellValue("A$count", "Total Reimbursement")
                        ->getStyle("A$count")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12)
                        ->setItalic(true);

                    $event->sheet->getDelegate()->mergeCells("A$count:C$count");
                    $event->sheet->getDelegate()->getStyle("A$count:C$count")
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                }

                $event->sheet->getDelegate()->setCellValue("A{$countAfterReimburs}", "Total Uang Harian")
                    ->getStyle("A{$countAfterReimburs}")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(12)
                    ->setItalic(true);

                $event->sheet->getDelegate()->setCellValue("D$countAfterReimburs", $totalDailyEarnings)
                    ->getStyle("D$countAfterReimburs")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(12);

                $event->sheet->getDelegate()->mergeCells("A{$countAfterReimburs}:C{$countAfterReimburs}");
                $event->sheet->getDelegate()->getStyle("A{$countAfterReimburs}:C{$countAfterReimburs}")
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


                $event->sheet->getDelegate()->setCellValue("A{$newCount}", "Total")
                    ->getStyle("A{$newCount}")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(12)
                    ->setItalic(true);

                $event->sheet->getDelegate()->setCellValue("D$newCount", $totalDailyEarnings + $reimbursementsCount->sum('nominal'))
                    ->getStyle("D$newCount")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(12);

                $event->sheet->getDelegate()->mergeCells("A{$newCount}:C{$newCount}");
                $event->sheet->getDelegate()->getStyle("A{$newCount}:C{$newCount}")
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle('A4')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('B4')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('C4')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('D4')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('E4')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('F4')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('G4')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('H4')->applyFromArray($styleArray);

                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension('3')->setRowHeight(22);
                $event->sheet->getDelegate()->getRowDimension('4')->setRowHeight(22);

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(55);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(45);

                $event->sheet->getDelegate()->getStyle('A4:H4')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(12)
                    ->getColor()
                    ->setARGB('ffffff');
                $event->sheet->getDelegate()->getStyle('A4:H4')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('3d98d5');
                $event->sheet->getDelegate()
                    ->getStyle('A:H')
                    ->getAlignment()
                    ->setWrapText(true);
                $event->sheet->getDelegate()
                    ->getStyle('A:H')
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                $event->sheet->getDelegate()
                    ->getStyle('D')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()
                    ->getStyle('A1:A2')
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()
                    ->getStyle('A4:H4')
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A:H')
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
