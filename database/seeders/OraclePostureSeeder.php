<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OraclePosture;   

class OraclePostureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $postures = [
        'ofensiva' => ['label' => 'Ofensiva', 'icon' => 'Swords'],
        'equilibrada' => ['label' => 'Equilibrada', 'icon' => 'Scale'],
        'defensiva' => ['label' => 'Defensiva', 'icon' => 'Shield'],
    ];

    foreach ($postures as $posture => $data) {
        OraclePosture::updateOrCreate(['posture' => $posture], $data);
    }
}
}
