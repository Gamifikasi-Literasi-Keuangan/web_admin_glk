<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->command->info('🌱 Starting SQL Seeder...');

        // Path ke file SQL seeder
        $sqlFiles = [
            'glk_db.sql'
        ];

        foreach ($sqlFiles as $sqlFile) {
            $path = database_path("seeders/sql/{$sqlFile}");

            if (!File::exists($path)) {
                $this->command->warn("⚠️  File tidak ditemukan: {$sqlFile}");
                continue;
            }

            $this->command->info("📄 Loading: {$sqlFile}");

            // Baca file SQL
            $sql = File::get($path);

            // Split berdasarkan statement (;)
            // Hapus comment dan baris kosong
            $statements = array_filter(
                array_map('trim', explode(';', $sql)),
                function ($statement) {
                    // Remove all comment lines
                    $lines = explode("\n", $statement);
                    $cleanedLines = array_filter($lines, function($line) {
                        $trimmed = trim($line);
                        return !empty($trimmed) && !str_starts_with($trimmed, '--');
                    });
                    $cleaned = trim(implode("\n", $cleanedLines));
                    
                    // Skip empty statements and MySQL directives
                    return !empty($cleaned) 
                        && !str_starts_with($cleaned, '/*')
                        && !str_starts_with($cleaned, '!');
                }
            );

            $this->command->info("   └─ Total statements: " . count($statements));
            
            // Debug: Show all statements for postman_seed_full.sql
            if ($sqlFile === 'postman_seed_full.sql') {
                foreach ($statements as $idx => $stmt) {
                    $preview = substr(str_replace(["\n", "\r"], ' ', $stmt), 0, 80);
                    $this->command->info("   Statement #" . ($idx + 1) . ": " . $preview . "...");
                }
            }

            // Execute setiap statement
            $success = 0;
            $failed = 0;

            foreach ($statements as $index => $statement) {
                try {
                    // Clean up the statement - remove comment lines
                    $lines = explode("\n", $statement);
                    $cleanedLines = array_filter($lines, function($line) {
                        $trimmed = trim($line);
                        return !empty($trimmed) && !str_starts_with($trimmed, '--');
                    });
                    $cleanStatement = trim(implode("\n", $cleanedLines));
                    
                    if (empty($cleanStatement)) {
                        continue;
                    }
                    
                    DB::unprepared($cleanStatement);
                    $success++;
                    
                    // Debug: Show what's being executed
                    if (str_contains(strtolower($cleanStatement), 'boardtiles')) {
                        $this->command->info("   → Executing boardtiles statement");
                        $this->command->info("   → Statement preview: " . substr($cleanStatement, 0, 100) . '...');
                    }
                } catch (\Exception $e) {
                    $failed++;
                    $this->command->error("   ✗ Error in statement #" . ($index + 1) . ": " . substr($statement, 0, 50) . '...');
                    $this->command->error("     " . $e->getMessage());
                }
            }

            $this->command->info("   ✓ Success: {$success} | Failed: {$failed}");
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ SQL Seeder completed!');
    }
}
