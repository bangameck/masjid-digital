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

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Gallery;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Galeri Kegiatan')]
class GalleryManager extends Component
{
    use WithPagination, WithFileUploads;

    public $viewMode = 'albums';
    public $activeGalleryId = null;
    public $activeGalleryTitle = '';

    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isDeletePhotoModalOpen = false;
    public $isEditMode = false;
    public $selectedId;

    public $nama_kegiatan, $tanggal_kegiatan, $deskripsi, $is_active = true;

    public $photo;
    public $originalFileName;

    public $selectedPhotos = [];
    public $photoToDeleteId = null;

    // Variabel Pembatas Hak Akses
    public $canEdit = false;

    public function mount()
    {
        // Hanya Superadmin, Operator, dan Humas yang bisa CRUD Galeri
        $this->canEdit = in_array(Auth::user()->role, ['superadmin', 'operator', 'humas']);
    }

    public function render()
    {
        if ($this->viewMode == 'albums') {
            $galleries = Gallery::with('photos')->latest('tanggal_kegiatan')->paginate(9);
            return view('livewire.gallery-manager', ['galleries' => $galleries]);
        } else {
            $photos = Photo::where('gallery_id', $this->activeGalleryId)->latest()->get();
            return view('livewire.gallery-manager', ['photos' => $photos]);
        }
    }

    // --- HELPER UNTUK DATA LIGHTBOX ---
    public function getLightboxData()
    {
        if (!$this->activeGalleryId) return [];

        return Photo::where('gallery_id', $this->activeGalleryId)
            ->latest()
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'url' => Storage::url($p->file_path),
                'caption' => $p->caption
            ])
            ->toArray();
    }

    // --- NAVIGATION ---
    public function openGallery($id)
    {
        $g = Gallery::find($id);
        $this->activeGalleryId = $id;
        $this->activeGalleryTitle = $g->nama_kegiatan;
        $this->viewMode = 'photos';
        $this->selectedPhotos = [];
    }

    public function backToAlbums()
    {
        $this->viewMode = 'albums';
        $this->activeGalleryId = null;
        $this->photo = null;
    }

    // --- PHOTO LOGIC ---
    public function setOriginalFileName($name)
    {
        $this->originalFileName = $name;
    }

    public function savePhoto()
    {
        if (!$this->canEdit) return;

        $this->validate(['photo' => 'image|max:10240']);

        if ($this->photo) {
            $path = $this->photo->store('gallery_photos', 'public');
            $caption = $this->originalFileName
                ? pathinfo($this->originalFileName, PATHINFO_FILENAME)
                : 'Foto Dokumentasi';

            Photo::create([
                'gallery_id' => $this->activeGalleryId,
                'file_path' => $path,
                'caption' => $caption,
            ]);

            $this->photo = null;
            $this->originalFileName = null;

            $this->dispatch('refresh-lightbox', data: $this->getLightboxData());
        }
    }

    // --- DELETE ACTIONS ---
    public function confirmDeletePhoto($id)
    {
        if (!$this->canEdit) return;
        $this->photoToDeleteId = $id;
        $this->selectedPhotos = [];
        $this->isDeletePhotoModalOpen = true;
    }

    public function confirmBulkDelete()
    {
        if (!$this->canEdit) return;
        if (count($this->selectedPhotos) > 0) {
            $this->photoToDeleteId = null;
            $this->isDeletePhotoModalOpen = true;
        }
    }

    public function deletePhotoAction()
    {
        if (!$this->canEdit) return;

        if ($this->photoToDeleteId) {
            $p = Photo::find($this->photoToDeleteId);
            if ($p) {
                if(Storage::disk('public')->exists($p->file_path)) {
                    Storage::disk('public')->delete($p->file_path);
                }
                $p->delete();
            }
        }
        elseif (count($this->selectedPhotos) > 0) {
            $photos = Photo::whereIn('id', $this->selectedPhotos)->get();
            foreach ($photos as $p) {
                if(Storage::disk('public')->exists($p->file_path)) {
                    Storage::disk('public')->delete($p->file_path);
                }
                $p->delete();
            }
        }

        $this->isDeletePhotoModalOpen = false;
        $this->photoToDeleteId = null;
        $this->selectedPhotos = [];

        $this->dispatch('refresh-lightbox', data: $this->getLightboxData());
        session()->flash('message', 'Foto berhasil dihapus!');
    }

    public function updateCaption($id, $caption)
    {
        if (!$this->canEdit) return;
        Photo::find($id)->update(['caption' => $caption]);
        $this->dispatch('refresh-lightbox', data: $this->getLightboxData());
    }

    public function downloadPhoto($id)
    {
        $photo = Photo::find($id);
        if ($photo && Storage::disk('public')->exists($photo->file_path)) {
            return response()->download(storage_path('app/public/' . $photo->file_path), $photo->caption . '.jpg');
        }
    }

    // --- GALLERY CRUD ---
    public function createGallery() {
        if (!$this->canEdit) return;
        $this->resetInput();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function storeGallery() {
        if (!$this->canEdit) return;
        $this->validate(['nama_kegiatan' => 'required', 'tanggal_kegiatan' => 'required|date']);
        Gallery::create(['nama_kegiatan' => $this->nama_kegiatan, 'tanggal_kegiatan' => $this->tanggal_kegiatan, 'deskripsi' => $this->deskripsi, 'is_active' => $this->is_active]);
        $this->closeModal(); session()->flash('message', 'Album berhasil dibuat!');
    }

    public function editGallery($id) {
        if (!$this->canEdit) return;
        $g = Gallery::find($id); $this->selectedId = $id; $this->nama_kegiatan = $g->nama_kegiatan; $this->tanggal_kegiatan = $g->tanggal_kegiatan->format('Y-m-d'); $this->deskripsi = $g->deskripsi; $this->is_active = $g->is_active;
        $this->isEditMode = true; $this->isModalOpen = true;
    }

    public function updateGallery() {
        if (!$this->canEdit) return;
        $this->validate(['nama_kegiatan' => 'required', 'tanggal_kegiatan' => 'required|date']);
        Gallery::find($this->selectedId)->update(['nama_kegiatan' => $this->nama_kegiatan, 'tanggal_kegiatan' => $this->tanggal_kegiatan, 'deskripsi' => $this->deskripsi, 'is_active' => $this->is_active]);
        $this->closeModal(); session()->flash('message', 'Album diperbarui!');
    }

    public function deleteGalleryId($id) {
        if (!$this->canEdit) return;
        $this->selectedId = $id; $this->isDeleteModalOpen = true;
    }

    public function deleteGallery() {
        if (!$this->canEdit) return;
        $g = Gallery::with('photos')->find($this->selectedId);
        foreach($g->photos as $photo) { if(Storage::disk('public')->exists($photo->file_path)) { Storage::disk('public')->delete($photo->file_path); } }
        $g->delete();
        $this->isDeleteModalOpen = false; session()->flash('message', 'Album dihapus!');
    }

    public function closeModal() {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->isDeletePhotoModalOpen = false;
        $this->resetInput();
    }

    public function resetInput() { $this->nama_kegiatan = ''; $this->tanggal_kegiatan = date('Y-m-d'); $this->deskripsi = ''; $this->is_active = true; }
}
