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
use App\Models\User;
use App\Models\Pengurus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Manajemen SDM Masjid')]
class UserPengurus extends Component
{
    use WithPagination, WithFileUploads;

    public $activeTab = 'pengurus';
    public $search = '';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isEditMode = false;
    public $selectedId;

    public $name, $email, $password, $role = 'superadmin';
    public $nama_pengurus, $jabatan, $no_hp, $is_active = true;
    public $foto, $foto_path;

    // Variabel Pembatas Hak Akses
    public $canEdit = false;

    public function mount()
    {
        // Hanya Superadmin & Operator yang bisa CRUD
        $this->canEdit = in_array(Auth::user()->role, ['superadmin', 'operator']);
    }

    public function render()
    {
        $data = [];
        if ($this->activeTab == 'users') {
            // Proteksi: Hanya superadmin yang boleh lihat tab ini
            if (Auth::user()->role !== 'superadmin') {
                $this->activeTab = 'pengurus';
            } else {
                // Tampilkan hanya superadmin murni
                $data = User::where('role', 'superadmin')
                    ->where(function($q) {
                        $q->where('name', 'like', '%'.$this->search.'%')
                          ->orWhere('email', 'like', '%'.$this->search.'%');
                    })->latest()->paginate(10);
            }
        }

        if ($this->activeTab == 'pengurus') {
            $data = Pengurus::with('user')
                ->where('nama', 'like', '%'.$this->search.'%')
                ->orWhere('jabatan', 'like', '%'.$this->search.'%')
                ->latest()->paginate(10);
        }

        return view('livewire.user-pengurus', ['data' => $data]);
    }

    public function switchTab($tab)
    {
        // Cegah user biasa klik tab ini
        if ($tab == 'users' && Auth::user()->role !== 'superadmin') {
            session()->flash('error', 'Akses ditolak! Hanya Superadmin.');
            return;
        }

        $this->activeTab = $tab;
        $this->resetPage();
        $this->resetInput();
    }

    public function create()
    {
        if (!$this->canEdit) return; // Proteksi Gembok

        $this->resetInput();
        $this->isEditMode = false;
        $this->role = ($this->activeTab == 'users') ? 'superadmin' : ''; // Role dikosongkan untuk pengurus (otomatis dari jabatan)
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->name = ''; $this->email = ''; $this->password = ''; $this->role = 'superadmin';
        $this->nama_pengurus = ''; $this->jabatan = ''; $this->no_hp = ''; $this->is_active = true;
        $this->foto = null; $this->foto_path = null;
    }

    public function messages()
    {
        return [
            'required' => 'Kolom ini wajib diisi ya, Wak.',
            'email' => 'Format email tidak valid (harus ada @).',
            'email.unique' => 'Email ini sudah dipakai, gunakan yang lain.',
            'min' => 'Isian terlalu pendek, minimal :min karakter.',
            'image' => 'File harus berupa gambar (JPG/PNG).',
        ];
    }

    public function validationAttributes()
    {
        return [
            'name' => 'Nama Superadmin', 'nama_pengurus' => 'Nama Pengurus',
            'email' => 'Alamat Email', 'password' => 'Kata Sandi',
            'jabatan' => 'Jabatan', 'foto' => 'Foto Profil',
        ];
    }

    public function store()
    {
        if (!$this->canEdit) return; // Proteksi Gembok

        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ];

        if ($this->activeTab == 'users') {
            if (Auth::user()->role !== 'superadmin') abort(403);
            $rules['name'] = 'required';
        } else {
            $rules['nama_pengurus'] = 'required';
            $rules['jabatan'] = 'required';
            $rules['foto'] = 'nullable|image|max:2048';
            // Validasi 'role' dihapus karena akan digenerate otomatis dari jabatan
        }

        $this->validate($rules, $this->messages(), $this->validationAttributes());

