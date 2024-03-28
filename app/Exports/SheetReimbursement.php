<?php

namespace App\Exports;

use App\Employee;
use App\Reimbursement;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SheetReimbursement implements WithMultipleSheets
{
    use Exportable;

    protected $minggu;

    public function __construct($minggu)
    {
        $this->minggu = $minggu;
    }

    /**
     * @return array
     */

    public function sheets(): array
    {
        $sheets = [];
        $minggu = $this->minggu;
        $employees = Employee::get();

        foreach ($employees as $employee) {
            $sheets[$employee->first_name] = new ReimbursementExport($minggu, $employee->first_name);
        }

        return $sheets;
    }
}