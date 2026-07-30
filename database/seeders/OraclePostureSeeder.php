<?php

namespace Database\Seeders;

use App\Models\OraclePosture;
use Illuminate\Database\Seeder;

class OraclePostureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $postures = [
            'ofensiva' => [
                'label' => 'Ofensiva',
                'icon' => 'Swords',
                'description' => 'El ataque supera claramente a la defensa — la fuerza se expresa hacia afuera, sin guardarse nada.',
            ],
            'equilibrada' => [
                'label' => 'Equilibrada',
                'icon' => 'Scale',
                'description' => 'Ataque y defensa están cerca — no domina ni el impulso ni la resistencia, todavía se está definiendo.',
            ],
            'defensiva' => [
                'label' => 'Defensiva',
                'icon' => 'Shield',
                'description' => 'La defensa supera al ataque — la fuerza está ahí, pero se guarda en vez de mostrarse.',
            ],
        ];

        foreach ($postures as $posture => $data) {
            OraclePosture::updateOrCreate(['posture' => $posture], $data);
        }
    }
}
