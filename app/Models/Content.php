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

class Content extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'contents';

    /**
     * Field yang boleh diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'category',     // Enum: doa, hadist, quotes
        'judul',        // Judul Doa / Hadist
        'teks_arab',    // Teks dalam bahasa Arab
        'teks_indo',    // Terjemahan atau isi konten
        'sumber',       // Riwayat atau sumber teks
        'is_active',    // Status aktif untuk display
        'durasi',       // Durasi tampil di layar (detik)
    ];

    /**
     * Casting tipe data agar konsisten saat diakses.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'durasi'    => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope untuk mengambil hanya konten yang aktif.
     * Berguna banget buat query di Live Display nanti.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter berdasarkan kategori.
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
