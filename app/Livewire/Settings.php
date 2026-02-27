<?php

/**
 * Aplikasi Masjid Digital
 * * @author RadevankaProject (@bangameck)
 * @link https://github.com/bangameck/masjid-digital
 * @license MIT
 * * Dibuat dengan niat amal jariyah untuk digitalisasi masjid.
 * Tolong jangan hapus hak cipta ini.
 */

namespace App\Livewire;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ThemeColor;

#[Layout('components.layouts.app')]
#[Title('Pengaturan Aplikasi')]
class Settings extends Component
{
    use WithFileUploads;

    public $tipe_tempat; // Masjid / Mushola
    public $nama_masjid, $alamat, $zona_waktu;
    public $latitude, $longitude;
    public $running_text_speed, $durasi_slide_foto;

    // Uploads
    public $logo, $background_image;
    public $old_logo, $old_background;

    public $adzan, $old_adzan;
    public $adzan_subuh, $old_adzan_subuh; // Adzan Subuh

    public $video, $old_video;
    public $isVideoSaving = false;

    public $availableThemes = [];
    public $theme_color;

    public $iqomah  = [];
    public $koreksi = [];

    public $api_cari_lokasi, $api_jadwal_sholat, $api_hijriah;
    public $kota_id, $kota_nama;

    public $canEdit = false;

    public function mount()
    {
        $settings = AppSetting::getSettings();
        $this->availableThemes = ThemeColor::where('is_active', true)->get();

        if ($settings) {
            $this->tipe_tempat        = $settings->tipe_tempat ?? 'Masjid';
            $this->nama_masjid        = $settings->nama_masjid;
            $this->alamat             = $settings->alamat;
            $this->latitude           = $settings->latitude ?? '0.51788';
            $this->longitude          = $settings->longitude ?? '101.44737';
            $this->zona_waktu         = $settings->zona_waktu;

            $this->api_cari_lokasi    = $settings->api_cari_lokasi ?? 'https://api.myquran.com/v3/sholat/kabkota/cari/';
            $this->api_jadwal_sholat  = $settings->api_jadwal_sholat ?? 'https://api.myquran.com/v3/sholat/jadwal/';
            $this->api_hijriah        = $settings->api_hijriah ?? 'https://api.myquran.com/v3/cal/hijr/';
            $this->kota_id            = $settings->kota_id;
            $this->kota_nama          = $settings->kota_nama;

            $this->running_text_speed = $settings->running_text_speed;
            $this->durasi_slide_foto  = $settings->durasi_slide_foto;

            // Media Lama
            $this->old_video       = $settings->video_playlist_url;
            $this->old_logo        = $settings->logo_path;
            $this->old_background  = $settings->background_image;
            $this->old_adzan       = $settings->path_adzan;
            $this->old_adzan_subuh = $settings->path_adzan_subuh;

            $this->theme_color     = $settings->theme_color ?? 'emerald';

            $this->iqomah = [
                'subuh'   => $settings->iqomah_subuh,
                'dzuhur'  => $settings->iqomah_dzuhur,
                'ashar'   => $settings->iqomah_ashar,
                'maghrib' => $settings->iqomah_maghrib,
                'isya'    => $settings->iqomah_isya,
            ];

            $this->koreksi = [
                'subuh'   => $settings->koreksi_subuh,
                'dzuhur'  => $settings->koreksi_dzuhur,
                'ashar'   => $settings->koreksi_ashar,
                'maghrib' => $settings->koreksi_maghrib,
                'isya'    => $settings->koreksi_isya,
            ];
        }

        $this->canEdit = in_array(auth()->user()->role, ['superadmin', 'operator']);

    }

    public function searchCity($query)
    {
        if (strlen($query) < 3) return [];
        $response = Http::get($this->api_cari_lokasi . $query);
        return $response->json('status') ? $response->json('data') : [];
    }

    // ==============================================================
    // AUTO-SAVE MEDIA HOOKS (Jalan otomatis saat upload selesai)
    // ==============================================================

