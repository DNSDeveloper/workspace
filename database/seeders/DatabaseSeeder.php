<?php

use App\Role;
use Database\Seeders\RolesSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\TaskSeeder;
use Database\Seeders\UnitSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(TaskSeeder::class);
    }
}