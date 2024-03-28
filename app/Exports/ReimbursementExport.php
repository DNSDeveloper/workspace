<?php

namespace App\Exports;

use App\Reimbursement;
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
    public function __construct($minggu, $employee)
    {
        $this->minggu = $minggu;
        $this->employee = $employee;
    }

    public function title(): string
    {
        return $this->employee;
    }

    public function query()
    {
        $minggu = $this->minggu;
        $employee = $this->employee;

        $reimbursements = Reimbursement::query()
            ->where('minggu', $this->minggu)
            ->whereHas('employee', function ($q) use ($employee) {
                $q->where('first_name', $employee);
            })->orderBy('jenis','ASC');
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
        ];
    }
    public function map($reimbursement): array
    {
        return [
            $reimbursement->tanggal_reimbursement == null ? '' : date('d M Y', strtotime($reimbursement->tanggal_reimbursement)),
            ucfirst($reimbursement->jenis),
            ucfirst($reimbursement->deskripsi),
            $reimbursement->nominal == null ? '' : 'Rp ' . number_format($reimbursement->nominal, 0, 0, '.'),
            $reimbursement->tanggal_transfer == null ? '' : date('d M Y', strtotime($reimbursement->tanggal_transfer)),
            ucfirst($reimbursement->status),
            ucfirst($reimbursement->catetan),
        ];
    }


    public function registerEvents(): array
    {
        $minggu = $this->minggu;
        $employee = $this->employee;
        $reimbursementsCount = $reimbursements = Reimbursement::with('employee')->where('minggu', $this->minggu)->whereHas('employee', function ($q) use ($employee) {
            $q->where('first_name', $employee);
        })->get();

        $count = 6 + $reimbursementsCount->count();
        $bulan = Carbon::now()->isoFormat('MMMM');
        return [
            BeforeSheet::class => function (BeforeSheet $event) use ($minggu, $bulan, $employee) {
                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->mergeCells('A2:G2');
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
                $event->sheet->getDelegate()->getStyle('A3:G3')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->mergeCells('A3:G3');

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
            AfterSheet::class => function (AfterSheet $event) use ($count, $reimbursementsCount) {
                if ($reimbursementsCount->count() > 0) {
                    $event->sheet->getDelegate()->setCellValue("D$count", "Rp " . number_format($reimbursementsCount->sum('nominal'), 0, 0, '.'))
                        ->getStyle("D$count")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);
                    $event->sheet->getDelegate()->setCellValue("A$count", "Total")
                        ->getStyle("A$count")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);
                    $event->sheet->getDelegate()->mergeCells("A$count:C$count");
                    $event->sheet->getDelegate()
                        ->getStyle('A')
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $event->sheet->getDelegate()->getStyle("A$count")->getFont()->setItalic(true);
                }

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

                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension('3')->setRowHeight(22);
                $event->sheet->getDelegate()->getRowDimension('4')->setRowHeight(22);

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(30);

                $event->sheet->getDelegate()->getStyle('A4:G4')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(12)
                    ->getColor()
                    ->setARGB('ffffff');
                $event->sheet->getDelegate()->getStyle('A4:G4')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('3d98d5');
                $event->sheet->getDelegate()
                    ->getStyle('A:G')
                    ->getAlignment()
                    ->setWrapText(true);
                $event->sheet->getDelegate()
                    ->getStyle('A:G')
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
                    ->getStyle('A4:G4')
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A:G')
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}