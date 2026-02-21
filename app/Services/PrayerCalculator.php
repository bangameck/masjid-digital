<?php
/**
 * Aplikasi Masjid Digital
 * * @author RadevankaProject (@bangameck)
 * @link https://github.com/bangameck/masjid-digital
 * @license MIT
 * * Dibuat dengan niat amal jariyah untuk digitalisasi masjid.
 * Tolong jangan hapus hak cipta ini.
 */

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\AppSetting;

class PrayerCalculator
{
    public static function calculate($kotaId, $date)
    {
        // Ambil settingan URL dari database
        $setting = AppSetting::first();

        // Fallback ke URL default jika di database kosong
        $baseUrlJadwal = $setting->api_jadwal_sholat ?? 'https://api.myquran.com/v3/sholat/jadwal/';
        $baseUrlHijri  = $setting->api_hijriah ?? 'https://api.myquran.com/v3/cal/hijr/';

        // Pastikan URL diakhiri dengan slash agar tidak bentrok saat digabung dengan parameter
        $baseUrlJadwal = rtrim($baseUrlJadwal, '/') . '/';
        $baseUrlHijri  = rtrim($baseUrlHijri, '/') . '/';

        try {
            // 1. Tembak API Jadwal Sholat v3: {url}/{kotaId}/{yyyy-mm-dd}
            $responseJadwal = Http::timeout(10)->get($baseUrlJadwal . $kotaId . '/' . $date);
            $dataJadwal = $responseJadwal->json();

            // 2. Tembak API Hijriah: {url}/{yyyy-mm-dd}
            $responseHijri = Http::timeout(10)->get($baseUrlHijri . $date);
            $dataHijri = $responseHijri->json();

            if ($dataJadwal && $dataJadwal['status'] == true) {
                // Sesuai struktur v3: data -> jadwal -> {tanggal}
                $j = $dataJadwal['data']['jadwal'][$date];

                // Ambil data Hijriah dari output API Hijriah
                $hijriString = null;
                if ($dataHijri && $dataHijri['status'] == true) {
                    $hijriString = $dataHijri['data']['hijr']['today'];
                }

                return [
                    'times' => [
                        'imsak'   => $j['imsak'],
                        'subuh'   => $j['subuh'],
                        'terbit'  => $j['terbit'],
                        'dhuha'   => $j['dhuha'],
                        'dzuhur'  => $j['dzuhur'],
                        'ashar'   => $j['ashar'],
                        'maghrib' => $j['maghrib'],
                        'isya'    => $j['isya'],
                    ],
                    // Jika API Hijriah gagal, fallback ke field 'tanggal' dari API Jadwal
                    'hijri' => $hijriString ?? $j['tanggal']
                ];
            }
        } catch (\Exception $e) {
            // Log error jika diperlukan untuk debugging
            \Illuminate\Support\Facades\Log::error("PrayerCalculator Error: " . $e->getMessage());
            return null;
        }

        return null;
    }
}
