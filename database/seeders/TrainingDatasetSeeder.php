<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TrainingDataset;

class TrainingDatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding Training Dataset...');

        // Hapus data lama jika ada
        TrainingDataset::truncate();

        $trainingData = [
            // Financial Novice (5 samples)
            ['pendapatan' => 'Sangat Rendah', 'anggaran' => 'Sangat Rendah', 'tabungan_dan_dana_darurat' => 'Sangat Rendah', 'utang' => 'Sangat Tinggi', 'investasi' => 'Tidak Ada', 'asuransi_dan_proteksi' => 'Sangat Rendah', 'tujuan_jangka_panjang' => 'Sangat Rendah', 'cluster' => 'Financial Novice'],
            ['pendapatan' => 'Rendah', 'anggaran' => 'Sangat Rendah', 'tabungan_dan_dana_darurat' => 'Sangat Rendah', 'utang' => 'Tinggi', 'investasi' => 'Tidak Ada', 'asuransi_dan_proteksi' => 'Tidak Ada', 'tujuan_jangka_panjang' => 'Sangat Rendah', 'cluster' => 'Financial Novice'],
            ['pendapatan' => 'Sangat Rendah', 'anggaran' => 'Rendah', 'tabungan_dan_dana_darurat' => 'Rendah', 'utang' => 'Sangat Tinggi', 'investasi' => 'Tidak Ada', 'asuransi_dan_proteksi' => 'Rendah', 'tujuan_jangka_panjang' => 'Sangat Rendah', 'cluster' => 'Financial Novice'],
            ['pendapatan' => 'Rendah', 'anggaran' => 'Rendah', 'tabungan_dan_dana_darurat' => 'Sangat Rendah', 'utang' => 'Tinggi', 'investasi' => 'Tidak Ada', 'asuransi_dan_proteksi' => 'Sangat Rendah', 'tujuan_jangka_panjang' => 'Tidak Ada', 'cluster' => 'Financial Novice'],
            ['pendapatan' => 'Rendah', 'anggaran' => 'Sangat Rendah', 'tabungan_dan_dana_darurat' => 'Rendah', 'utang' => 'Sangat Tinggi', 'investasi' => 'Tidak Ada', 'asuransi_dan_proteksi' => 'Sangat Rendah', 'tujuan_jangka_panjang' => 'Sangat Rendah', 'cluster' => 'Financial Novice'],
            
            // Financial Explorer (6 samples)
            ['pendapatan' => 'Rendah', 'anggaran' => 'Sedang', 'tabungan_dan_dana_darurat' => 'Rendah', 'utang' => 'Sedang', 'investasi' => 'Sangat Rendah', 'asuransi_dan_proteksi' => 'Rendah', 'tujuan_jangka_panjang' => 'Rendah', 'cluster' => 'Financial Explorer'],
            ['pendapatan' => 'Sedang', 'anggaran' => 'Rendah', 'tabungan_dan_dana_darurat' => 'Rendah', 'utang' => 'Sedang', 'investasi' => 'Sangat Rendah', 'asuransi_dan_proteksi' => 'Rendah', 'tujuan_jangka_panjang' => 'Rendah', 'cluster' => 'Financial Explorer'],
            ['pendapatan' => 'Rendah', 'anggaran' => 'Sedang', 'tabungan_dan_dana_darurat' => 'Sedang', 'utang' => 'Tinggi', 'investasi' => 'Rendah', 'asuransi_dan_proteksi' => 'Rendah', 'tujuan_jangka_panjang' => 'Rendah', 'cluster' => 'Financial Explorer'],
            ['pendapatan' => 'Sedang', 'anggaran' => 'Sedang', 'tabungan_dan_dana_darurat' => 'Rendah', 'utang' => 'Sedang', 'investasi' => 'Sangat Rendah', 'asuransi_dan_proteksi' => 'Rendah', 'tujuan_jangka_panjang' => 'Sangat Rendah', 'cluster' => 'Financial Explorer'],
            ['pendapatan' => 'Rendah', 'anggaran' => 'Rendah', 'tabungan_dan_dana_darurat' => 'Sedang', 'utang' => 'Sedang', 'investasi' => 'Rendah', 'asuransi_dan_proteksi' => 'Sangat Rendah', 'tujuan_jangka_panjang' => 'Rendah', 'cluster' => 'Financial Explorer'],
            ['pendapatan' => 'Sedang', 'anggaran' => 'Sedang', 'tabungan_dan_dana_darurat' => 'Rendah', 'utang' => 'Tinggi', 'investasi' => 'Sangat Rendah', 'asuransi_dan_proteksi' => 'Rendah', 'tujuan_jangka_panjang' => 'Rendah', 'cluster' => 'Financial Explorer'],
            
            // Foundation Builder (7 samples)
            ['pendapatan' => 'Sedang', 'anggaran' => 'Tinggi', 'tabungan_dan_dana_darurat' => 'Tinggi', 'utang' => 'Rendah', 'investasi' => 'Sedang', 'asuransi_dan_proteksi' => 'Sedang', 'tujuan_jangka_panjang' => 'Sedang', 'cluster' => 'Foundation Builder'],
            ['pendapatan' => 'Tinggi', 'anggaran' => 'Sedang', 'tabungan_dan_dana_darurat' => 'Tinggi', 'utang' => 'Rendah', 'investasi' => 'Sedang', 'asuransi_dan_proteksi' => 'Sedang', 'tujuan_jangka_panjang' => 'Sedang', 'cluster' => 'Foundation Builder'],
            ['pendapatan' => 'Sedang', 'anggaran' => 'Tinggi', 'tabungan_dan_dana_darurat' => 'Tinggi', 'utang' => 'Rendah', 'investasi' => 'Sedang', 'asuransi_dan_proteksi' => 'Sedang', 'tujuan_jangka_panjang' => 'Tinggi', 'cluster' => 'Foundation Builder'],
            ['pendapatan' => 'Tinggi', 'anggaran' => 'Tinggi', 'tabungan_dan_dana_darurat' => 'Sangat Tinggi', 'utang' => 'Sangat Rendah', 'investasi' => 'Sedang', 'asuransi_dan_proteksi' => 'Tinggi', 'tujuan_jangka_panjang' => 'Sedang', 'cluster' => 'Foundation Builder'],
            ['pendapatan' => 'Sedang', 'anggaran' => 'Sedang', 'tabungan_dan_dana_darurat' => 'Tinggi', 'utang' => 'Rendah', 'investasi' => 'Tinggi', 'asuransi_dan_proteksi' => 'Sedang', 'tujuan_jangka_panjang' => 'Sedang', 'cluster' => 'Foundation Builder'],
            ['pendapatan' => 'Tinggi', 'anggaran' => 'Tinggi', 'tabungan_dan_dana_darurat' => 'Tinggi', 'utang' => 'Rendah', 'investasi' => 'Sedang', 'asuransi_dan_proteksi' => 'Sedang', 'tujuan_jangka_panjang' => 'Tinggi', 'cluster' => 'Foundation Builder'],
            ['pendapatan' => 'Sedang', 'anggaran' => 'Tinggi', 'tabungan_dan_dana_darurat' => 'Sangat Tinggi', 'utang' => 'Rendah', 'investasi' => 'Sedang', 'asuransi_dan_proteksi' => 'Sedang', 'tujuan_jangka_panjang' => 'Sedang', 'cluster' => 'Foundation Builder'],
            
            // Financial Architect (5 samples)
            ['pendapatan' => 'Tinggi', 'anggaran' => 'Sangat Tinggi', 'tabungan_dan_dana_darurat' => 'Sangat Tinggi', 'utang' => 'Sangat Rendah', 'investasi' => 'Tinggi', 'asuransi_dan_proteksi' => 'Tinggi', 'tujuan_jangka_panjang' => 'Tinggi', 'cluster' => 'Financial Architect'],
            ['pendapatan' => 'Sangat Tinggi', 'anggaran' => 'Tinggi', 'tabungan_dan_dana_darurat' => 'Sangat Tinggi', 'utang' => 'Sangat Rendah', 'investasi' => 'Tinggi', 'asuransi_dan_proteksi' => 'Tinggi', 'tujuan_jangka_panjang' => 'Sangat Tinggi', 'cluster' => 'Financial Architect'],
            ['pendapatan' => 'Tinggi', 'anggaran' => 'Sangat Tinggi', 'tabungan_dan_dana_darurat' => 'Sangat Tinggi', 'utang' => 'Sangat Rendah', 'investasi' => 'Sangat Tinggi', 'asuransi_dan_proteksi' => 'Tinggi', 'tujuan_jangka_panjang' => 'Sangat Tinggi', 'cluster' => 'Financial Architect'],
            ['pendapatan' => 'Sangat Tinggi', 'anggaran' => 'Sangat Tinggi', 'tabungan_dan_dana_darurat' => 'Sangat Tinggi', 'utang' => 'Sangat Rendah', 'investasi' => 'Tinggi', 'asuransi_dan_proteksi' => 'Sangat Tinggi', 'tujuan_jangka_panjang' => 'Tinggi', 'cluster' => 'Financial Architect'],
            ['pendapatan' => 'Tinggi', 'anggaran' => 'Tinggi', 'tabungan_dan_dana_darurat' => 'Sangat Tinggi', 'utang' => 'Sangat Rendah', 'investasi' => 'Sangat Tinggi', 'asuransi_dan_proteksi' => 'Tinggi', 'tujuan_jangka_panjang' => 'Sangat Tinggi', 'cluster' => 'Financial Architect'],
            
            // Financial Sage (2 samples)
            ['pendapatan' => 'Sangat Tinggi', 'anggaran' => 'Sangat Tinggi', 'tabungan_dan_dana_darurat' => 'Sangat Tinggi', 'utang' => 'Sangat Rendah', 'investasi' => 'Sangat Tinggi', 'asuransi_dan_proteksi' => 'Sangat Tinggi', 'tujuan_jangka_panjang' => 'Sangat Tinggi', 'cluster' => 'Financial Sage'],
            ['pendapatan' => 'Sangat Tinggi', 'anggaran' => 'Sangat Tinggi', 'tabungan_dan_dana_darurat' => 'Sangat Tinggi', 'utang' => 'Sangat Rendah', 'investasi' => 'Sangat Tinggi', 'asuransi_dan_proteksi' => 'Tinggi', 'tujuan_jangka_panjang' => 'Sangat Tinggi', 'cluster' => 'Financial Sage'],
        ];

        $count = 0;
        foreach ($trainingData as $data) {
            TrainingDataset::create([
                'pendapatan' => $data['pendapatan'],
                'anggaran' => $data['anggaran'],
                'tabungan_dan_dana_darurat' => $data['tabungan_dan_dana_darurat'],
                'utang' => $data['utang'],
                'investasi' => $data['investasi'],
                'asuransi_dan_proteksi' => $data['asuransi_dan_proteksi'],
                'tujuan_jangka_panjang' => $data['tujuan_jangka_panjang'],
                'cluster' => $data['cluster'],
                'is_active' => true,
                'notes' => null
            ]);
            $count++;
        }

        $this->command->info("✅ Seeded {$count} training dataset records");
        
        // Show distribution
        $distribution = TrainingDataset::getDistribution();
        $this->command->info("\n📊 Distribution:");
        foreach ($distribution as $cluster => $count) {
            $this->command->info("   {$cluster}: {$count}");
        }
    }
}
