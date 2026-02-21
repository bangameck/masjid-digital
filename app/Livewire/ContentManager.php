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
use App\Models\Content;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Manajemen Doa & Hadist')]
class ContentManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isEditMode = false;
    public $selectedId;

    // Form Properties
    public $category = 'doa';
    public $judul, $teks_arab, $teks_indo, $sumber, $durasi = 15, $is_active = true;

    public $canEdit = false;

    public function mount()
    {
        $this->canEdit = in_array(Auth::user()->role, ['superadmin', 'operator', 'humas']);
    }

    public function render()
    {
        return view('livewire.content-manager', [
            'contents' => Content::latest()->paginate(10)
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
            'judul' => 'required|min:3',
            'teks_indo' => 'required',
            'category' => 'required'
        ]);

        Content::create([
            'category' => $this->category,
            'judul' => $this->judul,
            'teks_arab' => $this->teks_arab,
            'teks_indo' => $this->teks_indo,
            'sumber' => $this->sumber,
            'durasi' => $this->durasi,
            'is_active' => $this->is_active,
        ]);

        $this->closeModal();
        session()->flash('message', 'Konten berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (!$this->canEdit) return;
        $c = Content::find($id);
        $this->selectedId = $id;
        $this->category = $c->category;
        $this->judul = $c->judul;
        $this->teks_arab = $c->teks_arab;
        $this->teks_indo = $c->teks_indo;
        $this->sumber = $c->sumber;
        $this->durasi = $c->durasi;
        $this->is_active = $c->is_active;

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function update()
    {
        if (!$this->canEdit) return;
        $this->validate(['judul' => 'required', 'teks_indo' => 'required']);

        Content::find($this->selectedId)->update([
            'category' => $this->category,
            'judul' => $this->judul,
            'teks_arab' => $this->teks_arab,
            'teks_indo' => $this->teks_indo,
            'sumber' => $this->sumber,
            'durasi' => $this->durasi,
            'is_active' => $this->is_active,
        ]);

        $this->closeModal();
        session()->flash('message', 'Konten berhasil diperbarui!');
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
        Content::find($this->selectedId)->delete();
        $this->isDeleteModalOpen = false;
        session()->flash('message', 'Konten telah dihapus!');
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
        $this->teks_arab = '';
        $this->teks_indo = '';
        $this->sumber = '';
        $this->durasi = 15;
        $this->category = 'doa';
        $this->is_active = true;
    }
}
