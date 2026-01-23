<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Urutan seeding penting untuk menjaga foreign key constraints!
     */
    public function run()
    {
        $this->command->info('🚀 Starting Database Seeding Process...');
        $this->command->info('');

        // ============================================
        // FASE 1: Core System Setup
        // ============================================
        $this->command->warn('📦 FASE 1: Core System Setup');
        
        // 1. Admin User (untuk login admin dashboard)
        $this->call(AdminUserSeeder::class);
        
        // 2. Training Dataset untuk AI/ML
        $this->call(TrainingDatasetSeeder::class);
        
        // 3. Profiling System (Financial Aspects, Questions, Options)
        $this->call(ProfilingSystemSeeder::class);

        // 4. intervention seeder
        $this->call(InterventionTemplateSeeder::class);

        $this->command->info('');

        // ============================================
        // FASE 2: Game Content
        // ============================================
        $this->command->warn('📦 FASE 2: Game Content');
        
        // 4. Board Tiles (papan permainan)
        $this->call(BoardTilesSeeder::class);
        
        // 5. Cards (Risk & Chance cards)
        $this->call(CardSeeder::class);
        
        // 6. Quiz Cards & Options
        $this->call(QuizSeeder::class);
        
        // 7. Scenarios (skenario keputusan keuangan)
        $this->call(ScenarioSeeder::class);
        
        // 8. Scenario Options (pilihan jawaban scenario)
        // CATATAN: ScenarioOptionSeeder memiliki dependency ke Scenarios
        $this->call(ScenarioOptionSeeder::class);

        // 9. interventio seeder
        $this->call(InterventionTemplateSeeder::class);

        // 10. Scoring Configuration
        $this->call(ScoringConfigSeeder::class);

        $this->command->info('');

        // ============================================
        // FASE 3: Legacy SQL Data (Optional)
        // ============================================
        $this->command->warn('📦 FASE 3: Legacy SQL Import (Optional)');
        // $this->command->info('⚠️  Melewati SqlSeeder untuk menghindari duplikasi data...');
        
        // UNCOMMENT jika ingin import dari SQL files:
        $this->call(SqlSeeder::class);

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════');
        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('═══════════════════════════════════════════════');
        
        // Summary
        $this->command->info('');
        $this->command->info('📊 Seeded Data Summary:');
        $this->command->info('   ✓ Admin Users');
        $this->command->info('   ✓ Training Datasets (25 records)');
        $this->command->info('   ✓ Profiling System (7 aspects, 3 questions)');
        $this->command->info('   ✓ Board Tiles (28 tiles)');
        $this->command->info('   ✓ Cards (Risk & Opportunity)');
        $this->command->info('   ✓ Quiz Cards (20+ quizzes)');
        $this->command->info('   ✓ Scenarios (60+ scenarios)');
        $this->command->info('   ✓ Scenario Options (180+ options)');
        $this->command->info('');
    }
}