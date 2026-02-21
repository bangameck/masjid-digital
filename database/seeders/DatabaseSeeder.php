<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. App Settings
        DB::table('app_settings')->insert([
            'id' => 1,
            'nama_masjid' => 'Masjid Nurul Fitrah',
            'alamat' => 'Jl. H. Imam Munandar, Pekanbaru',
            'latitude' => '0.51788',
            'longitude' => '101.44737',
            'zona_waktu' => 'Asia/Jakarta',
            'api_cari_lokasi' => 'https://api.myquran.com/v3/sholat/kabkota/cari/',
            'api_jadwal_sholat' => 'https://api.myquran.com/v3/sholat/jadwal/',
            'api_hijriah' => 'https://api.myquran.com/v3/cal/hijr/',
            'kota_nama' => 'KOTA PEKANBARU',
            'kota_id' => 'c7e1249ffc03eb9ded908c236bd1996d',
            'logo_path' => 'logos/Y2f6abAuI5vr2KQ6cVwrq4PvqgXc5x8fdSXGiqlK.png',
            'background_image' => 'backgrounds/L532KxBL0CvCMqG7XkeW2VaLOckv8609LUBnwWss.png',
            'running_text_speed' => 7,
            'durasi_slide_foto' => 5000,
            'iqomah_subuh' => 10,
            'iqomah_dzuhur' => 10,
            'iqomah_ashar' => 10,
            'iqomah_maghrib' => 10,
            'iqomah_isya' => 2,
            'theme_color' => 'emerald',
            'created_at' => '2026-02-13 14:52:53',
            'updated_at' => '2026-02-16 14:16:00',
        ]);

        // 2. Users (Password: password)
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Super Admin',
            'email' => 'admin@masjid.com',
            'password' => '$2y$12$S.KmCkTEhAN/F0mOkLlSUuRjrT4iOcnORc1yMCGBdL/aAUS0zYdRK', // Hash aslinya
            'role' => 'superadmin',
            'created_at' => '2026-02-13 14:52:54',
        ]);

        // 3. Banners
        DB::table('banners')->insert([
            [
                'id' => 1,
                'judul' => 'Ayo Berqurban 2026',
                'image_path' => 'banners/EE8j1wXj8WXNLc5VEaibbYpIhcwfJNBrpcNjiVct.jpg',
                'tgl_mulai' => '2026-02-16',
                'tgl_selesai' => '2026-02-17',
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'judul' => 'Kebersamaan yang hakiki',
                'image_path' => 'banners/5UmBVxdsGuKvQKRosAkA1XB5emhJlEvHaa3uhxv5.jpg',
                'tgl_mulai' => '2026-02-15',
                'tgl_selesai' => '2026-02-16',
                'is_active' => 1,
                'created_at' => now(),
            ]
        ]);

        // 4. Contents (Doa & Hadist)
        DB::table('contents')->insert([
            [
                'id' => 1,
                'category' => 'doa',
                'judul' => 'Al Baqarah: 83',
                'teks_arab' => 'وَاِذْ اَخَذْنَا مِيْثَاقَ بَنِيْٓ اِسْرَاۤءِيْلَ لَا تَعْبُدُوْنَ اِلَّا اللّٰهَ وَبِالْوَالِدَيْنِ اِحْسَانًا وَّذِى الْقُرْبٰى وَالْيَتٰمٰى وَالْمَسٰكِيْنِ وَقُوْلُوْا لِلنَّاسِ حُسْنًا وَّاَقِيْمُوا الصَّلٰوةَ وَاٰتُوا الزَّكٰوةَۗ ثُمَّ تَوَلَّيْتُمْ اِلَّا قَلِيْلًا مِّنْكُمْ وَاَنْتُمْ مُّعْرِضُوْنَ ۝٨٣',
                'teks_indo' => 'Dan (ingatlah), ketika Kami mengambil janji dari Bani Israil (yaitu): Janganlah kamu menyembah selain Allah...',
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'category' => 'hadist',
                'judul' => 'Tempat yang paling dicintai Allah',
                'teks_arab' => 'أَحَبُّ البِلادِ إلَى اللهِ مَسَاجِدُهَا ، وَأبْغَضُ البِلاَدِ إلَى اللهِ أسْوَاقُهَا',
                'teks_indo' => 'Tempat yang paling dicintai Allâh adalah masjid-masjidnya...',
                'is_active' => 1,
                'created_at' => now(),
            ]
        ]);

        // 5. Jadwal Sholat (Februari 2026 Saja)
        $jadwalData = [
            ['tanggal' => '2026-02-01', 'hijri' => 'Ahad, 14 Syakban 1447 H', 'subuh' => '05:07', 'isya' => '19:45'],
            ['tanggal' => '2026-02-02', 'hijri' => 'Senin, 15 Syakban 1447 H', 'subuh' => '05:07', 'isya' => '19:45'],
            ['tanggal' => '2026-02-03', 'hijri' => 'Selasa, 16 Syakban 1447 H', 'subuh' => '05:07', 'isya' => '19:45'],
            ['tanggal' => '2026-02-04', 'hijri' => 'Rabu, 17 Syakban 1447 H', 'subuh' => '05:08', 'isya' => '19:45'],
            ['tanggal' => '2026-02-05', 'hijri' => 'Kamis, 18 Syakban 1447 H', 'subuh' => '05:08', 'isya' => '19:45'],
            ['tanggal' => '2026-02-06', 'hijri' => 'Jumat, 19 Syakban 1447 H', 'subuh' => '05:08', 'isya' => '19:45'],
            ['tanggal' => '2026-02-07', 'hijri' => 'Sabtu, 20 Syakban 1447 H', 'subuh' => '05:08', 'isya' => '19:45'],
            ['tanggal' => '2026-02-08', 'hijri' => 'Ahad, 21 Syakban 1447 H', 'subuh' => '05:08', 'isya' => '19:45'],
            ['tanggal' => '2026-02-09', 'hijri' => 'Senin, 22 Syakban 1447 H', 'subuh' => '05:09', 'isya' => '19:45'],
            ['tanggal' => '2026-02-10', 'hijri' => 'Selasa, 23 Syakban 1447 H', 'subuh' => '05:09', 'isya' => '19:45'],
            ['tanggal' => '2026-02-11', 'hijri' => 'Rabu, 24 Syakban 1447 H', 'subuh' => '05:09', 'isya' => '19:45'],
            ['tanggal' => '2026-02-12', 'hijri' => 'Kamis, 25 Syakban 1447 H', 'subuh' => '05:09', 'isya' => '19:44'],
            ['tanggal' => '2026-02-13', 'hijri' => 'Jumat, 26 Syakban 1447 H', 'subuh' => '05:09', 'isya' => '19:44'],
            ['tanggal' => '2026-02-15', 'hijri' => 'Ahad, 28 Syakban 1447 H', 'subuh' => '05:09', 'isya' => '19:15'],
            ['tanggal' => '2026-02-16', 'hijri' => 'Senin, 29 Syakban 1447 H', 'subuh' => '05:09', 'isya' => '22:00'],
            ['tanggal' => '2026-02-17', 'hijri' => 'Selasa, 30 Syakban 1447 H', 'subuh' => '05:09', 'isya' => '19:44'],
            ['tanggal' => '2026-02-18', 'hijri' => 'Rabu, 1 Ramadan 1447 H', 'subuh' => '05:09', 'isya' => '19:44'],
            ['tanggal' => '2026-02-19', 'hijri' => 'Kamis, 2 Ramadan 1447 H', 'subuh' => '05:09', 'isya' => '19:43'],
            ['tanggal' => '2026-02-20', 'hijri' => 'Jumat, 3 Ramadan 1447 H', 'subuh' => '05:09', 'isya' => '19:43'],
            ['tanggal' => '2026-02-21', 'hijri' => 'Sabtu, 4 Ramadan 1447 H', 'subuh' => '05:09', 'isya' => '19:43'],
            ['tanggal' => '2026-02-23', 'hijri' => 'Senin, 6 Ramadan 1447 H', 'subuh' => '05:09', 'isya' => '19:43'],
            ['tanggal' => '2026-02-24', 'hijri' => 'Selasa, 7 Ramadan 1447 H', 'subuh' => '05:09', 'isya' => '19:42'],
            ['tanggal' => '2026-02-25', 'hijri' => 'Rabu, 8 Ramadan 1447 H', 'subuh' => '05:09', 'isya' => '19:42'],
            ['tanggal' => '2026-02-27', 'hijri' => 'Jumat, 10 Ramadan 1447 H', 'subuh' => '05:09', 'isya' => '19:42'],
            ['tanggal' => '2026-02-28', 'hijri' => 'Sabtu, 11 Ramadan 1447 H', 'subuh' => '05:09', 'isya' => '19:42'],
        ];

        foreach($jadwalData as $j) {
            DB::table('jadwal_sholat')->insert([
                'tanggal' => $j['tanggal'],
                'tanggal_hijriah' => $j['hijri'],
                'imsak' => '04:58:00',
                'subuh' => $j['subuh'] . ':00',
                'terbit' => '06:22:00',
                'dhuha' => '06:50:00',
                'dzuhur' => '12:32:00',
                'ashar' => '15:52:00',
                'maghrib' => '18:34:00',
                'isya' => $j['isya'] . ':00',
                'created_at' => now(),
            ]);
        }

        // 6. Running Texts
        DB::table('running_texts')->insert([
            ['teks' => 'Selamat Datang di Masjid Nurul Fitrah. Mohon luruskan dan rapatkan shaf.', 'tipe' => 'ucapan', 'kecepatan' => 5, 'urutan' => 1, 'created_at' => now()],
            ['teks' => 'Mohon Matikan Ponsel atau Mode Silent', 'tipe' => 'info', 'kecepatan' => 5, 'urutan' => 2, 'created_at' => now()],
            ['teks' => 'Pengajian Rutin Tanggal 16 Februari 2026, Dengan Ust. Sergio Virnando', 'tipe' => 'info', 'kecepatan' => 5, 'urutan' => 3, 'created_at' => now()],
        ]);

        // 7. Keuangan
        DB::table('keuangan')->insert([
            ['tanggal' => '2026-02-15', 'kategori' => 'pemasukan', 'sumber_atau_tujuan' => 'infak malam 1', 'nominal' => 750000.00, 'user_id' => 1, 'created_at' => now()],
            ['tanggal' => '2026-02-15', 'kategori' => 'pengeluaran', 'sumber_atau_tujuan' => 'pengeluaran malam pertama', 'nominal' => 560000.00, 'user_id' => 1, 'created_at' => now()],
        ]);

        // 8. Rekenings
        DB::table('rekenings')->insert([
            'nama_bank' => 'BSI',
            'nama_akun' => 'wawan Hermawan',
            'nomor_rekening' => '7187921214',
            'is_active' => 1,
        ]);

        // 9. Petugas Rutin & Ramadhan
        DB::table('pengajian_rutin')->insert([
            'tanggal' => '2026-02-16',
            'penceramah' => 'Ust. M. Sergio Virnando',
            'judul_ceramah' => 'Test Aja Penceramah Judulnya Lorem Ipsum Dolor',
            'created_at' => now(),
        ]);

        DB::table('petugas_jumat')->insert([
            'tanggal' => '2026-02-20',
            'khatib' => 'Ust. Analdi Fitrah',
            'imam' => 'Syamsu Anwar',
            'muadzin' => 'Balqi Ahmad',
            'bilal' => 'Ahmad Sobirin',
            'judul_ceramah' => 'Jumat Pertama dibulan puasa',
            'created_at' => now(),
        ]);

        DB::table('petugas_ramadhan')->insert([
            'tanggal' => '2026-02-18',
            'malam_ke' => 1,
            'penceramah' => 'Ust. M. Sergio Virnando',
            'imam' => 'Achmad Riadi',
            'muadzin' => 'Syamsu Anwar',
            'bilal' => 'Katmono',
            'judul_ceramah' => 'Malam Penuh Hikmat',
            'created_at' => now(),
        ]);

        // 10. Gallery
        DB::table('galleries')->insert([
            'nama_kegiatan' => 'Qurban Thn 2025',
            'tanggal_kegiatan' => '2026-02-15',
            'deskripsi' => '',
            'is_active' => 1,
            'created_at' => now(),
        ]);

        $this->call([
            ThemeColorSeeder::class,
        ]);
    }
}
