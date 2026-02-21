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
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Manajemen Banner')]
class BannerManager extends Component
{
    use WithPagination, WithFileUploads;

    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isEditMode = false;
    public $selectedId;

    // Form Properties
    public $judul, $tgl_mulai, $tgl_selesai, $is_active = true;
    public $photo; // Untuk upload temporary
    public $existingImage; // Untuk preview saat edit

    // Variabel Pembatas Hak Akses (RBAC)
    public $canEdit = false;

    public function mount()
    {
        // Akses CRUD hanya untuk Superadmin, Operator, dan Humas
        $this->canEdit = in_array(Auth::user()->role, ['superadmin', 'operator', 'humas']);
    }

    public function render()
    {
        return view('livewire.banner-manager', [
            'banners' => Banner::latest()->paginate(8)
        ]);
    }

    public function create()
    {
        if (!$this->canEdit) return;

        $this->resetInput();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function store()
    {
        if (!$this->canEdit) return;

        $this->validate([
            'photo' => 'required|image|max:10240',
            'judul' => 'nullable|min:3',
        ]);

        $path = $this->photo->store('banners', 'public');

        Banner::create([
            'judul' => $this->judul,
            'image_path' => $path,
            'tgl_mulai' => $this->tgl_mulai,
            'tgl_selesai' => $this->tgl_selesai,
            'is_active' => $this->is_active,
        ]);

        $this->closeModal();
        session()->flash('message', 'Banner baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (!$this->canEdit) return;

        $b = Banner::find($id);
        $this->selectedId = $id;
        $this->judul = $b->judul;
        $this->tgl_mulai = $b->tgl_mulai ? $b->tgl_mulai->format('Y-m-d') : null;
        $this->tgl_selesai = $b->tgl_selesai ? $b->tgl_selesai->format('Y-m-d') : null;
        $this->is_active = $b->is_active;
        $this->existingImage = $b->image_path;

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function update()
    {
        if (!$this->canEdit) return;

        $this->validate([
            'judul' => 'nullable|min:3',
        ]);

        $b = Banner::find($this->selectedId);
        $data = [
            'judul' => $this->judul,
            'tgl_mulai' => $this->tgl_mulai,
            'tgl_selesai' => $this->tgl_selesai,
            'is_active' => $this->is_active,
        ];

        if ($this->photo) {
            if (Storage::disk('public')->exists($b->image_path)) {
                Storage::disk('public')->delete($b->image_path);
            }
            $data['image_path'] = $this->photo->store('banners', 'public');
        }

        $b->update($data);

        $this->closeModal();
        session()->flash('message', 'Banner berhasil diperbarui!');
    }

    public function deleteId($id)
    {
        if (!$this->canEdit) return;

        $this->selectedId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        if (!$this->canEdit) return; 

        $b = Banner::find($this->selectedId);
        if (Storage::disk('public')->exists($b->image_path)) {
            Storage::disk('public')->delete($b->image_path);
        }
        $b->delete();

        $this->isDeleteModalOpen = false;
        session()->flash('message', 'Banner telah dihapus!');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->judul = '';
        $this->tgl_mulai = null;
        $this->tgl_selesai = null;
        $this->is_active = true;
        $this->photo = null;
        $this->existingImage = null;
    }
}
