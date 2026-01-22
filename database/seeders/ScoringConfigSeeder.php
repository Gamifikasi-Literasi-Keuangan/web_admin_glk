<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScoringConfigSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        DB::table('scoring_config')->insert([
            [
                'config_key' => 'max_player_score',
                'config_value' => 100,
                'description' => 'Skor maksimal per kategori yang bisa dicapai player. Digunakan untuk normalisasi perhitungan weighted score.',
                'category' => 'scoring',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'config_key' => 'sensitivity_factor',
                'config_value' => 0.5,
                'description' => 'Faktor sensitivitas untuk weighted scoring (0-1). Semakin besar nilai, semakin besar perbedaan antara soal mudah vs sulit untuk player dengan skill berbeda.',
                'category' => 'scoring',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'config_key' => 'min_score_multiplier',
                'config_value' => 0.3,
                'description' => 'Batas bawah score multiplier. Mencegah penalty/reward menjadi terlalu kecil.',
                'category' => 'scoring',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'config_key' => 'max_score_multiplier',
                'config_value' => 2.0,
                'description' => 'Batas atas score multiplier. Mencegah reward/penalty menjadi terlalu besar.',
                'category' => 'scoring',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $this->command->info('✓ Scoring config seeded successfully');
    }
}
