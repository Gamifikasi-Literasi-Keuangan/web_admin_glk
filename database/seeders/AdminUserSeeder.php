<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('auth_users')->insert([
            'username' => 'admin',
            'passwordHash' => Hash::make('admin123'),
            'google_id' => null,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('   Username: admin');
        $this->command->info('   Password: admin123');
    }
}
