<?php

namespace App\Exports;

use App\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SheetTask implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function sheets(): array
    {
        $sheets = [];
        $employees = Employee::get();

        foreach ($employees as $employee) {
            $sheets[$employee->first_name] = new TaskReportExport($employee->first_name);
        }

        return $sheets;
    }
}
