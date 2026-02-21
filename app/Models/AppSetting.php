<?php

/**
 * Aplikasi Masjid Digital
 * * @author RadevankaProject (@bangameck)
 * @link https://github.com/bangameck/masjid-digital
 * @license MIT
 * * Dibuat dengan niat amal jariyah untuk digitalisasi masjid.
 * Tolong jangan hapus hak cipta ini.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Semua kolom boleh diisi kecuali ID

    // Jika ingin tipe data otomatis dikonversi
    protected $casts = [
        'running_text_speed' => 'integer',
        'durasi_slide_foto'  => 'integer',
        'iqomah_subuh'       => 'integer',
        'iqomah_dzuhur'      => 'integer',
        'iqomah_ashar'       => 'integer',
        'iqomah_magrib'      => 'integer',
        'iqomah_isya'        => 'integer',
        'koreksi_subuh'      => 'integer',
        'koreksi_dzuhur'     => 'integer',
        'koreksi_ashar'      => 'integer',
        'koreksi_magrib'     => 'integer',
        'koreksi_isya'       => 'integer',
    ];

    public function theme()
    {
        // AppSetting memiliki kolom 'theme_color' yang mereferensi ke 'name' di tabel theme_colors
        return $this->belongsTo(ThemeColor::class, 'theme_color', 'name');
    }
}
