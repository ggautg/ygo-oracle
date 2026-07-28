<?php

namespace Database\Seeders;

use App\Models\OracleNumber;
use Illuminate\Database\Seeder;

class OracleNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numbers = [
            1 => 'Arranque — algo empieza de cero.',
            2 => 'Elección pendiente — dos caminos sin resolver.',
            3 => 'Algo crece más rápido de lo que controlás.',
            4 => 'Estructura — lo que te sostiene aunque no lo veas.',
            5 => 'Quiebre — un cambio forzado.',
            6 => 'Equilibrio con costo — algo se acomoda, pero alguien cede.',
            7 => 'Introspección — pausa obligada.',
            8 => 'Poder acumulado — consecuencias grandes.',
            9 => 'Cierre de ciclo.',
        ];

        foreach ($numbers as $number => $meaning) {
            OracleNumber::updateOrCreate(
                ['number' => $number],
                ['meaning' => $meaning]
            );
        }
    }
}
