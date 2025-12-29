<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        $quizzes = [
            // SOAL 1
            [
                'card' => [
                    'id' => 'QUIZ_01',
                    'question' => 'Apa fungsi utama dana darurat?',
                    'correctOption' => 'A',
                    'correctScore' => 6,
                    'incorrectScore' => -3,
                    'tags' => NULL,
                    'difficulty' => 1,
                    'learning_objective' => 'Memahami fungsi fundamental dana darurat.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Untuk kebutuhan mendesak'],
                    ['optionId' => 'B', 'text' => 'Untuk investasi saham'],
                    ['optionId' => 'C', 'text' => 'Untuk liburan'],
                ]
            ],
            // SOAL 2
            [
                'card' => [
                    'id' => 'QUIZ_02',
                    'question' => 'Jika ingin menabung untuk tujuan 1 tahun, instrumen apa yang paling tepat?',
                    'correctOption' => 'A',
                    'correctScore' => 7,
                    'incorrectScore' => -4,
                    'tags' => NULL,
                    'difficulty' => 2,
                    'learning_objective' => 'Memilih instrumen investasi sesuai jangka waktu (Time Horizon).',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Reksadana Pasar Uang'],
                    ['optionId' => 'B', 'text' => 'Saham'],
                    ['optionId' => 'C', 'text' => 'Kripto'],
                ]
            ],
            // SOAL 3
            [
                'card' => [
                    'id' => 'QUIZ_03',
                    'question' => 'Apa arti prinsip "pay yourself first"?',
                    'correctOption' => 'A',
                    'correctScore' => 6,
                    'incorrectScore' => -3,
                    'tags' => NULL,
                    'difficulty' => 1,
                    'learning_objective' => 'Memahami prioritas menabung dalam cashflow.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Menyisihkan tabungan sebelum belanja'],
                    ['optionId' => 'B', 'text' => 'Bayar utang dulu baru menabung'],
                    ['optionId' => 'C', 'text' => 'Belanja dulu baru menabung'],
                ]
            ],
            // SOAL 4
            [
                'card' => [
                    'id' => 'QUIZ_04',
                    'question' => 'Apa risiko utama memakai pinjol ilegal?',
                    'correctOption' => 'C',
                    'correctScore' => 7,
                    'incorrectScore' => -5,
                    'tags' => NULL,
                    'difficulty' => 1,
                    'learning_objective' => 'Mengidentifikasi bahaya pinjaman online ilegal.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Bebas bunga'],
                    ['optionId' => 'B', 'text' => 'Legalitas terjamin'],
                    ['optionId' => 'C', 'text' => 'Bunga tinggi & teror penagihan'],
                ]
            ],
            // SOAL 5
            [
                'card' => [
                    'id' => 'QUIZ_05',
                    'question' => 'Kalau ingin investasi jangka panjang (5 tahun+), instrumen apa yang cocok?',
                    'correctOption' => 'A',
                    'correctScore' => 6,
                    'incorrectScore' => -3,
                    'tags' => NULL,
                    'difficulty' => 2,
                    'learning_objective' => 'Memilih aset agresif untuk tujuan jangka panjang.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Saham/Reksadana Saham'],
                    ['optionId' => 'B', 'text' => 'Tabungan biasa'],
                    ['optionId' => 'C', 'text' => 'Pinjol'],
                ]
            ],
            // SOAL 6
            [
                'card' => [
                    'id' => 'QUIZ_06',
                    'question' => 'Idealnya, dana darurat adalah...',
                    'correctOption' => 'B',
                    'correctScore' => 7,
                    'incorrectScore' => -4,
                    'tags' => NULL,
                    'difficulty' => 1,
                    'learning_objective' => 'Mengetahui jumlah ideal dana darurat.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => '1 bulan pengeluaran'],
                    ['optionId' => 'B', 'text' => '3-6 bulan pengeluaran'],
                    ['optionId' => 'C', 'text' => '12 bulan pengeluaran'],
                ]
            ],
            // SOAL 7
            [
                'card' => [
                    'id' => 'QUIZ_07',
                    'question' => 'Apa yang harus dilakukan agar tagihan kartu kredit tidak berbunga?',
                    'correctOption' => 'A',
                    'correctScore' => 6,
                    'incorrectScore' => -3,
                    'tags' => NULL,
                    'difficulty' => 2,
                    'learning_objective' => 'Memahami cara kerja bunga kartu kredit.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Bayar penuh sebelum jatuh tempo'],
                    ['optionId' => 'B', 'text' => 'Bayar minimum payment'],
                    ['optionId' => 'C', 'text' => 'Abaikan tagihan'],
                ]
            ],
            // SOAL 8
            [
                'card' => [
                    'id' => 'QUIZ_08',
                    'question' => 'Apa manfaat asuransi kesehatan (BPJS)?',
                    'correctOption' => 'B',
                    'correctScore' => 5,
                    'incorrectScore' => -2,
                    'tags' => NULL,
                    'difficulty' => 1,
                    'learning_objective' => 'Memahami fungsi proteksi dari asuransi.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Menambah tabungan'],
                    ['optionId' => 'B', 'text' => 'Proteksi biaya berobat'],
                    ['optionId' => 'C', 'text' => 'Memberi cashback'],
                ]
            ],
            // SOAL 9
            [
                'card' => [
                    'id' => 'QUIZ_09',
                    'question' => 'Apa arti diversifikasi investasi?',
                    'correctOption' => 'A',
                    'correctScore' => 6,
                    'incorrectScore' => -3,
                    'tags' => NULL,
                    'difficulty' => 2,
                    'learning_objective' => 'Memahami konsep penyebaran risiko (Don\'t put all eggs in one basket).',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Sebar dana di berbagai instrumen'],
                    ['optionId' => 'B', 'text' => 'Taruh semua uang di 1 saham'],
                    ['optionId' => 'C', 'text' => 'Simpan uang tunai saja'],
                ]
            ],
            // SOAL 10
            [
                'card' => [
                    'id' => 'QUIZ_10',
                    'question' => 'Apa akibat impulse buying di e-commerce?',
                    'correctOption' => 'A',
                    'correctScore' => 5,
                    'incorrectScore' => -2,
                    'tags' => NULL,
                    'difficulty' => 1,
                    'learning_objective' => 'Dampak perilaku konsumtif terhadap kesehatan finansial.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Uang cepat habis untuk barang tak penting'],
                    ['optionId' => 'B', 'text' => 'Membuat literasi keuangan meningkat'],
                    ['optionId' => 'C', 'text' => 'Tabungan cepat bertambah'],
                ]
            ],
            // SOAL 11
            [
                'card' => [
                    'id' => 'QUIZ_11',
                    'question' => 'Jika inflasi 5% per tahun dan tabunganmu berbunga 3% per tahun, apa yang terjadi?',
                    'correctOption' => 'A',
                    'correctScore' => 7,
                    'incorrectScore' => -4,
                    'tags' => NULL,
                    'difficulty' => 2,
                    'learning_objective' => 'Memahami dampak inflasi terhadap nilai riil uang.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Nilai uangmu berkurang'],
                    ['optionId' => 'B', 'text' => 'Nilai uangmu meningkat'],
                    ['optionId' => 'C', 'text' => 'Nilai uangmu tetap sama'],
                ]
            ],
            // SOAL 12
            [
                'card' => [
                    'id' => 'QUIZ_12',
                    'question' => 'Apa perbedaan utama reksadana saham dan obligasi?',
                    'correctOption' => 'A',
                    'correctScore' => 6,
                    'incorrectScore' => -3,
                    'tags' => NULL,
                    'difficulty' => 2,
                    'learning_objective' => 'Membedakan karakteristik risiko dan return antar instrumen.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Saham untuk jangka panjang, obligasi lebih stabil'],
                    ['optionId' => 'B', 'text' => 'Obligasi lebih berisiko dari saham'],
                    ['optionId' => 'C', 'text' => 'Saham pasti untung, obligasi tidak'],
                ]
            ],
            // SOAL 13
            [
                'card' => [
                    'id' => 'QUIZ_13',
                    'question' => 'Jika kamu berutang Rp1.000.000 dengan bunga 2% flat per bulan selama 6 bulan, berapa total bunganya?',
                    'correctOption' => 'B',
                    'correctScore' => 7,
                    'incorrectScore' => -4,
                    'tags' => NULL,
                    'difficulty' => 2,
                    'learning_objective' => 'Menghitung biaya bunga sederhana (flat rate).',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Rp60.000'],
                    ['optionId' => 'B', 'text' => 'Rp120.000'], 
                    ['optionId' => 'C', 'text' => 'Rp240.000'],
                ]
            ],
            // SOAL 14
            [
                'card' => [
                    'id' => 'QUIZ_14',
                    'question' => 'Apa tujuan diversifikasi portofolio investasi?',
                    'correctOption' => 'A',
                    'correctScore' => 6,
                    'incorrectScore' => -3,
                    'tags' => NULL,
                    'difficulty' => 1,
                    'learning_objective' => 'Tujuan utama manajemen risiko investasi.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Meminimalkan risiko'],
                    ['optionId' => 'B', 'text' => 'Memaksimalkan utang'],
                    ['optionId' => 'C', 'text' => 'Mengurangi tabungan'],
                ]
            ],
            // SOAL 15
            [
                'card' => [
                    'id' => 'QUIZ_15',
                    'question' => 'Apa arti prinsip "time value of money"?',
                    'correctOption' => 'A',
                    'correctScore' => 7,
                    'incorrectScore' => -4,
                    'tags' => NULL,
                    'difficulty' => 2,
                    'learning_objective' => 'Memahami nilai waktu dari uang (Present Value vs Future Value).',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Uang hari ini lebih bernilai daripada jumlah yang sama di masa depan'],
                    ['optionId' => 'B', 'text' => 'Uang besok lebih bernilai daripada uang hari ini'],
                    ['optionId' => 'C', 'text' => 'Nilai uang tidak pernah berubah'],
                ]
            ],
            // SOAL 16
            [
                'card' => [
                    'id' => 'QUIZ_16',
                    'question' => 'Jika gaji Rp3 juta dan kamu ingin menerapkan aturan 50/30/20, berapa alokasi untuk Tabungan/Investasi (20%)?',
                    'correctOption' => 'C',
                    'correctScore' => 6,
                    'incorrectScore' => -3,
                    'tags' => NULL,
                    'difficulty' => 2,
                    'learning_objective' => 'Penerapan rumus budgeting 50/30/20.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Rp1.500.000'], // Kebutuhan (50%)
                    ['optionId' => 'B', 'text' => 'Rp900.000'],   // Keinginan (30%)
                    ['optionId' => 'C', 'text' => 'Rp600.000'],   // Tabungan (20%)
                ]
            ],
            // SOAL 17
            [
                'card' => [
                    'id' => 'QUIZ_17',
                    'question' => 'Apa perbedaan utama deposito dan tabungan biasa?',
                    'correctOption' => 'C',
                    'correctScore' => 6,
                    'incorrectScore' => -3,
                    'tags' => NULL,
                    'difficulty' => 1,
                    'learning_objective' => 'Karakteristik produk perbankan simpanan berjangka.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Deposito lebih fleksibel, bunga kecil'],
                    ['optionId' => 'B', 'text' => 'Tabungan tidak bisa diambil'],
                    ['optionId' => 'C', 'text' => 'Deposito bunga lebih tinggi, tapi dana tertahan jangka waktu tertentu'],
                ]
            ],
            // SOAL 18
            [
                'card' => [
                    'id' => 'QUIZ_18',
                    'question' => 'Apa konsekuensi jika skor kredit seseorang buruk?',
                    'correctOption' => 'B',
                    'correctScore' => 5,
                    'incorrectScore' => -2,
                    'tags' => NULL,
                    'difficulty' => 1,
                    'learning_objective' => 'Dampak riwayat kredit buruk (SLIK OJK).',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Tabungan otomatis hangus'],
                    ['optionId' => 'B', 'text' => 'Sulit mendapat pinjaman di bank'],
                    ['optionId' => 'C', 'text' => 'Tidak boleh investasi saham'],
                ]
            ],
            // SOAL 19
            [
                'card' => [
                    'id' => 'QUIZ_19',
                    'question' => 'Dalam investasi, apa arti istilah "high risk high return"?',
                    'correctOption' => 'B',
                    'correctScore' => 7,
                    'incorrectScore' => -4,
                    'tags' => NULL,
                    'difficulty' => 1,
                    'learning_objective' => 'Hubungan linear antara risiko dan potensi keuntungan.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Risiko rendah pasti hasil tinggi'],
                    ['optionId' => 'B', 'text' => 'Risiko tinggi berpotensi memberikan hasil tinggi (tapi juga rugi besar)'],
                    ['optionId' => 'C', 'text' => 'Tidak ada hubungan risiko dan hasil'],
                ]
            ],
            // SOAL 20
            [
                'card' => [
                    'id' => 'QUIZ_20',
                    'question' => 'Apa langkah terbaik jika ingin memulai investasi sejak mahasiswa?',
                    'correctOption' => 'B',
                    'correctScore' => 6,
                    'incorrectScore' => -3,
                    'tags' => NULL,
                    'difficulty' => 1,
                    'learning_objective' => 'Strategi investasi untuk modal terbatas.',
                    'weak_area_relevance' => null,
                    'cluster_relevance' => null,
                    'historical_success_rate' => 0.5,
                    'created_at' => $now,
                ],
                'options' => [
                    ['optionId' => 'A', 'text' => 'Gunakan uang darurat untuk investasi'],
                    ['optionId' => 'B', 'text' => 'Mulai dari nominal kecil di instrumen risiko rendah/moderat'],
                    ['optionId' => 'C', 'text' => 'Tunggu sampai punya gaji besar'],
                ]
            ],
        ];

        // Looping untuk insert data
        foreach ($quizzes as $quiz) {
            // Insert ke tabel quiz_cards
            DB::table('quiz_cards')->insert($quiz['card']);

            // Insert ke tabel quiz_options
            foreach ($quiz['options'] as $option) {
                DB::table('quiz_options')->insert([
                    'quizId' => $quiz['card']['id'],
                    'optionId' => $option['optionId'],
                    'text' => $option['text'],
                ]);
            }
        }
    }
}