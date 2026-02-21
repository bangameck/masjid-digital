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
use App\Models\ThemeColor;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Manajemen Warna Tema')]
class ThemeColorManager extends Component
{
    public $colors, $selectedId;
    public $name, $label, $main_color, $dark_color, $light_color;
    public $is_edit = false;
    public $isDeleteModalOpen = false;

    // RBAC
    public $canEdit = false;

    protected $rules = [
        'name' => 'required|alpha_dash',
        'label' => 'required|string|max:50',
        'main_color' => 'required|string|max:7',
        'dark_color' => 'required|string|max:7',
        'light_color' => 'required|string|max:7',
    ];

    public function mount()
    {
        // RBAC CRUD
        $this->canEdit = in_array(Auth::user()->role, ['superadmin', 'operator', 'humas']);
        $this->resetInput();
    }

    public function render()
    {
        $this->colors = ThemeColor::all();
        return view('livewire.theme-color-manager');
    }

    public function resetInput()
    {
        $this->name = '';
        $this->label = '';
        $this->main_color = '#10b981';
        $this->dark_color = '#064e3b';
        $this->light_color = '#a7f3d0';
        $this->is_edit = false;
        $this->selectedId = null;
    }

    public function store()
    {
        if (!$this->canEdit) return;

        $this->validate(array_merge($this->rules, [
            'name' => 'required|alpha_dash|unique:theme_colors,name'
        ]));

        ThemeColor::create([
            'name' => strtolower($this->name),
            'label' => $this->label,
            'main_color' => $this->main_color,
            'dark_color' => $this->dark_color,
            'light_color' => $this->light_color,
        ]);

        session()->flash('message', 'Warna tema baru berhasil ditambahkan!');
        $this->resetInput();
    }

    public function edit($id)
    {
        if (!$this->canEdit) return;

        $color = ThemeColor::findOrFail($id);
        $this->selectedId = $id;
        $this->name = $color->name;
        $this->label = $color->label;
        $this->main_color = $color->main_color;
        $this->dark_color = $color->dark_color;
        $this->light_color = $color->light_color;
        $this->is_edit = true;
    }

    public function update()
    {
        if (!$this->canEdit) return;

        $this->validate(array_merge($this->rules, [
            'name' => 'required|alpha_dash|unique:theme_colors,name,' . $this->selectedId
        ]));

        $color = ThemeColor::findOrFail($this->selectedId);
        $color->update([
            'name' => $this->name,
            'label' => $this->label,
            'main_color' => $this->main_color,
            'dark_color' => $this->dark_color,
            'light_color' => $this->light_color,
        ]);

        session()->flash('message', 'Warna tema berhasil diperbarui!');
        $this->resetInput();
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

        ThemeColor::find($this->selectedId)->delete();
        $this->isDeleteModalOpen = false;
        session()->flash('message', 'Warna tema telah dihapus!');
        $this->resetInput();
    }

    public function closeModal()
    {
        $this->isDeleteModalOpen = false;
    }
}
