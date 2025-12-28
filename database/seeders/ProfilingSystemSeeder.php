<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FinancialAspect;
use App\Models\ProfilingQuestion;
use App\Models\ProfilingQuestionOption;
use App\Models\ProfilingQuestionAspect;
use Illuminate\Support\Facades\DB;

class ProfilingSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding Profiling System...');

        DB::beginTransaction();

        try {
            // 1. Seed Financial Aspects (Static data)
            $this->command->info('📊 Seeding Financial Aspects...');
            $aspects = [
                ['aspect_key' => 'pendapatan', 'display_name' => 'Pendapatan'],
                ['aspect_key' => 'anggaran', 'display_name' => 'Anggaran'],
                ['aspect_key' => 'tabungan_dan_dana_darurat', 'display_name' => 'Tabungan & Dana Darurat'],
                ['aspect_key' => 'utang', 'display_name' => 'Utang'],
                ['aspect_key' => 'asuransi_dan_proteksi', 'display_name' => 'Asuransi & Proteksi'],
                ['aspect_key' => 'investasi', 'display_name' => 'Investasi'],
                ['aspect_key' => 'tujuan_jangka_panjang', 'display_name' => 'Tujuan Jangka Panjang'],
            ];

            foreach ($aspects as $aspect) {
                FinancialAspect::create($aspect);
            }

            $this->command->info('✅ Created 7 Financial Aspects');

            // 2. Seed Profiling Questions
            $this->command->info('❓ Seeding Profiling Questions...');
            $questions = [
                [
                    'question_code' => 'q1',
                    'question_text' => 'Apakah Anda menabung sebelum mengeluarkan uang?',
                    'max_score' => 40,
                    'is_active' => true
                ],
                [
                    'question_code' => 'q2',
                    'question_text' => 'Berapa rasio total cicilan terhadap pendapatan bulanan Anda?',
                    'max_score' => 35,
                    'is_active' => true
                ],
                [
                    'question_code' => 'q3',
                    'question_text' => 'Apakah Anda berinvestasi secara rutin dan teratur?',
                    'max_score' => 25,
                    'is_active' => true
                ]
            ];

            foreach ($questions as $question) {
                ProfilingQuestion::create($question);
            }

            $this->command->info('✅ Created 3 Profiling Questions');

            // 3. Seed Question Options
            $this->command->info('📝 Seeding Question Options...');
            
            // Q1 Options
            $q1Options = [
                ['question_id' => 1, 'option_code' => 'A', 'option_text' => 'Tidak pernah', 'score' => 0],
                ['question_id' => 1, 'option_code' => 'B', 'option_text' => 'Kadang-kadang', 'score' => 10],
                ['question_id' => 1, 'option_code' => 'C', 'option_text' => 'Sering', 'score' => 20],
                ['question_id' => 1, 'option_code' => 'D', 'option_text' => 'Hampir selalu', 'score' => 30],
                ['question_id' => 1, 'option_code' => 'E', 'option_text' => 'Selalu dan otomatis', 'score' => 40],
            ];

            // Q2 Options
            $q2Options = [
                ['question_id' => 2, 'option_code' => 'A', 'option_text' => '> 50%', 'score' => 0],
                ['question_id' => 2, 'option_code' => 'B', 'option_text' => '30% – 50%', 'score' => 9],
                ['question_id' => 2, 'option_code' => 'C', 'option_text' => '20% – 30%', 'score' => 18],
                ['question_id' => 2, 'option_code' => 'D', 'option_text' => '10% – 20%', 'score' => 26],
                ['question_id' => 2, 'option_code' => 'E', 'option_text' => '< 10%', 'score' => 35],
            ];

            // Q3 Options
            $q3Options = [
                ['question_id' => 3, 'option_code' => 'A', 'option_text' => 'Tidak', 'score' => 0],
                ['question_id' => 3, 'option_code' => 'B', 'option_text' => 'Pernah mencoba', 'score' => 6],
                ['question_id' => 3, 'option_code' => 'C', 'option_text' => 'Melakukan tapi tidak rutin', 'score' => 13],
                ['question_id' => 3, 'option_code' => 'D', 'option_text' => 'Rutin dan otomatis', 'score' => 19],
                ['question_id' => 3, 'option_code' => 'E', 'option_text' => 'Rutin, otomatis, dan terus meningkat', 'score' => 25],
            ];

            $allOptions = array_merge($q1Options, $q2Options, $q3Options);
            foreach ($allOptions as $option) {
                ProfilingQuestionOption::create($option);
            }

            $this->command->info('✅ Created 15 Question Options (5 per question)');

            // 4. Seed Question-Aspect Relationships
            $this->command->info('🔗 Seeding Question-Aspect Relationships...');
            
            // Q1 -> Pendapatan, Anggaran, Tabungan & Dana Darurat
            $q1AspectIds = FinancialAspect::whereIn('aspect_key', [
                'pendapatan', 'anggaran', 'tabungan_dan_dana_darurat'
            ])->pluck('id');

            foreach ($q1AspectIds as $aspectId) {
                ProfilingQuestionAspect::create([
                    'question_id' => 1,
                    'aspect_id' => $aspectId
                ]);
            }

            // Q2 -> Utang, Asuransi & Proteksi
            $q2AspectIds = FinancialAspect::whereIn('aspect_key', [
                'utang', 'asuransi_dan_proteksi'
            ])->pluck('id');

            foreach ($q2AspectIds as $aspectId) {
                ProfilingQuestionAspect::create([
                    'question_id' => 2,
                    'aspect_id' => $aspectId
                ]);
            }

            // Q3 -> Investasi, Tujuan Jangka Panjang
            $q3AspectIds = FinancialAspect::whereIn('aspect_key', [
                'investasi', 'tujuan_jangka_panjang'
            ])->pluck('id');

            foreach ($q3AspectIds as $aspectId) {
                ProfilingQuestionAspect::create([
                    'question_id' => 3,
                    'aspect_id' => $aspectId
                ]);
            }

            $this->command->info('✅ Created Question-Aspect mappings');
            $this->command->info('   - Q1: 3 aspects (Pendapatan, Anggaran, Tabungan)');
            $this->command->info('   - Q2: 2 aspects (Utang, Asuransi)');
            $this->command->info('   - Q3: 2 aspects (Investasi, Tujuan Jangka Panjang)');

            DB::commit();

            $this->command->info('');
            $this->command->info('✅ Profiling System seeded successfully!');
            $this->command->info('📊 Summary:');
            $this->command->info('   - Financial Aspects: 7');
            $this->command->info('   - Profiling Questions: 3');
            $this->command->info('   - Question Options: 15');
            $this->command->info('   - Question-Aspect Links: 7');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error seeding profiling system: ' . $e->getMessage());
            throw $e;
        }
    }
}
