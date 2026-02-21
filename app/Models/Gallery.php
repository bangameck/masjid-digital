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

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kegiatan', 'tanggal_kegiatan', 'deskripsi', 'is_active'];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'is_active' => 'boolean',
    ];

    // Relasi: Galeri punya banyak foto
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
