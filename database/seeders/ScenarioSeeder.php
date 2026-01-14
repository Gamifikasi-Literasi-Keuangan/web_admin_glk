<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScenarioSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        DB::table('scenarios')->insert([

            // =========================
            // PENDAPATAN – UANG BULANAN
            // =========================

            [
                'id' => 'SCN_PENDAPATAN_01',
                'category' => 'Uang Bulanan',
                'title' => 'Gaji Pertama',
                'question' => 'Kamu mendapat gaji pertama Rp2.000.000. Apa yang kamu lakukan?',
                'difficulty' => 1,
                'expected_benefit' => 5,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_PENDAPATAN_02',
                'category' => 'Uang Bulanan',
                'title' => 'Gaji Lembur',
                'question' => 'Kamu mendapat tambahan gaji lembur Rp500.000. Apa yang kamu lakukan?',
                'difficulty' => 2,
                'expected_benefit' => 5,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_PENDAPATAN_03',
                'category' => 'Uang Bulanan',
                'title' => 'Gaji Tetap Bulanan',
                'question' => 'Setiap bulan kamu menerima gaji tetap Rp3.000.000. Bagaimana cara mengalokasikannya?',
                'difficulty' => 3,
                'expected_benefit' => 5,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],


            // =========================
            // PENDAPATAN – Freelance
            // =========================

            [
                'id' => 'SCN_PENDAPATAN_04',
                'category' => 'Freelance',
                'title' => 'Bayaran Freelance',
                'question' => 'Kamu mendapat bayaran freelance Rp1.000.000. Apa yang kamu lakukan?',
                'difficulty' => 1,
                'expected_benefit' => 5,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_PENDAPATAN_05',
                'category' => 'Freelance',
                'title' => 'Pendapatan Freelance Tidak Tetap',
                'question' => 'Pendapatan freelance-mu bulan ini Rp700.000, bulan depan belum tentu ada. Bagaimana cara mengelolanya?',
                'difficulty' => 2,
                'expected_benefit' => 5,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_PENDAPATAN_06',
                'category' => 'Freelance',
                'title' => 'Pendapatan Tambahan dari Freelance',
                'question' => 'Kamu sudah punya gaji tetap, lalu mendapat tambahan Rp1.500.000 dari freelance. Apa yang kamu lakukan dengan tambahan ini?',
                'difficulty' => 3,
                'expected_benefit' => 4,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =========================
            // PENDAPATAN – BEASISWA
            // =========================

            [
                'id' => 'SCN_PENDAPATAN_07',
                'category' => 'Beasiswa',
                'title' => 'Beasiswa Biaya Kuliah',
                'question' => 'Kamu menerima beasiswa Rp4.000.000 untuk biaya kuliah. Apa yang kamu lakukan?',
                'difficulty' => 1,
                'expected_benefit' => 5,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_PENDAPATAN_08',
                'category' => 'Beasiswa',
                'title' => 'Beasiswa Uang Saku',
                'question' => 'Kamu mendapat beasiswa uang saku bulanan Rp1.000.000. Bagaimana kamu mengelolanya?',
                'difficulty' => 2,
                'expected_benefit' => 5,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_PENDAPATAN_09',
                'category' => 'Beasiswa',
                'title' => 'Beasiswa Penelitian',
                'question' => 'Kamu mendapat beasiswa Rp2.000.000 untuk penelitian skripsi. Apa yang kamu lakukan?',
                'difficulty' => 3,
                'expected_benefit' => 4,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // KATEGORI: ANGGARAN
            // SUB-KATEGORI: MAKAN
            // =============================================

            [
                'id' => 'SCN_ANGGARAN_01',
                'category' => 'Makan',
                'title' => 'Jatah Makan Harian',
                'question' => 'Kamu mendapat jatah Rp30.000 per hari untuk makan. Bagaimana cara mengelolanya?',
                'difficulty' => 1,
                'expected_benefit' => 8,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_ANGGARAN_02',
                'category' => 'Makan',
                'title' => 'Budget Makan Bulanan',
                'question' => 'Dari uang saku Rp2.000.000 per bulan, berapa yang kamu anggarkan untuk makan?',
                'difficulty' => 2,
                'expected_benefit' => 9,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_ANGGARAN_03',
                'category' => 'Makan',
                'title' => 'Godaan Nongkrong',
                'question' => 'Kamu sudah menyiapkan Rp500.000/bulan untuk makan. Temanmu sering mengajak nongkrong di kafe. Apa yang kamu lakukan?',
                'difficulty' => 3,
                'expected_benefit' => 7,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // SUB-KATEGORI: TRANSPORT
            // =============================================
            [
                'id' => 'SCN_ANGGARAN_04',
                'category' => 'Transport',
                'title' => 'Transport Kuliah Harian',
                'question' => 'Setiap hari kamu butuh transport untuk kuliah (jarak 7 km). Bagaimana cara mengatur biaya transportasi?',
                'difficulty' => 1,
                'expected_benefit' => 7,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_ANGGARAN_05',
                'category' => 'Transport',
                'title' => 'Alokasi Transport Bulanan',
                'question' => 'Dari uang saku Rp2.000.000 per bulan, kamu harus menganggarkan transport bulanan. Mana pilihanmu?',
                'difficulty' => 2,
                'expected_benefit' => 8,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_ANGGARAN_06',
                'category' => 'Transport',
                'title' => 'Promo Ojek Online',
                'question' => 'Kamu sering mendapat tawaran promo ojek online. Bagaimana sikapmu?',
                'difficulty' => 3,
                'expected_benefit' => 5,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // SUB-KATEGORI: NONGKRONG
            // =============================================
            [
                'id' => 'SCN_ANGGARAN_07',
                'category' => 'Nongkrong',
                'title' => 'Anggaran Sosialisasi',
                'question' => 'Teman-temanmu rutin nongkrong di kafe tiap akhir pekan. Bagaimana kamu mengelola anggarannya?',
                'difficulty' => 1,
                'expected_benefit' => 6,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_ANGGARAN_08',
                'category' => 'Nongkrong',
                'title' => 'Anggaran Habis',
                'question' => 'Temanmu tiba-tiba mengajak nongkrong padahal anggaran bulan ini sudah habis. Apa yang kamu lakukan?',
                'difficulty' => 2,
                'expected_benefit' => 8,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_ANGGARAN_09',
                'category' => 'Nongkrong',
                'title' => 'Pilihan Tempat Nongkrong',
                'question' => 'Kamu dan teman-teman ingin nongkrong. Pilihan tempat beragam. Mana yang kamu pilih?',
                'difficulty' => 3,
                'expected_benefit' => 5,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // KATEGORI: TABUNGAN & DANA DARURAT
            // SUB-KATEGORI: TABUNGAN
            // =============================================
            [
                'id' => 'SCN_TABUNGAN_01',
                'category' => 'Tabungan',
                'title' => 'Mulai Menabung',
                'question' => 'Kamu ingin mulai menabung dari uang saku bulanan Rp2.000.000. Bagaimana caramu?',
                'difficulty' => 1,
                'expected_benefit' => 9, // Habit fundamental "Pay Yourself First"
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_TABUNGAN_02',
                'category' => 'Tabungan',
                'title' => 'Target Beli Laptop',
                'question' => 'Kamu ingin membeli laptop baru seharga Rp6.000.000 dalam 1 tahun. Bagaimana strategimu?',
                'difficulty' => 2,
                'expected_benefit' => 8, // Perencanaan keuangan berbasis tujuan (Goal Setting)
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_TABUNGAN_03',
                'category' => 'Tabungan',
                'title' => 'Mengelola Uang Kaget',
                'question' => 'Kamu punya Rp2.000.000 dari hasil freelance. Bagaimana cara mengelolanya?',
                'difficulty' => 3,
                'expected_benefit' => 7, // Diversifikasi sederhana dan menahan konsumsi impulsif
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // SUB-KATEGORI: DANA DARURAT
            // =============================================
            [
                'id' => 'SCN_TABUNGAN_04',
                'category' => 'Dana Darurat',
                'title' => 'Menyiapkan Dana Darurat',
                'question' => 'Kamu baru mulai kerja dan ingin menyiapkan dana darurat. Apa yang kamu lakukan?',
                'difficulty' => 1,
                'expected_benefit' => 9, // Sangat penting untuk keamanan finansial dasar
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_TABUNGAN_05',
                'category' => 'Dana Darurat',
                'title' => 'Menggunakan Dana Darurat',
                'question' => 'Tiba-tiba motor kamu rusak dan butuh biaya Rp1.000.000. Dari mana kamu ambil uangnya?',
                'difficulty' => 2,
                'expected_benefit' => 10, // Kritis: Mencegah utang buruk saat musibah
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_TABUNGAN_06',
                'category' => 'Dana Darurat',
                'title' => 'Menghitung Target Dana Darurat',
                'question' => 'Pengeluaran rutinmu Rp2.000.000/bulan. Berapa target dana darurat yang perlu disiapkan?',
                'difficulty' => 3,
                'expected_benefit' => 8, // Pemahaman standar keamanan finansial (3-6 bulan)
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // SUB-KATEGORI: DEPOSITO
            // =============================================
            [
                'id' => 'SCN_TABUNGAN_07',
                'category' => 'Deposito',
                'title' => 'Strategi Simpanan',
                'question' => 'Kamu punya tabungan Rp5.000.000. Bagaimana cara menyimpannya?',
                'difficulty' => 1,
                'expected_benefit' => 6, // Optimasi return rendah risiko
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_TABUNGAN_08',
                'category' => 'Deposito',
                'title' => 'Pilihan Tenor Deposito',
                'question' => 'Bank menawarkan deposito dengan tenor 1 bulan, 6 bulan, dan 12 bulan. Mana yang kamu pilih?',
                'difficulty' => 2,
                'expected_benefit' => 5, // Manajemen likuiditas vs return
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_TABUNGAN_09',
                'category' => 'Deposito',
                'title' => 'Keamanan Deposito',
                'question' => 'Kamu ingin menaruh dana di deposito. Apa yang perlu diperhatikan?',
                'difficulty' => 3,
                'expected_benefit' => 9, // Sangat tinggi: Menghindari scam/kehilangan uang (LPS)
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // KATEGORI: UTANG
            // SUB-KATEGORI: PINJAMAN TEMAN
            // =============================================
            [
                'id' => 'SCN_UTANG_01',
                'category' => 'Pinjaman Teman',
                'title' => 'Tawaran Pinjaman Teman',
                'question' => 'Kamu butuh Rp500.000 untuk biaya kuliah mendadak, dan teman menawarkan pinjaman tanpa bunga. Apa yang kamu lakukan?',
                'difficulty' => 1,
                'expected_benefit' => 7,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_UTANG_02',
                'category' => 'Pinjaman Teman',
                'title' => 'Penggunaan Pinjaman',
                'question' => 'Temanmu menawarkan pinjaman Rp300.000. Kamu ingin nongkrong, tapi juga ada kebutuhan kuliah. Apa yang kamu lakukan?',
                'difficulty' => 2,
                'expected_benefit' => 6,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_UTANG_03',
                'category' => 'Pinjaman Teman',
                'title' => 'Etika Pengembalian Utang',
                'question' => 'Kamu meminjam Rp1.000.000 dari teman. Sebulan kemudian, bagaimana caramu mengembalikannya?',
                'difficulty' => 3,
                'expected_benefit' => 8,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // SUB-KATEGORI: PINJAL (PINJAMAN ONLINE)
            // =============================================
            [
                'id' => 'SCN_UTANG_04',
                'category' => 'Pinjol',
                'title' => 'Iklan Pinjol Cepat Cair',
                'question' => 'Kamu melihat iklan pinjol dengan proses 5 menit cair. Apa yang kamu lakukan?',
                'difficulty' => 1,
                'expected_benefit' => 9,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_UTANG_05',
                'category' => 'Pinjol',
                'title' => 'Utang Darurat Medis',
                'question' => 'Kamu butuh dana Rp500.000 untuk biaya berobat, dan tidak ada tabungan. Apa pilihanmu?',
                'difficulty' => 2,
                'expected_benefit' => 9,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_UTANG_06',
                'category' => 'Pinjol',
                'title' => 'Strategi Bayar Pinjol',
                'question' => 'Kamu punya pinjol Rp2.000.000 dengan cicilan Rp500.000/bulan. Bagaimana strategi bayarnya?',
                'difficulty' => 3,
                'expected_benefit' => 10,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // SUB-KATEGORI: PAYLATER
            // =============================================
            [
                'id' => 'SCN_UTANG_07',
                'category' => 'Paylater',
                'title' => 'Keinginan vs Kemampuan',
                'question' => 'Kamu ingin membeli sepatu Rp800.000 padahal gaji masih 2 minggu lagi. Apa pilihanmu?',
                'difficulty' => 1,
                'expected_benefit' => 7,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_UTANG_08',
                'category' => 'Paylater',
                'title' => 'Pelunasan Kartu Kredit',
                'question' => 'Kamu punya tagihan kartu kredit Rp1.000.000. Bagaimana cara membayarnya?',
                'difficulty' => 2,
                'expected_benefit' => 9,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_UTANG_09',
                'category' => 'Paylater',
                'title' => 'Jebakan Diskon Paylater',
                'question' => 'E-commerce menawarkan diskon besar jika memakai paylater. Bagaimana sikapmu?',
                'difficulty' => 3,
                'expected_benefit' => 6,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // KATEGORI: INVESTASI
            // SUB-KATEGORI: REKSADANA
            // =============================================
            [
                'id' => 'SCN_INVESTASI_01',
                'category' => 'Reksadana',
                'title' => 'Modal Investasi Pertama',
                'question' => 'Kamu punya Rp500.000 pertama untuk investasi. Apa yang kamu lakukan?',
                'difficulty' => 1,
                'expected_benefit' => 8, // Penting: Langkah awal memulai investasi (barrier to entry).
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_INVESTASI_02',
                'category' => 'Reksadana',
                'title' => 'Investasi Jangka Pendek',
                'question' => 'Kamu ingin menabung untuk liburan 1 tahun lagi. Pilihan reksadana apa yang kamu ambil?',
                'difficulty' => 2,
                'expected_benefit' => 7, // Menyesuaikan instrumen dengan time horizon.
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_INVESTASI_03',
                'category' => 'Reksadana',
                'title' => 'Diversifikasi Reksadana',
                'question' => 'Kamu sudah punya tabungan Rp5.000.000. Ingin mencoba reksadana, bagaimana strategimu?',
                'difficulty' => 3,
                'expected_benefit' => 9, // Sangat Penting: Konsep Diversifikasi Aset.
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // SUB-KATEGORI: SAHAM
            // =============================================
            [
                'id' => 'SCN_INVESTASI_04',
                'category' => 'Saham',
                'title' => 'Langkah Awal Saham',
                'question' => 'Kamu punya Rp1.000.000 dan ingin mulai berinvestasi saham. Apa langkah pertama?',
                'difficulty' => 1,
                'expected_benefit' => 8, // Fundamental: Memilih saham bluechip vs spekulasi.
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_INVESTASI_05',
                'category' => 'Saham',
                'title' => 'Strategi Jangka Panjang',
                'question' => 'Kamu ingin dana pendidikan 5 tahun ke depan. Bagaimana strategi investasimu di saham?',
                'difficulty' => 2,
                'expected_benefit' => 9, // Strategi Dollar Cost Averaging (DCA).
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_INVESTASI_06',
                'category' => 'Saham',
                'title' => 'Psikologi Market Crash',
                'question' => 'Harga saham yang kamu beli turun 20% dalam 1 bulan, Apa reaksimu?',
                'difficulty' => 3,
                'expected_benefit' => 9, // Mentalitas investor: Tidak panik jual rugi (Cut loss tanpa analisa).
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // SUB-KATEGORI: CRYPTOCURRENCY
            // =============================================
            [
                'id' => 'SCN_INVESTASI_07',
                'category' => 'Cryptocurrency',
                'title' => 'Memulai Crypto',
                'question' => 'Kamu punya Rp1.000.000 dan ingin mencoba cryptocurrency. Apa langkahmu?',
                'difficulty' => 1,
                'expected_benefit' => 7, // Edukasi aset berisiko tinggi (High Risk).
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_INVESTASI_08',
                'category' => 'Cryptocurrency',
                'title' => 'Volatilitas Crypto',
                'question' => 'Harga crypto yang kamu beli turun 30% dalam sebulan. Apa reaksimu?',
                'difficulty' => 2,
                'expected_benefit' => 8, // Manajemen emosi pada aset volatil.
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_INVESTASI_09',
                'category' => 'Cryptocurrency',
                'title' => 'Keamanan Aset Digital',
                'question' => 'Kamu menyimpan crypto di aplikasi exchange. Bagaimana cara terbaik melindunginya?',
                'difficulty' => 3,
                'expected_benefit' => 9, // Keamanan siber finansial (Wallet security).
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // KATEGORI: ASURANSI
            // SUB-KATEGORI: ASURANSI KESEHATAN
            // =============================================
            [
                'id' => 'SCN_ASURANSI_01',
                'category' => 'Asuransi Kesehatan',
                'title' => 'Pendaftaran BPJS',
                'question' => 'Kamu baru bekerja dan diminta memilih apakah mau daftar BPJS Kesehatan. Apa keputusanmu?',
                'difficulty' => 1,
                'expected_benefit' => 10, // Sangat Kritis: Proteksi kesehatan dasar adalah wajib.
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_ASURANSI_02',
                'category' => 'Asuransi Kesehatan',
                'title' => 'Klaim Pengobatan',
                'question' => 'Kamu sakit dan butuh berobat. Apa yang kamu lakukan dengan BPJS?',
                'difficulty' => 2,
                'expected_benefit' => 8, // Efisiensi pengeluaran medis.
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_ASURANSI_03',
                'category' => 'Asuransi Kesehatan',
                'title' => 'Tunggakan Iuran',
                'question' => 'Kamu lupa bayar iuran BPJS selama 3 bulan. Apa yang kamu lakukan?',
                'difficulty' => 3,
                'expected_benefit' => 9, // Kepatuhan dan manajemen risiko aktif.
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // SUB-KATEGORI: ASURANSI KENDARAAN
            // =============================================
            [
                'id' => 'SCN_ASURANSI_04',
                'category' => 'Asuransi Kendaraan',
                'title' => 'Proteksi Kendaraan Cicilan',
                'question' => 'Kamu baru membeli motor dengan cicilan bulanan. Dealer menawarkan asuransi kendaraan. Apa keputusanmu?',
                'difficulty' => 1,
                'expected_benefit' => 8, // Proteksi aset kredit.
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_ASURANSI_05',
                'category' => 'Asuransi Kendaraan',
                'title' => 'Asuransi Kendaraan Produktif',
                'question' => 'Kamu menggunakan motor untuk kerja part-time sebagai driver ojek online. Bagaimana sikapmu soal asuransi kendaraan?',
                'difficulty' => 2,
                'expected_benefit' => 9, // Sangat Tinggi: Melindungi sumber penghasilan.
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_ASURANSI_06',
                'category' => 'Asuransi Kendaraan',
                'title' => 'Risiko Parkir',
                'question' => 'Kamu sering parkir motor di pinggir jalan kampus. Apa langkah terbaik?',
                'difficulty' => 3,
                'expected_benefit' => 7, // Pemilihan jenis asuransi yang tepat (TLO vs All Risk).
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // SUB-KATEGORI: ASURANSI BARANG/HARTA
            // =============================================
            [
                'id' => 'SCN_ASURANSI_07',
                'category' => 'Asuransi Barang/Harta',
                'title' => 'Asuransi Gadget',
                'question' => 'Kamu membeli laptop seharga Rp10 juta untuk kuliah. Ada tawaran asuransi gadget. Apa keputusanmu?',
                'difficulty' => 1,
                'expected_benefit' => 7, // Proteksi alat produktif (kuliah/kerja).
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_ASURANSI_08',
                'category' => 'Asuransi Barang/Harta',
                'title' => 'Kehilangan HP',
                'question' => 'HP kamu hilang saat naik transport umum. Bagaimana mengantisipasi risiko ini ke depannya?',
                'difficulty' => 2,
                'expected_benefit' => 6, // Manajemen risiko barang pribadi.
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_ASURANSI_09',
                'category' => 'Asuransi Barang/Harta',
                'title' => 'Risiko Kebakaran Kos',
                'question' => 'Ada kasus kos-kosan di dekatmu kebakaran. Bagaimana kamu melindungi barang-barang berhargamu?',
                'difficulty' => 3,
                'expected_benefit' => 8, // Mitigasi bencana/kerugian total.
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // KATEGORI: TUJUAN JANGKA PANJANG
            // SUB-KATEGORI: PENDIDIKAN
            // =============================================
            [
                'id' => 'SCN_TUJUAN_01',
                'category' => 'Pendidikan',
                'title' => 'Tabungan Skripsi',
                'question' => 'Dalam 1 tahun kamu butuh Rp5 juta untuk biaya skripsi. Apa strategi menabungmu?',
                'difficulty' => 1,
                'expected_benefit' => 10,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_TUJUAN_02',
                'category' => 'Pendidikan',
                'title' => 'Biaya Kursus Karir',
                'question' => 'Kamu ingin ikut kursus digital marketing Rp2 juta untuk menunjang karier. Apa langkahmu?',
                'difficulty' => 2,
                'expected_benefit' => 8,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_TUJUAN_03',
                'category' => 'Pendidikan',
                'title' => 'Rencana Studi Lanjut',
                'question' => 'Kamu berencana melanjutkan S2 dalam 3 tahun dengan biaya Rp60 juta. Apa rencanamu?',
                'difficulty' => 3,
                'expected_benefit' => 9,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // SUB-KATEGORI: PENGALAMAN
            // =============================================
            [
                'id' => 'SCN_TUJUAN_04',
                'category' => 'Pengalaman',
                'title' => 'Persiapan Study Tour',
                'question' => 'Biaya study tour kampus Rp3 juta, sedangkan masih ada 6 bulan untuk persiapan. Apa langkahmu?',
                'difficulty' => 1,
                'expected_benefit' => 7,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_TUJUAN_05',
                'category' => 'Pengalaman',
                'title' => 'Program Exchange',
                'question' => 'Kamu lolos program exchange 1 semester ke luar negeri dengan biaya pribadi Rp20 juta. Apa yang kamu lakukan?',
                'difficulty' => 2,
                'expected_benefit' => 9,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_TUJUAN_06',
                'category' => 'Pengalaman',
                'title' => 'Liburan Bersama Teman',
                'question' => 'Kamu diajak teman liburan ke Bali dengan estimasi biaya Rp4 juta. Bagaimana menyikapinya?',
                'difficulty' => 3,
                'expected_benefit' => 6,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],

            // =============================================
            // SUB-KATEGORI: ASET PRODUKTIF
            // =============================================
            [
                'id' => 'SCN_TUJUAN_07',
                'category' => 'Aset Produktif',
                'title' => 'Laptop untuk Freelance',
                'question' => 'Kamu ingin beli laptop Rp8 juta agar bisa ambil lebih banyak proyek freelance. Apa rencanamu?',
                'difficulty' => 1,
                'expected_benefit' => 9,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_TUJUAN_08',
                'category' => 'Aset Produktif',
                'title' => 'Kamera untuk Konten',
                'question' => 'Kamu ingin kamera Rp12 juta untuk membuat konten YouTube. Apa langkahmu?',
                'difficulty' => 2,
                'expected_benefit' => 8,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
            [
                'id' => 'SCN_TUJUAN_09',
                'category' => 'Aset Produktif',
                'title' => 'Modal Usaha Kecil',
                'question' => 'Kamu dan temanmu ingin buka usaha kecil dengan modal Rp5 juta. Apa pilihanmu?',
                'difficulty' => 3,
                'expected_benefit' => 8,
                'learning_objective' => null,
                'weak_area_relevance' => null,
                'cluster_relevance' => null,
                'historical_success_rate' => 0.5,
                'created_at' => $now,
            ],
        ]);
    }
}
