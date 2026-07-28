<?php

namespace Database\Seeders;

use App\Models\OracleRace;
use Illuminate\Database\Seeder;

class OracleRaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/OracleRaceSeeder.php
    public function run(): void
    {
        $races = [
            'Warrior' => 'El impulso directo, sin cálculo de por medio.',
            'Spellcaster' => 'La mente calculando, no el instinto.',
            'Dragon' => 'Ambición cruda, poder que no pide permiso.',
            'Fiend' => 'La sombra, lo que no querés admitir.',
            'Fairy' => 'Idealismo, a veces ingenuo.',
            'Beast' => 'Instinto puro, sin filtro.',
            'Machine' => 'Lógica fría, rigidez.',
            'Zombie' => 'Lo no resuelto, el pasado que vuelve.',
            'Aqua' => 'Emoción, lo que fluye.',
            'Pyro' => 'Pasión, impulsividad.',
            'Thunder' => 'El cambio repentino.',
            'Rock' => 'Estancamiento, estabilidad terca.',
            'Plant' => 'Crecimiento lento, paciencia.',
            'Insect' => 'Enjambre, ansiedad acumulada.',
            'Winged Beast' => 'Libertad, huida.',
            'Sea Serpent' => 'El inconsciente profundo.',
            'Reptile' => 'Astucia, cálculo.',
            'Psychic' => 'Intuición.',
            'Wyrm' => 'Poder ancestral.',
            'Cyberse' => 'El vos digital, lo moderno.',
            'Illusion' => 'Engaño, incertidumbre.',
            'Beast-Warrior' => 'Fuerza bruta con algo de método.',
            'Divine-Beast' => 'Intervención mayor, fuera de tu control.',
            'Creator-God' => 'Lo que está por encima de cualquier regla del juego.',
        ];

        foreach ($races as $race => $essence) {
            OracleRace::updateOrCreate(['race' => $race], ['essence' => $essence]);
        }
    }
}
