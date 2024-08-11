<?php

namespace App\Exports;

use App\Task;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;

class TaskReportExport implements FromQuery, WithCustomStartCell, WithEvents, WithHeadings, WithMapping, WithTitle
{
    use Exportable;
    private $employee;
    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    public function title(): string
    {
        return $this->employee;
    }

    public function query()
    {
        $employee = $this->employee;

        $start = date('Y-m-d', strtotime('2024-02-01'));
        $end = date('Y-m-d', strtotime('2024-06-30'));
        $tasks = Task::query()->with('employee', 'unit', 'service')->whereBetween('created_at', [$start, $end])
            ->whereHas('employee', function ($q) use ($employee) {
                $q->where('first_name', $employee);
            })->orderBy('created_at', 'ASC');
        return $tasks;
    }
    public function startCell(): string
    {
        return 'A4';
    }
    public function headings(): array
    {
        return  [
            'Task',
            'Unit',
            'Service',
            'Created Date',
            'Deadline',
            'Completed Date',
            'Status'
        ];
    }
    public function map($tasks): array
    {
        return [
            $tasks->task,
            $tasks->unit->name,
            $tasks->service->name,
            date('d M Y', strtotime($tasks->created_at)),
            date('d M Y', strtotime($tasks->deadline)),
            $tasks->completed_time == null ? '-' : date('d M Y', strtotime($tasks->completed_time)),
            ucfirst($tasks->status),
        ];
    }


    public function registerEvents(): array
    {
        $employee = $this->employee;
        $tasksCount = Task::with('employee')->whereHas('employee', function ($q) use ($employee) {
            $q->where('first_name', $employee);
        })->get();

        $count = $tasksCount->count();
        $nextCount = $count + 1;
        $countStatus = [
            'totalTask' =>  $tasksCount->count(),
            'totalCompleted' => $tasksCount->where('status', 'confirmed')->count(),
            'totalOnProgress' => $tasksCount->where('status', 'on progress')->count(),
            'totalOpen' => $tasksCount->where('status', 'open')->count(),
            'totalDone' => $tasksCount->where('status', 'done')->count(),
        ];
        return [
            BeforeSheet::class => function (BeforeSheet $event) use ($employee, $countStatus) {
                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->mergeCells('A2:G2');
                $event->sheet->getDelegate()->setCellValue('A1', "Rekap Task DNS TEAM")
                    ->getStyle('A1')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(20);
                $event->sheet->getDelegate()->setCellValue('A2', "Februari s.d. Juni 2024")
                    ->getStyle('A2')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(16);
                $event->sheet->getDelegate()->getStyle('A1:G1')
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A2:G2')
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
            AfterSheet::class => function (AfterSheet $event) use ($count, $tasksCount, $countStatus, $nextCount) {
                if ($tasksCount->count() > 0) {
                    $event->sheet->getDelegate()->setCellValue("A$nextCount", "Total Tasks")
                        ->getStyle("A$nextCount")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);
                    $event->sheet->getDelegate()->setCellValue("G$nextCount", $countStatus['totalTask'])
                        ->getStyle("G$nextCount")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);
                    $event->sheet->getDelegate()->mergeCells("A$nextCount:F$nextCount");
                    $event->sheet->getDelegate()->getStyle("A$nextCount:F$nextCount")
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $event->sheet->getDelegate()->getStyle("A$nextCount")->getFont()->setItalic(true);
                    $event->sheet->getDelegate()->getStyle("G$nextCount")
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $event->sheet->getDelegate()->getStyle("A$nextCount")->getFont()->setItalic(true);

                    $nextCount++;
                    $event->sheet->getDelegate()->setCellValue("A$nextCount", "Total Done Tasks")
                        ->getStyle("A$nextCount")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);
                    $event->sheet->getDelegate()->setCellValue("G$nextCount", $countStatus['totalDone'])
                        ->getStyle("G$nextCount")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);
                    $event->sheet->getDelegate()->mergeCells("A$nextCount:F$nextCount");
                    $event->sheet->getDelegate()->getStyle("A$nextCount:F$nextCount")
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $event->sheet->getDelegate()->getStyle("A$nextCount")->getFont()->setItalic(true);
                    $event->sheet->getDelegate()->getStyle("G$nextCount")
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $event->sheet->getDelegate()->getStyle("A$nextCount")->getFont()->setItalic(true);

                    $nextCount++;
                    $event->sheet->getDelegate()->setCellValue("A$nextCount", "Total On Progress Tasks")
                        ->getStyle("A$nextCount")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);
                    $event->sheet->getDelegate()->setCellValue("G$nextCount", $countStatus['totalOnProgress'])
                        ->getStyle("G$nextCount")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);
                    $event->sheet->getDelegate()->mergeCells("A$nextCount:F$nextCount");
                    $event->sheet->getDelegate()->getStyle("A$nextCount:F$nextCount")
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $event->sheet->getDelegate()->getStyle("A$nextCount")->getFont()->setItalic(true);
                    $event->sheet->getDelegate()->getStyle("G$nextCount")
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $event->sheet->getDelegate()->getStyle("A$nextCount")->getFont()->setItalic(true);

                    $nextCount++;
                    $event->sheet->getDelegate()->setCellValue("A$nextCount", "Total Confirmed Tasks")
                        ->getStyle("A$nextCount")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);
                    $event->sheet->getDelegate()->setCellValue("G$nextCount", $countStatus['totalCompleted'])
                        ->getStyle("G$nextCount")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);
                    $event->sheet->getDelegate()->mergeCells("A$nextCount:F$nextCount");
                    $event->sheet->getDelegate()->getStyle("A$nextCount:F$nextCount")
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $event->sheet->getDelegate()->getStyle("A$nextCount")->getFont()->setItalic(true);
                    $event->sheet->getDelegate()->getStyle("G$nextCount")
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $event->sheet->getDelegate()->getStyle("A$nextCount")->getFont()->setItalic(true);

                    $nextCount++;

                    $event->sheet->getDelegate()->setCellValue("A$nextCount", "Total Open Tasks")
                        ->getStyle("A$nextCount")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);
                    $event->sheet->getDelegate()->setCellValue("G$nextCount", $countStatus['totalOpen'])
                        ->getStyle("G$nextCount")
                        ->getFont()
                        ->setBold(true)
                        ->setSize(12);
                    $event->sheet->getDelegate()->mergeCells("A$nextCount:F$nextCount");
                    $event->sheet->getDelegate()->getStyle("A$nextCount:F$nextCount")
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $event->sheet->getDelegate()->getStyle("A$nextCount")->getFont()->setItalic(true);
                    $event->sheet->getDelegate()->getStyle("G$nextCount")
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $event->sheet->getDelegate()->getStyle("A$nextCount")->getFont()->setItalic(true);
                }

                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle('A4:G4')->applyFromArray($styleArray);

                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension('3')->setRowHeight(22);
                $event->sheet->getDelegate()->getRowDimension('4')->setRowHeight(22);

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);

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
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }

        ];
    }
}
