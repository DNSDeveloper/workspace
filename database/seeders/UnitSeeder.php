<?php

namespace Database\Seeders;

use App\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create([
            'name' => 'Digital Nusantara Sinergi',
        ]);
        Unit::create([
            'name' => 'Ayo Bisa Indonesia'
        ]);
        Unit::create([
            'name' => 'STIQR'
        ]);
    }
}