        DB::beginTransaction();
        try {
            if ($this->activeTab == 'users') {
                User::create([
                    'name' => $this->name, 'email' => $this->email,
                    'password' => Hash::make($this->password), 'role' => 'superadmin',
                ]);
            } else {
                $path = $this->foto ? $this->foto->store('pengurus', 'public') : null;

                $pengurus = Pengurus::create([
                    'nama' => $this->nama_pengurus, 'jabatan' => $this->jabatan,
                    'no_hp' => $this->no_hp, 'foto_path' => $path, 'is_active' => $this->is_active,
                ]);

                // LOGIKA BARU: Role otomatis mengambil dari Jabatan dan dijadikan huruf kecil
                $generatedRole = strtolower($this->jabatan);

                User::create([
                    'name' => $this->nama_pengurus, 'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'role' => $generatedRole,
                    'pengurus_id' => $pengurus->id,
                ]);
            }
            DB::commit();
            $this->closeModal();
            session()->flash('message', 'Alhamdulillah, data berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Sistem Gagal: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!$this->canEdit) return; // Proteksi Gembok

        $this->isEditMode = true;
        $this->selectedId = $id;

        if ($this->activeTab == 'users') {
            if (Auth::user()->role !== 'superadmin') abort(403);
            $user = User::where('role', 'superadmin')->find($id);
            if(!$user) {
                $this->closeModal();
                session()->flash('error', 'Data superadmin tidak ditemukan!');
                return;
            }
            $this->name = $user->name; $this->email = $user->email; $this->role = $user->role;
        } else {
            $p = Pengurus::with('user')->find($id);
            if(!$p) return;
            $this->nama_pengurus = $p->nama; $this->jabatan = $p->jabatan;
            $this->no_hp = $p->no_hp; $this->foto_path = $p->foto_path; $this->is_active = $p->is_active;

            if ($p->user) {
                $this->email = $p->user->email; $this->role = $p->user->role;
            }
        }
        $this->isModalOpen = true;
    }

    public function update()
    {
        if (!$this->canEdit) return; // Proteksi Gembok

        $userIdToCheck = $this->activeTab == 'users'
            ? $this->selectedId
            : (Pengurus::find($this->selectedId)->user->id ?? null);

        $emailRule = $userIdToCheck
            ? 'required|email|unique:users,email,' . $userIdToCheck
            : 'required|email|unique:users,email';

        $rules = ['email' => $emailRule];

        if ($this->activeTab == 'users') {
            if (Auth::user()->role !== 'superadmin') abort(403);
            $rules['name'] = 'required';
        } else {
            $rules['nama_pengurus'] = 'required';
            $rules['jabatan'] = 'required';
            $rules['foto'] = 'nullable|image|max:2048';
        }

        $this->validate($rules, $this->messages(), $this->validationAttributes());

        DB::beginTransaction();
        try {
            if ($this->activeTab == 'users') {
                $user = User::find($this->selectedId);
                $userData = ['name' => $this->name, 'email' => $this->email];
                if (!empty($this->password)) $userData['password'] = Hash::make($this->password);
                $user->update($userData);
            } else {
                $pengurus = Pengurus::with('user')->find($this->selectedId);
                $path = $pengurus->foto_path;

                if ($this->foto) {
                    if ($path && Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                    $path = $this->foto->store('pengurus', 'public');
                }

                $pengurus->update([
                    'nama' => $this->nama_pengurus, 'jabatan' => $this->jabatan,
                    'no_hp' => $this->no_hp, 'foto_path' => $path, 'is_active' => $this->is_active,
                ]);

                // LOGIKA BARU: Update Role juga jika jabatan diubah
                $generatedRole = strtolower($this->jabatan);

                if ($pengurus->user) {
                    $userData = ['name' => $this->nama_pengurus, 'email' => $this->email, 'role' => $generatedRole];
                    if (!empty($this->password)) $userData['password'] = Hash::make($this->password);
                    $pengurus->user->update($userData);
                } else {
                    User::create([
                        'name' => $this->nama_pengurus, 'email' => $this->email,
                        'password' => Hash::make($this->password ?? 'password123'),
                        'role' => $generatedRole, 'pengurus_id' => $pengurus->id,
                    ]);
                }
            }
            DB::commit();
            $this->closeModal();
            session()->flash('message', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function deleteId($id) {
        if (!$this->canEdit) return; // Proteksi Gembok
        if ($this->activeTab == 'users' && Auth::user()->role !== 'superadmin') abort(403);

        $this->selectedId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        if (!$this->canEdit) return; // Proteksi Gembok

        DB::beginTransaction();
        try {
            if ($this->activeTab == 'users') {
                User::find($this->selectedId)->delete();
            } else {
                $pengurus = Pengurus::with('user')->find($this->selectedId);
                if ($pengurus->foto_path && Storage::disk('public')->exists($pengurus->foto_path)) {
                    Storage::disk('public')->delete($pengurus->foto_path);
                }
                if ($pengurus->user) $pengurus->user->delete();
                $pengurus->delete();
            }
            DB::commit();
            $this->isDeleteModalOpen = false;
            session()->flash('message', 'Data berhasil dihapus permanen!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id) {
        if (!$this->canEdit) return; // Proteksi Gembok
        $p = Pengurus::find($id); $p->is_active = !$p->is_active; $p->save();
    }
}
