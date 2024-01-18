<?php

namespace Database\Seeders;

use App\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
            'unit_id'=> 1,
            'name'=> 'ISPC'
        ]);
        Service::create([
            'unit_id'=> 1,
            'name'=> 'MI'
        ]);
        Service::create([
            'unit_id'=> 1,
            'name'=> 'SMARTMEDIS'
        ]);
        Service::create([
            'unit_id'=> 2,
            'name'=> 'ABITALK'
        ]);
        Service::create([
            'unit_id'=> 2,
            'name'=> 'ABITORIAL'
        ]);
        Service::create([
            'unit_id'=> 2,
            'name'=> 'ABICUBATION'
        ]);
    }
}