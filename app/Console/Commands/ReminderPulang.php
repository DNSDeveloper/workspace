<?php

namespace App\Console\Commands;

use App\Employee;
use App\Jobs\ReminderEmployee;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReminderPulang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:pulang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $employees = Employee::get();
            if (Carbon::now()->isWeekday()) {
                Log::info('REMINDER RUNNING');
                $delay = now()->addMinutes(5);
                $waktu = 'Pulang';
                foreach ($employees as $employee) {
                    ReminderEmployee::dispatch($employee, $waktu)->delay($delay);
                    $delay->addMinutes(5);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error occurred: ' . $e->getMessage());
        }
    }
}