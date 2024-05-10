<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class JobsReminderPresensi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   protected $employee;
    protected $waktu;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employee, $waktu)
    {
        $this->employee = $employee;
        $this->waktu = $waktu;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    try {
            $header = [
                'Content-Type' => 'application/json',
            ];
            $data = [
                "name"=> $this->employee->first_name . ' ' . $this->employee->last_name,
                'code' => $this->waktu == 'Masuk' ? 'A001' : 'A003'
            ];
            $client = new Client();
            $url = env("API_URL") . 'call-service/' . $this->employee->phone;
            $storeToBot = $client->post($url, [
                "headers" => $header,
                "json" => $data
            ]);
            Log::info('Notification sent to ' . $this->employee->first_name . ' ' . $this->employee->last_name);
        } catch (\Throwable $th) {
            Log::error('Error sending notification to ' . $this->employee->first_name . ' ' . $this->employee->last_name . ': ' . $th->getMessage());
        }
    }
}