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
            'glk_db.sql',
            'postman_seed_full.sql',
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
                    // Skip empty lines dan comments
                    return !empty($statement) 
                        && !str_starts_with($statement, '--') 
                        && !str_starts_with($statement, '/*')
                        && !str_starts_with($statement, '!');
                }
            );

            $this->command->info("   └─ Total statements: " . count($statements));

            // Execute setiap statement
            $success = 0;
            $failed = 0;

            foreach ($statements as $statement) {
                try {
                    DB::unprepared($statement);
                    $success++;
                } catch (\Exception $e) {
                    $failed++;
                    $this->command->error("   ✗ Error: " . substr($statement, 0, 50) . '...');
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
