<?php

namespace Database\Seeders;

use App\Models\OracleTheme;
use Illuminate\Database\Seeder;

class OracleThemesSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            // DM/Classic
            [
                'slug' => 'dark-magician',
                'name' => 'Mago Oscuro',
                'generation' => 1,
                'era' => 'DM',
                'icon' => 'Sparkles',
                'color' => '#8b5cf6',
                'vibe' => 'Magia, destino y potencial',
                'description' => 'El arquetipo de Yugi, símbolo de la magia clásica.',
                'keywords' => ['Magician', 'Magos'],
                'archetypes' => ['Dark Magician'],
            ],
            [
                'slug' => 'blue-eyes',
                'name' => 'Ojos Azules',
                'generation' => 1,
                'era' => 'DM',
                'icon' => 'Droplets',
                'color' => '#60a5fa',
                'vibe' => 'Orgullo, nobleza y poder puro',
                'description' => 'El dragón supremo de Kaiba, poder ancestral.',
                'keywords' => ['Blue-Eyes'],
                'archetypes' => ['Blue-Eyes'],
            ],
            [
                'slug' => 'red-eyes',
                'name' => 'Ojos Rojos',
                'generation' => 1,
                'era' => 'DM',
                'icon' => 'Flame',
                'color' => '#ef4444',
                'vibe' => 'Furia, rebeldía y retorno',
                'description' => 'El dragón de los caídos, fuerza desde las sombras.',
                'keywords' => ['Red-Eyes'],
                'archetypes' => ['Red-Eyes'],
            ],
            // GX
            [
                'slug' => 'elemental-hero',
                'name' => 'Héroes Elementales',
                'generation' => 2,
                'era' => 'GX',
                'icon' => 'Zap',
                'color' => '#f59e0b',
                'vibe' => 'Valor, fusión y superación',
                'description' => 'Los héroes de Jaden Yuki, lucha con corazón.',
                'keywords' => ['Elemental HERO', 'HERO'],
                'archetypes' => ['Elemental HERO', 'Evil HERO'],
            ],
            [
                'slug' => 'cyberdragon',
                'name' => 'Ciber Dragón',
                'generation' => 2,
                'era' => 'GX',
                'icon' => 'Cpu',
                'color' => '#94a3b8',
                'vibe' => 'Mecanización, evolución y velocidad',
                'description' => 'Máquinas que superan sus limitaciones.',
                'keywords' => ['Cyber Dragon', 'Cyber'],
                'archetypes' => ['Cyber Dragon'],
            ],
            // 5D's
            [
                'slug' => 'speedroid',
                'name' => 'Velocidad & Sincronicidad',
                'generation' => 3,
                'era' => "5D's",
                'icon' => 'Wind',
                'color' => '#22d3ee',
                'vibe' => 'Libertad, velocidad y destino',
                'description' => 'El mundo de los D-Wheelers y los Synchro.',
                'keywords' => ['Speedroid', 'Synchron', 'Stardust'],
                'archetypes' => ['Speedroid', 'Synchron'],
            ],
            [
                'slug' => 'stardust',
                'name' => 'Polvo Estelar',
                'generation' => 3,
                'era' => "5D's",
                'icon' => 'Star',
                'color' => '#a78bfa',
                'vibe' => 'Esperanza, conexión y renovación',
                'description' => 'Stardust Dragon y la luz que guía.',
                'keywords' => ['Stardust'],
                'archetypes' => ['Stardust Dragon'],
            ],
            // ZEXAL
            [
                'slug' => 'gagaga',
                'name' => 'Gagaga & Numeron',
                'generation' => 4,
                'era' => 'ZEXAL',
                'icon' => 'Sparkles',
                'color' => '#ec4899',
                'vibe' => 'Divertido, caótico y numérico',
                'description' => 'Yuma y el poder de los Números.',
                'keywords' => ['Gagaga', 'Number', 'Utopia'],
                'archetypes' => ['Gagaga', 'Number'],
            ],
            [
                'slug' => 'utopia',
                'name' => 'Utopía',
                'generation' => 4,
                'era' => 'ZEXAL',
                'icon' => 'Crown',
                'color' => '#fbbf24',
                'vibe' => 'Esperanza, amistad y victoria',
                'description' => 'Number 39: Utopia, el símbolo de la esperanza.',
                'keywords' => ['Utopia', 'Number 39'],
                'archetypes' => ['Utopia'],
            ],
            // ARC-V
            [
                'slug' => 'odd-eyes',
                'name' => 'Ojos Impares',
                'generation' => 5,
                'era' => 'ARC-V',
                'icon' => 'Eye',
                'color' => '#f97316',
                'vibe' => 'Dimensiones, acción y fusión múltiple',
                'description' => 'Yuya y el Pendulum/Action Duels.',
                'keywords' => ['Odd-Eyes', 'Performapal'],
                'archetypes' => ['Odd-Eyes', 'Performapal'],
            ],
            // VRAINS
            [
                'slug' => 'link',
                'name' => 'Ciberse & Links',
                'generation' => 6,
                'era' => 'VRAINS',
                'icon' => 'Network',
                'color' => '#06b6d4',
                'vibe' => 'Ciberespacio, hackeo y conexión',
                'description' => 'Playmaker y el mundo de LINK.',
                'keywords' => ['@Ignister', 'Firewall', 'Decode Talker'],
                'archetypes' => ['@Ignister', 'Firewall'],
            ],
            // SEVENS/GO RUSH
            [
                'slug' => 'rush-duel',
                'name' => 'Rush Duel',
                'generation' => 7,
                'era' => 'SEVENS/GO RUSH',
                'icon' => 'Rocket',
                'color' => '#84cc16',
                'vibe' => 'Rápido, desenfadado y maximalista',
                'description' => 'La nueva era de duelos acelerados.',
                'keywords' => ['Maximum', 'Rush'],
                'archetypes' => ['Maximum'],
            ],
        ];

        foreach ($themes as $theme) {
            OracleTheme::updateOrCreate(['slug' => $theme['slug']], $theme);
        }
    }
}