    public function updatedLogo()
    {
        if (!$this->canEdit) return;
        $settings = AppSetting::first();
        if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
            Storage::disk('public')->delete($settings->logo_path);
        }
        $path = $this->logo->store('logos', 'public');
        $settings->update(['logo_path' => $path]);
        $this->old_logo = $path;
        $this->logo = null;
        session()->flash('media_success', 'Logo otomatis diperbarui!');
    }

    public function updatedBackgroundImage()
    {
        if (!$this->canEdit) return;
        $settings = AppSetting::first();
        if ($settings->background_image && Storage::disk('public')->exists($settings->background_image)) {
            Storage::disk('public')->delete($settings->background_image);
        }
        $path = $this->background_image->store('backgrounds', 'public');
        $settings->update(['background_image' => $path]);
        $this->old_background = $path;
        $this->background_image = null;
        session()->flash('media_success', 'Background otomatis diperbarui!');
    }

    public function updatedVideo()
    {
        if (!$this->canEdit) return;
        $this->validate(['video' => 'file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska|max:204800']);
        $this->isVideoSaving = true;
        try {
            $settings = AppSetting::first();
            if ($settings->video_playlist_url && Storage::disk('public')->exists($settings->video_playlist_url)) {
                Storage::disk('public')->delete($settings->video_playlist_url);
            }
            $path = $this->video->store('videos', 'public');
            $settings->update(['video_playlist_url' => $path]);
            $this->old_video = $path;
            $this->video = null;
            session()->flash('media_success', 'Video Kajian otomatis diperbarui!');
        } catch (\Exception $e) {
            $this->addError('video', 'Gagal menyimpan video: ' . $e->getMessage());
        }
        $this->isVideoSaving = false;
    }

    public function updatedAdzan()
    {
        if (!$this->canEdit) return;
        $this->validate(['adzan' => 'mimes:mp3,wav|max:10240']);
        $settings = AppSetting::first();
        if ($settings->path_adzan && Storage::disk('public')->exists($settings->path_adzan)) {
            Storage::disk('public')->delete($settings->path_adzan);
        }
        $path = $this->adzan->store('sounds', 'public');
        $settings->update(['path_adzan' => $path]);
        $this->old_adzan = $path;
        $this->adzan = null;
        session()->flash('media_success', 'Audio Adzan otomatis diperbarui!');
    }

    public function updatedAdzanSubuh()
    {
        if (!$this->canEdit) return;
        $this->validate(['adzan_subuh' => 'mimes:mp3,wav|max:10240']);
        $settings = AppSetting::first();
        if ($settings->path_adzan_subuh && Storage::disk('public')->exists($settings->path_adzan_subuh)) {
            Storage::disk('public')->delete($settings->path_adzan_subuh);
        }
        $path = $this->adzan_subuh->store('sounds', 'public');
        $settings->update(['path_adzan_subuh' => $path]);
        $this->old_adzan_subuh = $path;
        $this->adzan_subuh = null;
        session()->flash('media_success', 'Audio Adzan Subuh otomatis diperbarui!');
    }

    // ==============================================================
    // SAVE DATA TEKS (Konfigurasi & Parameter)
    // ==============================================================

    public function save()
    {
        if (!$this->canEdit) return;
        $this->validate([
            'tipe_tempat'      => 'required|in:Masjid,Mushola',
            'nama_masjid'      => 'required|string|max:255',
            'latitude'         => 'required',
            'longitude'        => 'required',
            'theme_color'      => 'required|exists:theme_colors,name',
        ]);

        $settings = AppSetting::first();

        $settings->update([
            'tipe_tempat'        => $this->tipe_tempat,
            'nama_masjid'        => $this->nama_masjid,
            'alamat'             => $this->alamat,
            'latitude'           => $this->latitude,
            'longitude'          => $this->longitude,
            'zona_waktu'         => $this->zona_waktu,
            'api_cari_lokasi'    => $this->api_cari_lokasi,
            'api_jadwal_sholat'  => $this->api_jadwal_sholat,
            'api_hijriah'        => $this->api_hijriah,
            'kota_id'            => $this->kota_id,
            'kota_nama'          => $this->kota_nama,
            'running_text_speed' => $this->running_text_speed,
            'durasi_slide_foto'  => $this->durasi_slide_foto,
            'iqomah_subuh'       => $this->iqomah['subuh'],
            'iqomah_dzuhur'      => $this->iqomah['dzuhur'],
            'iqomah_ashar'       => $this->iqomah['ashar'],
            'iqomah_maghrib'     => $this->iqomah['maghrib'],
            'iqomah_isya'        => $this->iqomah['isya'],
            'koreksi_subuh'      => $this->koreksi['subuh'],
            'koreksi_dzuhur'     => $this->koreksi['dzuhur'],
            'koreksi_ashar'      => $this->koreksi['ashar'],
            'koreksi_maghrib'    => $this->koreksi['maghrib'],
            'koreksi_isya'       => $this->koreksi['isya'],
            'theme_color'        => $this->theme_color,
        ]);

        $this->dispatch('theme-changed', color: $this->theme_color);
        session()->flash('message', 'Pengaturan teks & lokasi disimpan!');
    }

    public function render()
    {
        return view('livewire.settings');
    }
}
