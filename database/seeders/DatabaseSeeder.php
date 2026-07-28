<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\OraclePostureSeeder;
use Database\Seeders\OracleRaceSeeder;
use Database\Seeders\OracleAttributeSeeder;
use Database\Seeders\OracleNumberSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
{
    $this->call([
        OracleRaceSeeder::class,
        OracleAttributeSeeder::class,
        OracleNumberSeeder::class,
        OraclePostureSeeder::class,
    ]);
}
}
