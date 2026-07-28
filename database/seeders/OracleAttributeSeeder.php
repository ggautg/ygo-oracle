<?php

namespace Database\Seeders;

use App\Models\OracleAttribute;
use Illuminate\Database\Seeder;

class OracleAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $atributtes = [
            'LIGHT' => 'Idealismo, claridad — a veces ingenua.',
            'DARK' => 'Lo que se mueve sin que lo veas venir.',
            'EARTH' => 'Estabilidad, aunque a veces terca.',
            'WATER' => 'Emoción, lo que fluye sin que lo controles.',
            'FIRE' => 'Impulso, pasión sin filtro.',
            'WIND' => 'Cambio rápido, libertad, poco apego.',
            'DIVINE' => 'Fuera de tu control, intervención mayor.',
        ];

        foreach ($atributtes as $atributte => $essence) {
            OracleAttribute::updateOrCreate(
                ['attribute' => $atributte],
                ['essence' => $essence]
            );
        }
    }
}
