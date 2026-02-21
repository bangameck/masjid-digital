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
use App\Models\RunningText;
use Livewire\WithPagination;
use App\Models\AppSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Manajemen Running Text')]
class RunningTextManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isEditMode = false;
    public $selectedId;

    // Form Properties
    public $teks, $tipe = 'info', $is_active = true, $urutan = 0;
    public $kecepatan = 5;
    public $canEdit = false;

    public function mount()
    {
        $this->canEdit = in_array(Auth::user()->role, ['superadmin', 'operator', 'humas']);
    }
    public function render()
    {
        // Ambil Data Running Text
        $data = RunningText::orderBy('urutan', 'asc')->latest()->paginate(10);

        // Ambil Global Speed dari AppSetting
        // Default value (misal 5) jika tabel kosong
        $setting = AppSetting::first();
        $globalSpeed = $setting->running_text_speed ?? 5;

        return view('livewire.running-text-manager', [
            'data' => $data,
            'globalSpeed' => $globalSpeed // Kirim ke View
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
            'teks' => 'required|min:5',
            'tipe' => 'required',
            'kecepatan' => 'required|numeric|min:1|max:10',
        ]);

        RunningText::create([
            'teks' => $this->teks,
            'tipe' => $this->tipe,
            'is_active' => $this->is_active,
            'urutan' => RunningText::max('urutan') + 1,
            'kecepatan' => $this->kecepatan,
        ]);

        $this->closeModal();
        session()->flash('message', 'Teks berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (!$this->canEdit) return;
        $this->isEditMode = true;
        $this->selectedId = $id;
        $rt = RunningText::find($id);
        $this->teks = $rt->teks;
        $this->tipe = $rt->tipe;
        $this->is_active = $rt->is_active;
        $this->urutan = $rt->urutan;
        $this->kecepatan = $rt->kecepatan;
        $this->isModalOpen = true;
    }

    public function update()
    {
        if (!$this->canEdit) return;
        $this->validate(['teks' => 'required', 'tipe' => 'required']);

        RunningText::find($this->selectedId)->update([
            'teks' => $this->teks,
            'tipe' => $this->tipe,
            'is_active' => $this->is_active,
            'urutan' => $this->urutan,
            'kecepatan' => $this->kecepatan,
        ]);

        $this->closeModal();
        session()->flash('message', 'Teks diperbarui!');
    }

    public function toggleStatus($id)
    {
        $rt = RunningText::find($id);
        $rt->is_active = !$rt->is_active;
        $rt->save();
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
        RunningText::find($this->selectedId)->delete();
        $this->isDeleteModalOpen = false;
        session()->flash('message', 'Teks dihapus!');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->teks = '';
        $this->tipe = 'info';
        $this->is_active = true;
        $this->urutan = 0;
        $this->kecepatan = 5;
    }
}
