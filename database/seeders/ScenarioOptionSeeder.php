<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScenarioOptionSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🌱 Seeding Scenario Options...');

        DB::table('scenario_options')->insert([
            // =============================================
            // SCENARIO 01: Gaji Pertama
            // =============================================
            [
                'scenarioId' => 'SCN_PENDAPATAN_01',
                'optionId' => 'A',
                'text' => 'Simpan 50% untuk tabungan/dana darurat',
                'scoreChange' => json_encode(['pendapatan' => 5, 'tabungan_dan_dana_darurat' => 3]),
                'response' => 'Bagus! Menyisihkan sebagian besar gaji untuk dana darurat adalah langkah dasar yang sehat.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_01',
                'optionId' => 'B',
                'text' => 'Belanja 80% untuk gaya hidup',
                'scoreChange' => json_encode(['pendapatan' => -3, 'anggaran' => -2]),
                'response' => 'Hati-hati, kalau belanja terlalu besar bisa bikin kamu kewalahan saat ada kebutuhan mendesak. Idealnya, lifestyle cukup seperlunya.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_01',
                'optionId' => 'C',
                'text' => 'Beli saham dengan seluruh gaji',
                'scoreChange' => json_encode(['pendapatan' => -1, 'investasi' => 2]),
                'response' => 'Investasi itu bagus, tapi jangan sampai menghabiskan seluruh gaji. Tanpa dana darurat, investasimu bisa berisiko.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO 02: Bonus Lembur
            // =============================================
            [
                'scenarioId' => 'SCN_PENDAPATAN_02',
                'optionId' => 'A',
                'text' => 'Menabung seluruh bonus lembur',
                'scoreChange' => json_encode(['pendapatan' => 5, 'tabungan_dan_dana_darurat' => 3]),
                'response' => 'Luar biasa! Dengan menyimpan tambahan gaji, tujuan finansialmu bisa tercapai lebih cepat.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_02',
                'optionId' => 'B',
                'text' => 'Menggunakan bonus untuk liburan singkat',
                'scoreChange' => json_encode(['pendapatan' => 1, 'anggaran' => 1]),
                'response' => 'Tidak masalah sesekali memberi reward untuk diri sendiri, asal tetap menyisihkan sebagian untuk tabungan.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_02',
                'optionId' => 'C',
                'text' => 'Membayar cicilan/utang kecil yang tertunggak',
                'scoreChange' => json_encode(['pendapatan' => 5, 'utang' => -5]),
                'response' => 'Bagus sekali! Menggunakan bonus untuk melunasi utang akan mengurangi beban bunga dan memberi ruang lega di masa depan.',
                'is_correct' => true,
            ],

            // =============================================
            // SCENARIO 03: Alokasi Gaji Tetap (50-30-20)
            // =============================================
            [
                'scenarioId' => 'SCN_PENDAPATAN_03',
                'optionId' => 'A',
                'text' => '50% kebutuhan pokok, 30% tabungan/investasi, 20% hiburan',
                'scoreChange' => json_encode(['pendapatan' => 5, 'anggaran' => 5]),
                'response' => 'Sangat baik! Rule 50-30-20 membantu menjaga keseimbangan antara kebutuhan, tabungan, dan hiburan.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_03',
                'optionId' => 'B',
                'text' => '70% kebutuhan pokok, 20% hiburan, 10% tabungan',
                'scoreChange' => json_encode(['pendapatan' => 1, 'anggaran' => 2]),
                'response' => 'Cukup realistis, tapi porsi tabunganmu masih terlalu kecil. Cobalah menaikkannya sedikit demi kesehatan keuangan jangka panjang.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_03',
                'optionId' => 'C',
                'text' => '80% tabungan/investasi, 20% kebutuhan pokok',
                'scoreChange' => json_encode(['pendapatan' => 2, 'anggaran' => -1]),
                'response' => 'Ambisi menabung itu bagus, tapi jangan sampai kebutuhan pokokmu terabaikan. Literasi keuangan menekankan keseimbangan.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO 04: Bayaran Freelance
            // =============================================
            [
                'scenarioId' => 'SCN_PENDAPATAN_04',
                'optionId' => 'A',
                'text' => 'Sisihkan 40% untuk tabungan, 40% untuk kebutuhan, 20% untuk hiburan',
                'scoreChange' => json_encode(['pendapatan' => 5, 'anggaran' => 3]),
                'response' => 'Bagus! Kamu sudah membagi penghasilan dengan seimbang. Ini melatih disiplin keuangan sejak dini.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_04',
                'optionId' => 'B',
                'text' => 'Gunakan semua untuk membeli gadget baru',
                'scoreChange' => json_encode(['pendapatan' => -3, 'anggaran' => -3]),
                'response' => 'Hati-hati, kalau seluruh penghasilan habis untuk konsumsi, kamu tidak punya cadangan saat darurat.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_04',
                'optionId' => 'C',
                'text' => 'Sisihkan seluruhnya untuk modal usaha kecil berikutnya',
                'scoreChange' => json_encode(['pendapatan' => 2, 'investasi' => 2]),
                'response' => 'Investasi ke modal usaha memang baik, tapi jangan gunakan 100% penghasilan. Simpan sebagian agar tetap ada cadangan.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO 05: Pendapatan Freelance Tidak Tetap
            // =============================================
            [
                'scenarioId' => 'SCN_PENDAPATAN_05',
                'optionId' => 'A',
                'text' => 'Simpan sebagian besar (60%) sebagai dana cadangan',
                'scoreChange' => json_encode(['pendapatan' => 5, 'tabungan_dan_dana_darurat' => 4]),
                'response' => 'Sangat baik! Kalau penghasilanmu tidak tetap, prioritas pertama adalah tabungan. Literasi keuangan selalu mengingatkan agar bersiap.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_05',
                'optionId' => 'B',
                'text' => 'Habiskan dulu karena bulan depan pasti ada proyek baru lagi',
                'scoreChange' => json_encode(['pendapatan' => -2, 'anggaran' => -3]),
                'response' => 'Berbahaya kalau kamu menganggap penghasilan tidak tetap sebagai jaminan pasti. Pola pikir seperti ini berisiko.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_05',
                'optionId' => 'C',
                'text' => 'Alokasikan 30% untuk hiburan, 30% untuk kebutuhan pokok, 40% untuk tabungan',
                'scoreChange' => json_encode(['pendapatan' => 3, 'anggaran' => 3, 'tabungan_dan_dana_darurat' => 2]),
                'response' => 'Cukup baik. Kamu masih bisa menikmati hasil kerja sambil menabung cukup besar.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO 06: Gaji Tetap & Tambahan Freelance
            // =============================================
            [
                'scenarioId' => 'SCN_PENDAPATAN_06',
                'optionId' => 'A',
                'text' => 'Gunakan untuk investasi jangka panjang (reksadana/saham)',
                'scoreChange' => json_encode(['pendapatan' => 5, 'investasi' => 3]),
                'response' => 'Bagus! Menggunakan penghasilan tambahan untuk investasi akan mempercepat tercapainya tujuan keuangan.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_06',
                'optionId' => 'B',
                'text' => 'Habiskan untuk liburan bersama teman-teman',
                'scoreChange' => json_encode(['pendapatan' => -2, 'anggaran' => -2]),
                'response' => 'Tidak masalah memberi reward sesekali, tapi kalau habis semua tanpa sisa, kesempatan membangun aset pun hilang.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_06',
                'optionId' => 'C',
                'text' => 'Simpan setengahnya, sisanya untuk hiburan',
                'scoreChange' => json_encode(['pendapatan' => 3, 'anggaran' => 2, 'tabungan_dan_dana_darurat' => 2]),
                'response' => 'Pilihan seimbang. Kamu bisa tetap menikmati hasil kerja sambil menabung sebagian.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO 07: Dana Beasiswa Kuliah
            // =============================================
            [
                'scenarioId' => 'SCN_PENDAPATAN_07',
                'optionId' => 'A',
                'text' => 'Gunakan sesuai tujuan: bayar biaya kuliah, sisanya ditabung',
                'scoreChange' => json_encode(['pendapatan' => 5, 'tabungan_dan_dana_darurat' => 3]),
                'response' => 'Bagus! Beasiswa pendidikan memang harus diprioritaskan untuk kuliah. Menyimpan sisanya menunjukkan kedisiplinan.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_07',
                'optionId' => 'B',
                'text' => 'Gunakan sebagian untuk biaya kuliah, sebagian besar untuk belanja keperluan pribadi',
                'scoreChange' => json_encode(['pendapatan' => -2, 'anggaran' => -2]),
                'response' => 'Hati-hati. Kalau beasiswa tidak dipakai untuk tujuan utama, bisa menyulitkanmu di semester berikutnya.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_07',
                'optionId' => 'C',
                'text' => 'Tunda pembayaran kuliah dan gunakan dulu untuk kebutuhan lain',
                'scoreChange' => json_encode(['pendapatan' => -4, 'anggaran' => -3]),
                'response' => 'Ini berisiko besar. Kalau biaya kuliah belum dibayar, kamu bisa kena denda atau kehilangan hak akademik.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO 08: Uang Saku Beasiswa
            // =============================================
            [
                'scenarioId' => 'SCN_PENDAPATAN_08',
                'optionId' => 'A',
                'text' => 'Alokasikan untuk kebutuhan pokok (makan, transport), sisanya tabung',
                'scoreChange' => json_encode(['pendapatan' => 5, 'anggaran' => 3, 'tabungan_dan_dana_darurat' => 3]),
                'response' => 'Sangat baik! Kamu memanfaatkan beasiswa untuk kebutuhan pokok sekaligus menyisihkan untuk tabungan.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_08',
                'optionId' => 'B',
                'text' => 'Habiskan untuk nongkrong dan hiburan, kebutuhan pokok ditanggung orang tua',
                'scoreChange' => json_encode(['pendapatan' => -3, 'anggaran' => -3]),
                'response' => 'Kurang tepat kalau beasiswa hanya dihabiskan untuk hiburan. Dana itu bisa lebih bijak digunakan.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_08',
                'optionId' => 'C',
                'text' => 'Gunakan semua untuk bayar kos selama 2 bulan ke depan',
                'scoreChange' => json_encode(['pendapatan' => 2, 'anggaran' => 2]),
                'response' => 'Cukup baik. Membayar kos lebih awal aman, tapi jangan lupa siapkan cadangan untuk kebutuhan lain.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO 09: Dana Penelitian Skripsi
            // =============================================
            [
                'scenarioId' => 'SCN_PENDAPATAN_09',
                'optionId' => 'A',
                'text' => 'Gunakan untuk kebutuhan riset (buku, kuisioner, perjalanan penelitian)',
                'scoreChange' => json_encode(['pendapatan' => 5, 'literasi_keuangan' => 3]),
                'response' => 'Bagus! Menggunakan dana sesuai tujuan akan mendukung kelancaran riset. Literasi keuangan mengajarkan pentingnya integritas.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_09',
                'optionId' => 'B',
                'text' => 'Gunakan sebagian kecil untuk penelitian, sebagian besar untuk belanja barang keinginan',
                'scoreChange' => json_encode(['pendapatan' => -2, 'anggaran' => -3]),
                'response' => 'Kurang tepat. Menyalahgunakan dana beasiswa bisa berakibat serius, termasuk menghambat penyelesaian skripsi.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_PENDAPATAN_09',
                'optionId' => 'C',
                'text' => 'Simpan dulu seluruhnya di tabungan karena penelitian belum dimulai',
                'scoreChange' => json_encode(['pendapatan' => 3, 'tabungan_dan_dana_darurat' => 2]),
                'response' => 'Baik. Menyimpan dana sementara itu aman, asal pada akhirnya benar-benar dipakai sesuai jadwal penelitian.',
                'is_correct' => false,
            ],
            // =============================================
            // SCENARIO (Anggaran): Jatah Makan Harian
            // =============================================
            [
                'scenarioId' => 'SCN_ANGGARAN_01',
                'optionId' => 'A',
                'text' => 'Membagi Rp30.000 untuk 3 kali makan sederhana',
                'scoreChange' => json_encode(['anggaran' => 5, 'literasi_keuangan' => 3]),
                'response' => 'Bagus! Cara ini realistis, menjaga kesehatan dan keuangan tetap seimbang. Literasi keuangan mendorong disiplin anggaran.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_01',
                'optionId' => 'B',
                'text' => 'Sekali makan di kafe habis Rp30.000, sisanya tidak makan',
                'scoreChange' => json_encode(['anggaran' => -3, 'literasi_keuangan' => -2]),
                'response' => 'Kalau sekali makan di kafe langsung habis Rp30.000 lalu sisanya tidak makan, itu kurang sehat. Prioritaskan kebutuhan dasar.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_01',
                'optionId' => 'C',
                'text' => 'Beli mie instan dalam jumlah banyak untuk 1 minggu',
                'scoreChange' => json_encode(['anggaran' => 1, 'literasi_keuangan' => 1]),
                'response' => 'Hemat memang baik, tapi kalau sampai mengorbankan gizi, itu tidak sehat. Literasi keuangan mengingatkan pentingnya keseimbangan.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Anggaran): Budget Makan Bulanan
            // =============================================
            [
                'scenarioId' => 'SCN_ANGGARAN_02',
                'optionId' => 'A',
                'text' => 'Rp500.000 (sekitar 25-30%) dengan pola makan teratur dan sehat',
                'scoreChange' => json_encode(['anggaran' => 5, 'literasi_keuangan' => 3]),
                'response' => 'Bagus! Alokasi 25-30% gaji untuk makan adalah proporsi yang sehat dan realistis.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_02',
                'optionId' => 'B',
                'text' => 'Rp1.200.000 (60%) untuk makan tiap hari',
                'scoreChange' => json_encode(['anggaran' => -3, 'tabungan_dan_dana_darurat' => -2]),
                'response' => 'Kalau sampai 60% gaji habis untuk makan, tabungan dan kebutuhan lain bisa tergerus. Kurangi pemborosan pangan.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_02',
                'optionId' => 'C',
                'text' => 'Rp300.000 (15%) hanya untuk makan seadanya',
                'scoreChange' => json_encode(['anggaran' => -1, 'literasi_keuangan' => -1]),
                'response' => 'Hemat itu baik, tapi jangan sampai kualitas makanan mengganggu kesehatan. Anggaran harus tetap realistis.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Anggaran): Godaan Nongkrong
            // =============================================
            [
                'scenarioId' => 'SCN_ANGGARAN_03',
                'optionId' => 'A',
                'text' => 'Tolak semua ajakan nongkrong agar tidak melebihi anggaran',
                'scoreChange' => json_encode(['anggaran' => 1, 'literasi_keuangan' => 1]),
                'response' => 'Hemat yang terlalu kaku bisa membuat relasi sosial terganggu. Literasi keuangan mengajarkan keseimbangan.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_03',
                'optionId' => 'B',
                'text' => 'Batasi nongkrong seminggu sekali, sisanya makan hemat sesuai anggaran',
                'scoreChange' => json_encode(['anggaran' => 5, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Kamu masih bisa bersosialisasi tanpa mengganggu anggaran utama. Ini bentuk literasi keuangan yang sehat.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_03',
                'optionId' => 'C',
                'text' => 'Ikut nongkrong setiap kali diajak meski anggaran makan membengkak',
                'scoreChange' => json_encode(['anggaran' => -4, 'literasi_keuangan' => -3]),
                'response' => 'Gaya hidup sosial memang penting, tapi jangan sampai mengganggu stabilitas keuangan. Ini bisa merusak cashflow.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Anggaran): Transport Harian
            // =============================================
            [
                'scenarioId' => 'SCN_ANGGARAN_04',
                'optionId' => 'A',
                'text' => 'Naik ojek online setiap hari, biaya sekitar Rp25.000 sekali jalan',
                'scoreChange' => json_encode(['anggaran' => -2, 'literasi_keuangan' => -1]),
                'response' => 'Nyaman sih, tapi kalau semua penghasilan habis untuk transport, anggaranmu bisa jebol.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_04',
                'optionId' => 'B',
                'text' => 'Naik angkot/bus umum, biaya Rp5.000 sekali jalan',
                'scoreChange' => json_encode(['anggaran' => 4, 'tabungan_dan_dana_darurat' => 2]),
                'response' => 'Pilihan hemat dan aman. Dengan transport umum, sisa uang bisa masuk tabungan.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_04',
                'optionId' => 'C',
                'text' => 'Jalan kaki atau bersepeda jika cuaca mendukung, naik angkot jika hujan',
                'scoreChange' => json_encode(['anggaran' => 5, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Kombinasi hemat, sehat, dan tetap fleksibel sesuai kondisi adalah contoh keuangan cerdas.',
                'is_correct' => true,
            ],

            // =============================================
            // SCENARIO (Anggaran): Alokasi Transport Bulanan
            // =============================================
            [
                'scenarioId' => 'SCN_ANGGARAN_05',
                'optionId' => 'A',
                'text' => 'Sisihkan Rp1.000.000 untuk bensin motor pribadi',
                'scoreChange' => json_encode(['anggaran' => 1, 'literasi_keuangan' => 1]),
                'response' => 'Kalau biaya motor pribadi sampai besar, bensin dan perawatan bisa menggerus penghasilanmu.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_05',
                'optionId' => 'B',
                'text' => 'Anggarkan Rp400.000 untuk transport umum (bus/kartu e-money)',
                'scoreChange' => json_encode(['anggaran' => 3, 'literasi_keuangan' => 2]),
                'response' => 'Transport umum memang lebih murah, meski kurang fleksibel. Itu tetap pilihan cerdas dalam literasi keuangan.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_05',
                'optionId' => 'C',
                'text' => 'Gunakan Rp700.000 untuk transport campuran (motor & transport umum)',
                'scoreChange' => json_encode(['anggaran' => 5, 'literasi_keuangan' => 3]),
                'response' => 'Pilihan seimbang. Kamu bisa fleksibel dengan motor, tapi tetap hemat dengan transport umum.',
                'is_correct' => true,
            ],

            // =============================================
            // SCENARIO (Anggaran): Promo Ojek Online
            // =============================================
            [
                'scenarioId' => 'SCN_ANGGARAN_06',
                'optionId' => 'A',
                'text' => 'Pakai ojek online setiap hari',
                'scoreChange' => json_encode(['anggaran' => -3, 'literasi_keuangan' => -2]),
                'response' => 'Tidak bijak kalau transport dipakai berlebihan, karena bisa membengkak jauh di atas anggaran.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_06',
                'optionId' => 'B',
                'text' => 'Gunakan promo hanya saat mendesak (hujan, barang bawaan berat)',
                'scoreChange' => json_encode(['anggaran' => 5, 'literasi_keuangan' => 4]),
                'response' => 'Sangat baik! Memanfaatkan promo secara bijak bisa menekan biaya tanpa mengorbankan kualitas hidup.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_06',
                'optionId' => 'C',
                'text' => 'Selalu berburu promo ojek online meski harus memutar jauh demi diskon',
                'scoreChange' => json_encode(['anggaran' => 2, 'literasi_keuangan' => 2]),
                'response' => 'Kreatif sih, tapi kalau waktu dan tenaga habis hanya demi promo, itu tidak efisien. Literasi keuangan menekankan efisiensi.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Anggaran): Anggaran Sosialisasi
            // =============================================
            [
                'scenarioId' => 'SCN_ANGGARAN_07',
                'optionId' => 'A',
                'text' => 'Ikut setiap minggu tanpa menghitung anggaran, habis Rp500.000 per bulan',
                'scoreChange' => json_encode(['anggaran' => -3, 'tabungan_dan_dana_darurat' => -2]),
                'response' => 'Kurang bijak kalau pengeluaran sosial lebih besar daripada tabungan. Itu bisa membahayakan kesehatan finansial.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_07',
                'optionId' => 'B',
                'text' => 'Batasi nongkrong maksimal 2 kali sebulan, anggaran Rp200.000',
                'scoreChange' => json_encode(['anggaran' => 5, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Kamu bisa menjaga relasi sosial sambil tetap mengontrol anggaran.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_07',
                'optionId' => 'C',
                'text' => 'Tidak pernah nongkrong sama sekali untuk menghemat',
                'scoreChange' => json_encode(['anggaran' => 2, 'literasi_keuangan' => 1]),
                'response' => 'Hemat memang baik, tapi jangan lupa relasi sosial juga penting. Cari keseimbangan yang lebih sehat.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Anggaran): Anggaran Habis
            // =============================================
            [
                'scenarioId' => 'SCN_ANGGARAN_08',
                'optionId' => 'A',
                'text' => 'Pinjam uang teman/paylater agar tetap ikut karena hubungan sosial itu penting',
                'scoreChange' => json_encode(['anggaran' => -3, 'utang' => -3]),
                'response' => 'Berbahaya kalau nongkrong harus pakai utang. Itu bisa merusak keuangan jangka panjang.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_08',
                'optionId' => 'B',
                'text' => 'Menolak dengan sopan karena anggaran sudah habis',
                'scoreChange' => json_encode(['anggaran' => 5, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Kamu berani bilang tidak demi menjaga anggaran. Itu disiplin keuangan yang patut ditiru.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_08',
                'optionId' => 'C',
                'text' => 'Ikut nongkrong tapi hanya pesan minum kecil agar hemat',
                'scoreChange' => json_encode(['anggaran' => 3, 'literasi_keuangan' => 2]),
                'response' => 'Cukup bijak. Kamu masih bisa menjaga hubungan sosial tanpa mengganggu anggaran besar.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Anggaran): Pilihan Tempat Nongkrong
            // =============================================
            [
                'scenarioId' => 'SCN_ANGGARAN_09',
                'optionId' => 'A',
                'text' => 'Kafe hits dengan harga Rp100.000 sekali nongkrong',
                'scoreChange' => json_encode(['anggaran' => -1, 'literasi_keuangan' => -1]),
                'response' => 'Boleh sesekali, tapi kalau jadi kebiasaan bisa menguras keuangan.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_09',
                'optionId' => 'B',
                'text' => 'Warung kopi sederhana Rp20.000 sekali nongkrong',
                'scoreChange' => json_encode(['anggaran' => 4, 'literasi_keuangan' => 3]),
                'response' => 'Pilihan hemat tapi tetap bisa bersosialisasi. Cocok untuk menjaga anggaran.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ANGGARAN_09',
                'optionId' => 'C',
                'text' => 'Alternatif kumpul di rumah teman dengan biaya patungan Rp10.000',
                'scoreChange' => json_encode(['anggaran' => 5, 'literasi_keuangan' => 4]),
                'response' => 'Ide cerdas! Nongkrong hemat tetap bisa seru, akrab, dan menyenangkan tanpa merusak anggaran.',
                'is_correct' => true,
            ],

            // =============================================
            // SCENARIO (Tabungan): Mulai Menabung
            // =============================================
            [
                'scenarioId' => 'SCN_TABUNGAN_01',
                'optionId' => 'A',
                'text' => 'Menabung Rp100.000 setiap minggu secara konsisten',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 5, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Menabung rutin meski kecil akan membentuk kebiasaan positif. Dalam literasi keuangan, prinsipnya pay yourself first.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_01',
                'optionId' => 'B',
                'text' => 'Menabung sisa uang kalau ada yang tersisa di akhir bulan',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 3, 'literasi_keuangan' => 1]),
                'response' => 'Kurang tepat. Kalau menunggu sisa, biasanya tidak ada yang tersisa. Literasi keuangan mengajarkan bahwa menabung harus di awal.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_01',
                'optionId' => 'C',
                'text' => 'Menabung Rp500.000 langsung di awal bulan, meski agak ketat untuk kebutuhan harian',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 2, 'anggaran' => -1]),
                'response' => 'Ambisi menabung besar itu bagus, tapi jangan sampai kebutuhan pokok tidak terpenuhi. Literasi keuangan menekankan keseimbangan.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Tabungan): Target Beli Laptop
            // =============================================
            [
                'scenarioId' => 'SCN_TABUNGAN_02',
                'optionId' => 'A',
                'text' => 'Menabung Rp200.000 tiap bulan',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => -2, 'literasi_keuangan' => -1]),
                'response' => 'Terlalu kecil. Dengan jumlah itu, butuh 2,5 tahun untuk mencapai tujuan. Literasi keuangan menganjurkan target menabung yang realistis.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_02',
                'optionId' => 'B',
                'text' => 'Menabung Rp500.000 tiap bulan di rekening khusus',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 5, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Dengan cara ini, target bisa tercapai tepat waktu. Menurut literasi keuangan, tabungan berjangka membantu melatih komitmen.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_02',
                'optionId' => 'C',
                'text' => 'Menabung Rp1.000.000 di awal, lalu tidak rutin di bulan berikutnya',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 1, 'literasi_keuangan' => -1]),
                'response' => 'Kurang konsisten. Lebih baik sedikit tapi rutin. Literasi keuangan mengajarkan bahwa konsistensi lebih penting daripada nominal sesaat.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Tabungan): Mengelola Uang Kaget
            // =============================================
            [
                'scenarioId' => 'SCN_TABUNGAN_03',
                'optionId' => 'A',
                'text' => 'Menabung seluruh Rp2.000.000 di rekening tabungan biasa',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 2, 'literasi_keuangan' => 1]),
                'response' => 'Aman, tapi return-nya kecil. Menurut literasi keuangan, tabungan memang likuid tapi nilai uang bisa tergerus inflasi.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_03',
                'optionId' => 'B',
                'text' => 'Menyimpan Rp1.000.000 di tabungan, Rp1.000.000 di reksadana pasar uang',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 5, 'investasi' => 3, 'literasi_keuangan' => 4]),
                'response' => 'Bagus sekali! Kamu menggabungkan keamanan tabungan dengan potensi return. Literasi keuangan menyebut ini diversifikasi.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_03',
                'optionId' => 'C',
                'text' => 'Gunakan seluruh Rp2.000.000 untuk jajan karena dianggap uang kecil',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => -4, 'anggaran' => -3, 'literasi_keuangan' => -4]),
                'response' => 'Sangat berisiko. Literasi keuangan mengingatkan, perilaku impulse spending membuatmu gagal membangun tabungan.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Dana Darurat): Menyiapkan Dana Darurat
            // =============================================
            [
                'scenarioId' => 'SCN_TABUNGAN_04',
                'optionId' => 'A',
                'text' => 'Sisihkan 10% gaji tiap bulan hingga terkumpul 3x pengeluaran bulanan',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 5, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Ini sesuai prinsip literasi keuangan: dana darurat idealnya 3-6 bulan pengeluaran. Kamu melatih disiplin jangka panjang.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_04',
                'optionId' => 'B',
                'text' => 'Menyimpan Rp100.000 saja setiap kali ingat',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => -2, 'literasi_keuangan' => -2]),
                'response' => 'Kurang konsisten. Literasi keuangan menekankan bahwa dana darurat butuh target dan disiplin, bukan hanya menabung saat ingat.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_04',
                'optionId' => 'C',
                'text' => 'Menyimpan setengah gaji langsung meski kebutuhan pokok jadi ketat',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 2, 'anggaran' => -2]),
                'response' => 'Ambisi besar itu bagus, tapi tidak realistis jika kebutuhan pokok terganggu. Literasi keuangan mengajarkan keseimbangan.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Dana Darurat): Menggunakan Dana Darurat
            // =============================================
            [
                'scenarioId' => 'SCN_TABUNGAN_05',
                'optionId' => 'A',
                'text' => 'Ambil dari tabungan dana darurat',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 5, 'literasi_keuangan' => 4]),
                'response' => 'Tepat! Memakai dana darurat sesuai fungsinya. Literasi keuangan menekankan dana ini dipakai hanya untuk kondisi mendesak.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_05',
                'optionId' => 'B',
                'text' => 'Pinjam dari teman karena tabungan dipakai untuk jalan-jalan',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => -3, 'utang' => -3, 'literasi_keuangan' => -3]),
                'response' => 'Kurang bijak. Jika dana darurat tidak tersedia, kamu jadi bergantung pada utang. Literasi keuangan menekankan pentingnya prioritas.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_05',
                'optionId' => 'C',
                'text' => 'Gesek kartu kredit dengan cicilan panjang',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 1, 'utang' => -2]),
                'response' => 'Boleh sesekali, tapi berisiko. Literasi keuangan menekankan dana darurat harus lebih dulu ada agar tidak terjebak bunga tinggi.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Dana Darurat): Target Dana Darurat
            // =============================================
            [
                'scenarioId' => 'SCN_TABUNGAN_06',
                'optionId' => 'A',
                'text' => 'Rp2.000.000 (1 bulan pengeluaran)',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 1, 'literasi_keuangan' => -1]),
                'response' => 'Terlalu kecil. Literasi keuangan menyarankan minimal 3-6 bulan pengeluaran agar lebih aman.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_06',
                'optionId' => 'B',
                'text' => 'Rp6.000.000 (3 bulan pengeluaran)',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 5, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Ini sesuai standar literasi keuangan: dana darurat minimal 3 bulan, ideal hingga 6 bulan.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_06',
                'optionId' => 'C',
                'text' => 'Rp12.000.000 (6 bulan pengeluaran)',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 4, 'literasi_keuangan' => 4]),
                'response' => 'Sangat baik! Ini standar ideal menurut literasi keuangan. Semakin besar cadangan, semakin aman dari risiko.',
                'is_correct' => true,
            ],

            // =============================================
            // SCENARIO (Deposito): Strategi Simpanan
            // =============================================
            [
                'scenarioId' => 'SCN_TABUNGAN_07',
                'optionId' => 'A',
                'text' => 'Menaruh seluruhnya di tabungan biasa agar mudah diambil kapan saja',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 1, 'investasi' => -1]),
                'response' => 'Aman, tapi kurang optimal. Literasi keuangan menekankan deposito memberi bunga lebih tinggi meski kurang likuid.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_07',
                'optionId' => 'B',
                'text' => 'Membuka deposito Rp3.000.000, sisanya di tabungan biasa',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 5, 'investasi' => 3, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Kamu mengombinasikan likuiditas tabungan dengan keuntungan deposito. Literasi keuangan menyebut ini diversifikasi.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_07',
                'optionId' => 'C',
                'text' => 'Menaruh semua di deposito 12 bulan',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 2, 'investasi' => 2]),
                'response' => 'Return lebih tinggi, tapi kurang fleksibel kalau ada kebutuhan darurat. Literasi keuangan mengingatkan pentingnya dana likuid.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Deposito): Pilihan Tenor
            // =============================================
            [
                'scenarioId' => 'SCN_TABUNGAN_08',
                'optionId' => 'A',
                'text' => 'Deposito 1 bulan',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 1, 'investasi' => 1]),
                'response' => 'Fleksibel, tapi bunganya kecil. Literasi keuangan mengajarkan semakin panjang tenor, semakin besar bunga.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_08',
                'optionId' => 'B',
                'text' => 'Deposito 6 bulan',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 4, 'investasi' => 3, 'literasi_keuangan' => 3]),
                'response' => 'Bagus! Bunga lebih tinggi dibanding 1 bulan, dan masih cukup fleksibel. Literasi keuangan menyarankan tenor menengah.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_08',
                'optionId' => 'C',
                'text' => 'Deposito 12 bulan',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 5, 'investasi' => 4, 'literasi_keuangan' => 3]),
                'response' => 'Sangat baik untuk return maksimal. Namun, literasi keuangan menekankan pentingnya menyiapkan dana likuid di luar deposito.',
                'is_correct' => true,
            ],

            // =============================================
            // SCENARIO (Deposito): Keamanan
            // =============================================
            [
                'scenarioId' => 'SCN_TABUNGAN_09',
                'optionId' => 'A',
                'text' => 'Hanya fokus pada bunga tinggi tanpa lihat reputasi bank',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => -4, 'investasi' => -4, 'literasi_keuangan' => -5]),
                'response' => 'Berbahaya. Literasi keuangan menekankan keamanan lembaga keuangan lebih penting daripada iming-iming bunga.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_09',
                'optionId' => 'B',
                'text' => 'Memilih bank resmi yang dijamin LPS (Lembaga Penjamin Simpanan)',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => 5, 'investasi' => 4, 'literasi_keuangan' => 5]),
                'response' => 'Bagus sekali! Literasi keuangan mengingatkan bahwa deposito di bank yang dijamin LPS lebih aman hingga batas tertentu.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_TABUNGAN_09',
                'optionId' => 'C',
                'text' => 'Menaruh deposito di lembaga keuangan tidak resmi karena bunga sangat tinggi',
                'scoreChange' => json_encode(['tabungan_dan_dana_darurat' => -5, 'investasi' => -5, 'literasi_keuangan' => -5]),
                'response' => 'Sangat berisiko. Literasi keuangan menyebut ini sebagai praktik investasi bodong/berisiko tinggi yang bisa hilang sewaktu-waktu.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Utang): Tawaran Pinjaman Teman
            // =============================================
            [
                'scenarioId' => 'SCN_UTANG_01',
                'optionId' => 'A',
                'text' => 'Terima pinjaman dan catat jadwal pengembalian',
                'scoreChange' => json_encode(['utang' => 5, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Transparansi penting agar tidak menimbulkan konflik. Literasi keuangan menekankan utang teman harus diperlakukan profesional[cite: 2].',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_UTANG_01',
                'optionId' => 'B',
                'text' => 'Pinjam tanpa memberi kepastian kapan akan mengembalikan',
                'scoreChange' => json_encode(['utang' => -3, 'literasi_keuangan' => -3]),
                'response' => 'Kurang tepat. Literasi keuangan mengingatkan bahwa tanpa komitmen, hubungan pertemanan bisa rusak[cite: 2].',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_UTANG_01',
                'optionId' => 'C',
                'text' => 'Menolak pinjaman meski akhirnya terpaksa berutang ke pinjol berbunga tinggi',
                'scoreChange' => json_encode(['utang' => -1, 'literasi_keuangan' => -2]),
                'response' => 'Berisiko. Literasi keuangan menyarankan memanfaatkan pinjaman yang aman (soft loan) terlebih dahulu sebelum mengambil utang berbunga tinggi[cite: 2].',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Utang): Penggunaan Pinjaman
            // =============================================
            [
                'scenarioId' => 'SCN_UTANG_02',
                'optionId' => 'A',
                'text' => 'Pinjam lalu gunakan semua untuk nongkrong',
                'scoreChange' => json_encode(['utang' => -4, 'anggaran' => -4, 'literasi_keuangan' => -4]),
                'response' => 'Tidak bijak. Literasi keuangan menekankan utang sebaiknya hanya untuk kebutuhan penting, bukan gaya hidup[cite: 2].',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_UTANG_02',
                'optionId' => 'B',
                'text' => 'Pinjam dan gunakan untuk membeli buku kuliah, lalu kembalikan tepat waktu',
                'scoreChange' => json_encode(['utang' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus! Menggunakan pinjaman untuk kebutuhan produktif sejalan dengan prinsip literasi keuangan[cite: 2].',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_UTANG_02',
                'optionId' => 'C',
                'text' => 'Pinjam, gunakan separuh untuk kuliah dan separuh untuk hiburan',
                'scoreChange' => json_encode(['utang' => 1, 'anggaran' => -1, 'literasi_keuangan' => 1]),
                'response' => 'Cukup, tapi kurang fokus. Literasi keuangan menyarankan prioritas penuh pada kebutuhan produktif jika sedang berutang[cite: 2].',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Utang): Etika Pengembalian Utang
            // =============================================
            [
                'scenarioId' => 'SCN_UTANG_03',
                'optionId' => 'A',
                'text' => 'Mengembalikan tepat waktu sesuai janji',
                'scoreChange' => json_encode(['utang' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus! Ini menjaga kepercayaan. Literasi keuangan menekankan bahwa disiplin mengembalikan utang menjaga reputasi[cite: 2].',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_UTANG_03',
                'optionId' => 'B',
                'text' => 'Mengulur waktu dengan alasan pribadi tanpa kepastian',
                'scoreChange' => json_encode(['utang' => -2, 'literasi_keuangan' => -3]),
                'response' => 'Kurang tepat. Literasi keuangan menekankan reputasi keuangan dibangun dari konsistensi janji[cite: 2].',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_UTANG_03',
                'optionId' => 'C',
                'text' => 'Mengembalikan sebagian dulu, sisanya dicicil dengan komunikasi yang jelas',
                'scoreChange' => json_encode(['utang' => 3, 'literasi_keuangan' => 3]),
                'response' => 'Cukup baik. Literasi keuangan mengajarkan bahwa komunikasi terbuka soal cicilan bisa mencegah konflik[cite: 2].',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Pinjal): Iklan Pinjol Cepat Cair
            // =============================================
            [
                'scenarioId' => 'SCN_UTANG_04',
                'optionId' => 'A',
                'text' => 'Langsung ajukan pinjaman Rp1.000.000 untuk belanja online',
                'scoreChange' => json_encode(['utang' => -5, 'anggaran' => -4, 'literasi_keuangan' => -5]),
                'response' => 'Kurang bijak. Literasi keuangan menekankan pinjol sebaiknya tidak dipakai untuk konsumtif karena bunganya tinggi[cite: 2].',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_UTANG_04',
                'optionId' => 'B',
                'text' => 'Cek legalitas pinjol di OJK sebelum memutuskan',
                'scoreChange' => json_encode(['utang' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus! Literasi keuangan mengingatkan bahwa hanya pinjol legal yang diawasi OJK yang aman digunakan[cite: 2].',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_UTANG_04',
                'optionId' => 'C',
                'text' => 'Abaikan iklan karena belum ada kebutuhan mendesak',
                'scoreChange' => json_encode(['utang' => 4, 'literasi_keuangan' => 4]),
                'response' => 'Cerdas! Literasi keuangan menekankan jangan ambil pinjaman kalau tidak ada kebutuhan penting[cite: 2].',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Pinjal): Utang Darurat Medis
            // =============================================
            [
                'scenarioId' => 'SCN_UTANG_05',
                'optionId' => 'A',
                'text' => 'Mengajukan pinjaman di pinjol legal dengan tenor singkat dan segera dilunasi',
                'scoreChange' => json_encode(['utang' => 4, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Literasi keuangan menyebut pinjal boleh digunakan darurat, tapi harus segera dilunasi agar bunga tidak menumpuk[cite: 2].',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_UTANG_05',
                'optionId' => 'B',
                'text' => 'Meminjam dari pinjol ilegal karena lebih cepat tanpa syarat',
                'scoreChange' => json_encode(['utang' => -5, 'literasi_keuangan' => -5]),
                'response' => 'Sangat berisiko. Literasi keuangan mengingatkan pinjol ilegal bisa memicu teror penagihan dan bunga mencekik[cite: 2].',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_UTANG_05',
                'optionId' => 'C',
                'text' => 'Menunda biaya berobat karena takut berutang',
                'scoreChange' => json_encode(['utang' => -1, 'literasi_keuangan' => -2]),
                'response' => 'Kurang tepat. Literasi keuangan menekankan kesehatan adalah prioritas, utang boleh diambil asal diatur dengan bijak[cite: 2].',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Pinjal): Strategi Bayar Pinjol
            // =============================================
            [
                'scenarioId' => 'SCN_UTANG_06',
                'optionId' => 'A',
                'text' => 'Bayar cicilan tepat waktu setiap bulan',
                'scoreChange' => json_encode(['utang' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus! Literasi keuangan menekankan disiplin bayar utang akan menjaga skor kredit dan menghindari denda[cite: 2].',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_UTANG_06',
                'optionId' => 'B',
                'text' => 'Bayar setengah dulu, sisanya tunggu gaji berikutnya',
                'scoreChange' => json_encode(['utang' => 1, 'anggaran' => -1, 'literasi_keuangan' => 1]),
                'response' => 'Cukup, tapi ada risiko denda. Literasi keuangan menyarankan selalu siapkan anggaran khusus cicilan[cite: 2].',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_UTANG_06',
                'optionId' => 'C',
                'text' => 'Menunda bayar karena merasa tidak ada konsekuensi',
                'scoreChange' => json_encode(['utang' => -4, 'literasi_keuangan' => -4]),
                'response' => 'Berbahaya. Literasi keuangan menegaskan penundaan cicilan membuat bunga menumpuk dan bisa merusak reputasi kredit[cite: 2].',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Paylater): Keinginan vs Kemampuan
            // =============================================
            [
                'scenarioId' => 'SCN_UTANG_07',
                'optionId' => 'A',
                'text' => 'Gunakan paylater, tapi sudah menyiapkan uang untuk melunasi saat jatuh tempo',
                'scoreChange' => json_encode(['utang' => 4, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Literasi keuangan menekankan paylater boleh dipakai asal ada dana pasti untuk melunasi tepat waktu[cite: 2].',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_UTANG_07',
                'optionId' => 'B',
                'text' => 'Gunakan paylater tanpa kepastian sumber dana untuk bayar',
                'scoreChange' => json_encode(['utang' => -4, 'anggaran' => -3, 'literasi_keuangan' => -4]),
                'response' => 'Berisiko. Literasi keuangan mengingatkan paylater bisa memicu beban bunga dan denda jika tidak disiplin[cite: 2].',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_UTANG_07',
                'optionId' => 'C',
                'text' => 'Menunda membeli sampai gajian tiba',
                'scoreChange' => json_encode(['utang' => 5, 'anggaran' => 4, 'literasi_keuangan' => 5]),
                'response' => 'Pilihan terbaik! Literasi keuangan menekankan menunda konsumsi lebih sehat daripada bergantung pada utang konsumtif[cite: 2].',
                'is_correct' => true,
            ],

            // =============================================
            // SCENARIO (Paylater): Pelunasan Kartu Kredit
            // =============================================
            [
                'scenarioId' => 'SCN_UTANG_08',
                'optionId' => 'A',
                'text' => 'Membayar minimum payment Rp100.000 saja',
                'scoreChange' => json_encode(['utang' => 1, 'literasi_keuangan' => -1]),
                'response' => 'Kurang tepat. Literasi keuangan menjelaskan minimum payment hanya menunda pokok utang dan membuat bunga terus berjalan[cite: 2].',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_UTANG_08',
                'optionId' => 'B',
                'text' => 'Membayar penuh Rp1.000.000 sebelum jatuh tempo',
                'scoreChange' => json_encode(['utang' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus sekali! Literasi keuangan menekankan bayar penuh kartu kredit agar bebas bunga[cite: 2].',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_UTANG_08',
                'optionId' => 'C',
                'text' => 'Membayar sebagian Rp500.000 lalu sisanya bulan depan',
                'scoreChange' => json_encode(['utang' => 1, 'literasi_keuangan' => 1]),
                'response' => 'Cukup, tapi ada bunga berjalan. Literasi keuangan menyarankan pelunasan penuh untuk menghindari beban tambahan[cite: 2].',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Paylater): Jebakan Diskon
            // =============================================
            [
                'scenarioId' => 'SCN_UTANG_09',
                'optionId' => 'A',
                'text' => 'Pakai paylater agar dapat diskon, meski harus mencicil 3 bulan',
                'scoreChange' => json_encode(['utang' => -2, 'anggaran' => -2, 'literasi_keuangan' => -2]),
                'response' => 'Tidak bijak jika barangnya bukan kebutuhan. Literasi keuangan mengingatkan jangan terjebak promo konsumtif[cite: 2].',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_UTANG_09',
                'optionId' => 'B',
                'text' => 'Pakai paylater hanya jika barang itu kebutuhan penting dan dana sudah siap',
                'scoreChange' => json_encode(['utang' => 3, 'anggaran' => 3, 'literasi_keuangan' => 3]),
                'response' => 'Cukup bijak! Literasi keuangan menyebut paylater bisa membantu cashflow jika dikelola dengan disiplin[cite: 2].',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_UTANG_09',
                'optionId' => 'C',
                'text' => 'Abaikan promo karena tidak ada kebutuhan mendesak',
                'scoreChange' => json_encode(['utang' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus! Literasi keuangan mengajarkan promo hanya bermanfaat kalau memang sesuai kebutuhan, bukan sekadar godaan[cite: 2].',
                'is_correct' => true,
            ],

            // =============================================
            // SCENARIO (Reksadana): Modal Investasi Pertama
            // =============================================
            [
                'scenarioId' => 'SCN_INVESTASI_01',
                'optionId' => 'A',
                'text' => 'Investasikan ke reksadana saham karena return tinggi',
                'scoreChange' => json_encode(['investasi' => 1, 'literasi_keuangan' => -1]),
                'response' => 'Ambisi bagus, tapi kurang tepat untuk pemula. Literasi keuangan menekankan profil risiko harus sesuai dengan instrumen.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_01',
                'optionId' => 'B',
                'text' => 'Investasikan ke reksadana pasar uang untuk belajar dulu',
                'scoreChange' => json_encode(['investasi' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus! Literasi keuangan menyebut reksadana pasar uang cocok untuk pemula karena risikonya rendah dan lebih aman.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_01',
                'optionId' => 'C',
                'text' => 'Simpan uangnya di tabungan biasa karena takut rugi',
                'scoreChange' => json_encode(['investasi' => 1, 'tabungan_dan_dana_darurat' => 2, 'literasi_keuangan' => -1]),
                'response' => 'Cukup aman, tapi nilai uang bisa tergerus inflasi. Literasi keuangan menekankan tabungan bukan instrumen investasi.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Reksadana): Investasi Jangka Pendek
            // =============================================
            [
                'scenarioId' => 'SCN_INVESTASI_02',
                'optionId' => 'A',
                'text' => 'Reksadana saham',
                'scoreChange' => json_encode(['investasi' => -3, 'literasi_keuangan' => -3]),
                'response' => 'Kurang tepat. Literasi keuangan menjelaskan reksadana saham cocok untuk tujuan jangka panjang (lebih dari 3 tahun), bukan setahun.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_02',
                'optionId' => 'B',
                'text' => 'Reksadana pasar uang',
                'scoreChange' => json_encode(['investasi' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus sekali! Literasi keuangan menekankan untuk tujuan jangka pendek, reksadana pasar uang lebih aman.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_02',
                'optionId' => 'C',
                'text' => 'Reksadana campuran',
                'scoreChange' => json_encode(['investasi' => 2, 'literasi_keuangan' => 2]),
                'response' => 'Cukup baik. Literasi keuangan menyebut reksadana campuran lebih cocok untuk tujuan menengah (1-3 tahun).',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Reksadana): Diversifikasi
            // =============================================
            [
                'scenarioId' => 'SCN_INVESTASI_03',
                'optionId' => 'A',
                'text' => 'Investasikan seluruh Rp5.000.000 ke 1 jenis reksadana saham',
                'scoreChange' => json_encode(['investasi' => -2, 'literasi_keuangan' => -2]),
                'response' => 'Berisiko tinggi. Literasi keuangan mengajarkan pentingnya diversifikasi agar risiko tidak terkonsentrasi di satu tempat.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_03',
                'optionId' => 'B',
                'text' => 'Bagi Rp5.000.000 ke beberapa jenis reksadana (pasar uang, obligasi, saham)',
                'scoreChange' => json_encode(['investasi' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus! Literasi keuangan menekankan diversifikasi sebagai kunci investasi sehat.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_03',
                'optionId' => 'C',
                'text' => 'Hanya simpan Rp5.000.000 di rekening biasa',
                'scoreChange' => json_encode(['investasi' => 1, 'tabungan_dan_dana_darurat' => 2, 'literasi_keuangan' => -1]),
                'response' => 'Kurang optimal. Literasi keuangan mengingatkan uang di tabungan akan kalah oleh inflasi jika tidak diinvestasikan.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Saham): Langkah Awal Saham
            // =============================================
            [
                'scenarioId' => 'SCN_INVESTASI_04',
                'optionId' => 'A',
                'text' => 'Membeli saham perusahaan populer tanpa riset',
                'scoreChange' => json_encode(['investasi' => -2, 'literasi_keuangan' => -3]),
                'response' => 'Kurang tepat. Literasi keuangan menekankan pentingnya analisis fundamental/teknikal sebelum membeli saham, bukan asal populer.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_04',
                'optionId' => 'B',
                'text' => 'Membeli saham blue chip yang stabil dan membayar dividen',
                'scoreChange' => json_encode(['investasi' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus! Literasi keuangan menyebut saham blue chip cocok untuk pemula karena lebih stabil dan aman.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_04',
                'optionId' => 'C',
                'text' => 'Menaruh semua uang di 1 saham startup yang baru IPO',
                'scoreChange' => json_encode(['investasi' => -3, 'literasi_keuangan' => -4]),
                'response' => 'Berisiko tinggi. Literasi keuangan mengingatkan jangan taruh semua dana di saham spekulatif (don\'t put all eggs in one basket).',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Saham): Strategi Jangka Panjang
            // =============================================
            [
                'scenarioId' => 'SCN_INVESTASI_05',
                'optionId' => 'A',
                'text' => 'Trading harian untuk mencari cuan cepat',
                'scoreChange' => json_encode(['investasi' => 1, 'literasi_keuangan' => -1]),
                'response' => 'Tidak sesuai tujuan. Literasi keuangan menekankan tujuan jangka panjang lebih cocok dengan strategi investasi, bukan trading harian.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_05',
                'optionId' => 'B',
                'text' => 'Menabung saham rutin tiap bulan pada emiten stabil',
                'scoreChange' => json_encode(['investasi' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus sekali! Literasi keuangan mengajarkan Dollar Cost Averaging (DCA) sebagai strategi efektif untuk investasi jangka panjang.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_05',
                'optionId' => 'C',
                'text' => 'Menyimpan uang di tabungan saja karena takut risiko saham',
                'scoreChange' => json_encode(['investasi' => 1, 'tabungan_dan_dana_darurat' => 2, 'literasi_keuangan' => -1]),
                'response' => 'Cukup aman, tapi kurang optimal. Literasi keuangan menekankan tabungan biasanya kalah oleh inflasi untuk tujuan jangka panjang.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Saham): Psikologi Market Crash
            // =============================================
            [
                'scenarioId' => 'SCN_INVESTASI_06',
                'optionId' => 'A',
                'text' => 'Panik dan langsung jual rugi semua saham',
                'scoreChange' => json_encode(['investasi' => -2, 'literasi_keuangan' => -3]),
                'response' => 'Kurang bijak. Literasi keuangan menyebut fluktuasi harga saham normal, jangan panik jual (panic selling) tanpa analisis.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_06',
                'optionId' => 'B',
                'text' => 'Evaluasi laporan keuangan perusahaan, tetap pegang jika fundamental bagus',
                'scoreChange' => json_encode(['investasi' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus! Literasi keuangan menekankan keputusan harus berbasis analisis fundamental, bukan emosi sesaat.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_06',
                'optionId' => 'C',
                'text' => 'Beli lagi saat harga turun untuk menurunkan harga rata-rata',
                'scoreChange' => json_encode(['investasi' => 3, 'literasi_keuangan' => 3]),
                'response' => 'Cukup baik jika berdasarkan analisis. Literasi keuangan menyebut ini strategi "average down", tapi hati-hati jangan asal beli.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Crypto): Memulai Crypto
            // =============================================
            [
                'scenarioId' => 'SCN_INVESTASI_07',
                'optionId' => 'A',
                'text' => 'Membeli koin baru yang viral di media sosial tanpa riset',
                'scoreChange' => json_encode(['investasi' => -4, 'literasi_keuangan' => -5]),
                'response' => 'Berbahaya. Literasi keuangan menekankan investasi harus berdasarkan riset, bukan sekadar ikut tren (Fear Of Missing Out/FOMO).',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_07',
                'optionId' => 'B',
                'text' => 'Membeli Bitcoin atau Ethereum di exchange resmi',
                'scoreChange' => json_encode(['investasi' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus! Literasi keuangan menyebut crypto utama seperti BTC/ETH relatif lebih aman dibanding koin spekulatif, meski tetap high risk.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_07',
                'optionId' => 'C',
                'text' => 'Menyimpan uang di tabungan dulu sambil belajar tentang crypto',
                'scoreChange' => json_encode(['investasi' => 2, 'literasi_keuangan' => 3]),
                'response' => 'Cukup bijak. Literasi keuangan mengajarkan pentingnya memahami instrumen (Do Your Own Research) sebelum berinvestasi.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Crypto): Volatilitas
            // =============================================
            [
                'scenarioId' => 'SCN_INVESTASI_08',
                'optionId' => 'A',
                'text' => 'Panik dan jual semua aset biar tidak rugi lebih besar',
                'scoreChange' => json_encode(['investasi' => -3, 'literasi_keuangan' => -3]),
                'response' => 'Kurang tepat. Literasi keuangan menekankan pentingnya strategi dan manajemen emosi, bukan panik saat harga turun.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_08',
                'optionId' => 'B',
                'text' => 'Diamkan dulu sambil evaluasi karena tahu harga crypto sangat fluktuatif',
                'scoreChange' => json_encode(['investasi' => 4, 'literasi_keuangan' => 4]),
                'response' => 'Bagus! Literasi keuangan mengingatkan crypto adalah aset berisiko tinggi, perlu kesabaran dan analisis jangka panjang.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_08',
                'optionId' => 'C',
                'text' => 'Beli lagi lebih banyak saat harga turun',
                'scoreChange' => json_encode(['investasi' => 3, 'literasi_keuangan' => 3]),
                'response' => 'Cukup baik jika ada analisis. Literasi keuangan menyebut strategi ini "buy the dip", tapi pastikan menggunakan uang dingin.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Crypto): Keamanan Aset
            // =============================================
            [
                'scenarioId' => 'SCN_INVESTASI_09',
                'optionId' => 'A',
                'text' => 'Biarkan saja di exchange karena lebih praktis',
                'scoreChange' => json_encode(['investasi' => -2, 'literasi_keuangan' => -3]),
                'response' => 'Kurang aman. Literasi keuangan menekankan risiko peretasan jika semua aset hanya disimpan di exchange (Not your keys, not your coins).',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_09',
                'optionId' => 'B',
                'text' => 'Pindahkan sebagian ke wallet pribadi (hardware/software wallet)',
                'scoreChange' => json_encode(['investasi' => 5, 'literasi_keuangan' => 5]),
                'response' => 'Bagus sekali! Literasi keuangan mengajarkan pentingnya diversifikasi penyimpanan untuk keamanan aset digital.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_INVESTASI_09',
                'optionId' => 'C',
                'text' => 'Membagikan password akun ke teman dekat supaya aman jika lupa',
                'scoreChange' => json_encode(['investasi' => -5, 'literasi_keuangan' => -5]),
                'response' => 'Sangat berisiko! Literasi keuangan menekankan data pribadi dan akses wallet (Private Key) tidak boleh dibagi ke orang lain.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Asuransi Kesehatan): Pendaftaran BPJS
            // =============================================
            [
                'scenarioId' => 'SCN_ASURANSI_01',
                'optionId' => 'A',
                'text' => 'Daftar BPJS Kesehatan meski merasa jarang sakit',
                'scoreChange' => json_encode(['literasi_keuangan' => 5, 'tabungan_dan_dana_darurat' => 4]),
                'response' => 'Bagus! Literasi keuangan menekankan asuransi kesehatan adalah bentuk proteksi, bukan investasi, dan berguna saat risiko tak terduga datang.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_01',
                'optionId' => 'B',
                'text' => 'Tidak daftar karena merasa sehat dan sayang bayar iuran',
                'scoreChange' => json_encode(['literasi_keuangan' => -3, 'tabungan_dan_dana_darurat' => -3]),
                'response' => 'Kurang tepat. Literasi keuangan menekankan risiko kesehatan bisa datang kapan saja, dan biaya berobat bisa jauh lebih besar dari iuran.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_01',
                'optionId' => 'C',
                'text' => 'Menunda daftar sampai benar-benar sakit',
                'scoreChange' => json_encode(['literasi_keuangan' => -1, 'tabungan_dan_dana_darurat' => -2]),
                'response' => 'Berisiko. Literasi keuangan mengingatkan bahwa asuransi harus dimiliki sebelum risiko terjadi, bukan setelahnya.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Asuransi Kesehatan): Klaim Pengobatan
            // =============================================
            [
                'scenarioId' => 'SCN_ASURANSI_02',
                'optionId' => 'A',
                'text' => 'Gunakan fasilitas BPJS sesuai prosedur rujukan',
                'scoreChange' => json_encode(['literasi_keuangan' => 5, 'anggaran' => 4]),
                'response' => 'Bagus! Literasi keuangan menekankan pemanfaatan asuransi sesuai prosedur membantu mengurangi biaya kesehatan secara signifikan.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_02',
                'optionId' => 'B',
                'text' => 'Bayar sendiri karena malas antri, meski biaya lebih besar',
                'scoreChange' => json_encode(['literasi_keuangan' => 2, 'anggaran' => -2]),
                'response' => 'Kurang bijak. Literasi keuangan menekankan pentingnya memanfaatkan asuransi agar keuangan tidak terbebani biaya yang seharusnya bisa ditanggung.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_02',
                'optionId' => 'C',
                'text' => 'Pinjam uang teman dulu, baru urus klaim BPJS nanti',
                'scoreChange' => json_encode(['literasi_keuangan' => 1, 'utang' => -1]),
                'response' => 'Tidak efisien. Literasi keuangan menekankan klaim asuransi sebaiknya dimanfaatkan langsung untuk meringankan beban cashflow.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Asuransi Kesehatan): Tunggakan Iuran
            // =============================================
            [
                'scenarioId' => 'SCN_ASURANSI_03',
                'optionId' => 'A',
                'text' => 'Abaikan tunggakan karena merasa tidak pernah pakai',
                'scoreChange' => json_encode(['literasi_keuangan' => -3, 'anggaran' => -2]),
                'response' => 'Kurang tepat. Literasi keuangan menekankan kewajiban iuran tetap harus dipenuhi agar perlindungan tetap aktif saat dibutuhkan.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_03',
                'optionId' => 'B',
                'text' => 'Segera melunasi tunggakan agar status aktif kembali',
                'scoreChange' => json_encode(['literasi_keuangan' => 5, 'anggaran' => 4]),
                'response' => 'Bagus sekali! Literasi keuangan menekankan disiplin membayar iuran adalah bagian dari manajemen risiko yang bertanggung jawab.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_03',
                'optionId' => 'C',
                'text' => 'Membayar sebagian dulu, sisanya bulan depan dengan komunikasi ke pihak BPJS',
                'scoreChange' => json_encode(['literasi_keuangan' => 2, 'anggaran' => 2]),
                'response' => 'Cukup baik. Literasi keuangan menekankan keterbukaan komunikasi penting agar perlindungan tidak terhenti total.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Asuransi Kendaraan): Proteksi Kendaraan Cicilan
            // =============================================
            [
                'scenarioId' => 'SCN_ASURANSI_04',
                'optionId' => 'A',
                'text' => 'Ambil asuransi kendaraan all-risk',
                'scoreChange' => json_encode(['literasi_keuangan' => 5, 'anggaran' => 4]),
                'response' => 'Bagus! Literasi keuangan menekankan perlindungan kendaraan cicilan penting agar tidak menanggung kerugian besar jika terjadi kecelakaan.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_04',
                'optionId' => 'B',
                'text' => 'Tolak asuransi karena merasa bisa hati-hati saat berkendara',
                'scoreChange' => json_encode(['literasi_keuangan' => -3, 'tabungan_dan_dana_darurat' => -2]),
                'response' => 'Kurang tepat. Literasi keuangan mengingatkan risiko jalan raya tidak selalu berasal dari dirimu, tapi juga orang lain.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_04',
                'optionId' => 'C',
                'text' => 'Ambil asuransi TLO (Total Loss Only) karena lebih murah',
                'scoreChange' => json_encode(['literasi_keuangan' => 2, 'anggaran' => 2]),
                'response' => 'Cukup baik. Literasi keuangan menyebut TLO efektif untuk proteksi hilang total/kerusakan parah, tapi tidak menanggung kerusakan ringan.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Asuransi Kendaraan): Asuransi Kendaraan Produktif
            // =============================================
            [
                'scenarioId' => 'SCN_ASURANSI_05',
                'optionId' => 'A',
                'text' => 'Tidak pakai asuransi karena merasa motor jarang rusak',
                'scoreChange' => json_encode(['literasi_keuangan' => -2, 'pendapatan' => -2]),
                'response' => 'Berisiko. Literasi keuangan menekankan kendaraan produktif sebaiknya dilindungi agar penghasilan tidak terganggu saat ada masalah.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_05',
                'optionId' => 'B',
                'text' => 'Ambil asuransi dasar agar ada proteksi minimal',
                'scoreChange' => json_encode(['literasi_keuangan' => 2, 'anggaran' => 2]),
                'response' => 'Cukup baik. Literasi keuangan menekankan proteksi dasar bisa membantu, meski perlindungannya terbatas.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_05',
                'optionId' => 'C',
                'text' => 'Ambil asuransi lengkap karena motor adalah sumber penghasilan utama',
                'scoreChange' => json_encode(['literasi_keuangan' => 5, 'pendapatan' => 4, 'anggaran' => 3]),
                'response' => 'Bagus sekali! Literasi keuangan menyebut proteksi menyeluruh penting untuk menjaga aset produktif dan aliran penghasilan.',
                'is_correct' => true,
            ],

            // =============================================
            // SCENARIO (Asuransi Kendaraan): Risiko Parkir
            // =============================================
            [
                'scenarioId' => 'SCN_ASURANSI_06',
                'optionId' => 'A',
                'text' => 'Tidak ambil asuransi karena yakin motor aman',
                'scoreChange' => json_encode(['literasi_keuangan' => -2, 'tabungan_dan_dana_darurat' => -2]),
                'response' => 'Kurang tepat. Literasi keuangan menekankan risiko kehilangan tetap ada meski terasa aman.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_06',
                'optionId' => 'B',
                'text' => 'Ambil asuransi TLO untuk antisipasi jika motor hilang',
                'scoreChange' => json_encode(['literasi_keuangan' => 5, 'tabungan_dan_dana_darurat' => 4]),
                'response' => 'Bagus! Literasi keuangan menyebut asuransi TLO cocok untuk risiko kehilangan kendaraan yang sering parkir sembarangan.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_06',
                'optionId' => 'C',
                'text' => 'Ambil asuransi all-risk meski premi lebih tinggi',
                'scoreChange' => json_encode(['literasi_keuangan' => 3, 'anggaran' => 2]),
                'response' => 'Cukup baik. Literasi keuangan menekankan proteksi all-risk memberi ketenangan penuh, tapi pastikan biayanya sesuai kemampuan.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Asuransi Barang): Asuransi Gadget
            // =============================================
            [
                'scenarioId' => 'SCN_ASURANSI_07',
                'optionId' => 'A',
                'text' => 'Ambil asuransi gadget untuk proteksi kerusakan/kehilangan',
                'scoreChange' => json_encode(['literasi_keuangan' => 5, 'tabungan_dan_dana_darurat' => 4]),
                'response' => 'Bagus! Literasi keuangan menekankan asuransi barang elektronik penting, apalagi jika laptop adalah aset produktif.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_07',
                'optionId' => 'B',
                'text' => 'Tidak ambil asuransi karena merasa laptop pasti aman',
                'scoreChange' => json_encode(['literasi_keuangan' => -3, 'tabungan_dan_dana_darurat' => -3]),
                'response' => 'Kurang tepat. Literasi keuangan mengingatkan risiko kehilangan/kerusakan bisa datang tiba-tiba dan biayanya mahal.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_07',
                'optionId' => 'C',
                'text' => 'Simpan sebagian dana di tabungan khusus untuk perbaikan laptop',
                'scoreChange' => json_encode(['literasi_keuangan' => 2, 'tabungan_dan_dana_darurat' => 3]),
                'response' => 'Cukup baik. Literasi keuangan menyebut alternatif self-insurance bisa jadi opsi, tapi risiko ditanggung sendiri sepenuhnya.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Asuransi Barang): Kehilangan HP
            // =============================================
            [
                'scenarioId' => 'SCN_ASURANSI_08',
                'optionId' => 'A',
                'text' => 'Ambil asuransi HP dengan premi bulanan agar ada penggantian',
                'scoreChange' => json_encode(['literasi_keuangan' => 5, 'tabungan_dan_dana_darurat' => 4]),
                'response' => 'Bagus sekali! Literasi keuangan menekankan asuransi gadget bisa melindungi dari kerugian mendadak.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_08',
                'optionId' => 'B',
                'text' => 'Tidak ambil asuransi, beli HP baru dengan cicilan',
                'scoreChange' => json_encode(['literasi_keuangan' => -2, 'utang' => -3, 'anggaran' => -2]),
                'response' => 'Berisiko. Literasi keuangan mengingatkan membeli aset konsumtif dengan cicilan menambah beban keuangan.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_08',
                'optionId' => 'C',
                'text' => 'Pakai HP lama seadanya sambil nabung untuk beli baru',
                'scoreChange' => json_encode(['literasi_keuangan' => 2, 'tabungan_dan_dana_darurat' => 3, 'anggaran' => 2]),
                'response' => 'Cukup realistis. Literasi keuangan menyebut strategi ini bisa dipakai untuk menghindari utang, tapi butuh disiplin menabung.',
                'is_correct' => false,
            ],

            // =============================================
            // SCENARIO (Asuransi Barang): Risiko Kebakaran Kos
            // =============================================
            [
                'scenarioId' => 'SCN_ASURANSI_09',
                'optionId' => 'A',
                'text' => 'Abaikan karena merasa kemungkinan sangat kecil',
                'scoreChange' => json_encode(['literasi_keuangan' => -4, 'tabungan_dan_dana_darurat' => -3]),
                'response' => 'Kurang tepat. Literasi keuangan menekankan manajemen risiko adalah melindungi diri dari hal tak terduga yang berdampak besar.',
                'is_correct' => false,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_09',
                'optionId' => 'B',
                'text' => 'Ambil asuransi harta benda sederhana yang melindungi barang di kos',
                'scoreChange' => json_encode(['literasi_keuangan' => 4, 'tabungan_dan_dana_darurat' => 4]),
                'response' => 'Bagus! Literasi keuangan menyebut asuransi harta dapat membantu meringankan beban jika terjadi kerugian total.',
                'is_correct' => true,
            ],
            [
                'scenarioId' => 'SCN_ASURANSI_09',
                'optionId' => 'C',
                'text' => 'Foto dan catat semua barang berharga untuk bukti, lalu simpan sebagian uang di rekening darurat',
                'scoreChange' => json_encode(['literasi_keuangan' => 3, 'tabungan_dan_dana_darurat' => 4]),
                'response' => 'Cukup bijak. Literasi keuangan menekankan dokumentasi aset dan dana cadangan juga bagian penting dari manajemen risiko.',
                'is_correct' => false,
            ],
        ]);
    }
}
