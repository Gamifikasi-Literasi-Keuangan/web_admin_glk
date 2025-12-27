<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Call SQL Seeder untuk import data dari file SQL
        $this->call([
            SqlSeeder::class,
            TrainingDatasetSeeder::class,
            ProfilingSystemSeeder::class,
        ]);

        $this->command->info('✅ Database seeded successfully!');
    }
}