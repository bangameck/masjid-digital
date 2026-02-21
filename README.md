# 🕌 Masjid Digital - Smart Live TV Display Management

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)

**Masjid Digital** adalah sistem informasi manajemen terpadu berbasis web yang dirancang khusus untuk mengelola konten pada layar TV Informasi (Live Display) di Masjid. Dibangun dengan antarmuka yang modern, elegan (Emerald Glassmorphism Theme), dan sangat interaktif.

Aplikasi ini memudahkan pengurus masjid (Takmir, Humas, Operator) untuk mengatur jadwal sholat, pengumuman, petugas dakwah, hingga _running text_ secara _real-time_ tanpa perlu me-_refresh_ layar TV.

---

## ✨ Fitur Unggulan

### 🔐 1. Role-Based Access Control (RBAC) yang Ketat
Aplikasi dilengkapi dengan sistem hak akses cerdas. Hanya role **Superadmin**, **Operator**, dan **Humas** yang dapat melakukan manipulasi data (Tambah, Edit, Hapus). Role lain hanya akan melihat antarmuka mode _Read-Only_ (Lihat Saja).

### 🖼️ 2. Manajemen Banner (Smart Image Compression)
- Upload banner untuk promosi/informasi masjid.
- **Auto-Compression & Resize:** Didukung oleh algoritma _Canvas Alpine.js_ di sisi _client_ (browser). Gambar besar otomatis di-resize maksimal resolusi Full HD (1920px) dan dikompres hingga 50% sebelum dikirim ke server. Sangat menghemat _storage_!
- Status otomatis mendeteksi banner kedaluwarsa berdasarkan tanggal.

### 🕋 3. Jadwal Sholat Terintegrasi API
- Sinkronisasi otomatis dengan **API Kemenag / MyQuran** untuk akurasi waktu sholat.
- Terintegrasi dengan **API Hijriah** untuk penanggalan Islam.
- Dilengkapi animasi _Progress Bar Loading_ dan _Live Status_ (Sukses/Gagal/Error) per tanggal saat melakukan sinkronisasi satu bulan penuh.
- Fitur _Inline Edit_ untuk mengubah tanggal Hijriah secara manual jika ada selisih hari raya.

### 🎤 4. Manajemen Jadwal Dakwah (Schedule Manager)
Sistem tab rapi untuk mengelola berbagai agenda dakwah:
- **Jumat:** Mengatur Khatib, Imam, Muadzin, dan Bilal.
- **Ramadhan:** Mengatur Penceramah dan Imam berdasarkan "Malam Ke-". Otomatis memfilter daftar tanggal dari data Jadwal Sholat.
- **Sholat Ied:** Mengatur jadwal Idul Fitri & Idul Adha.
- **Pengajian Rutin:** Mengatur penceramah dan tema materi kajian.

### 📖 5. Doa & Hadist (Content Edukasi)
- Menampilkan konten edukasi agama di layar TV.
- Mendukung input teks Arab (dengan _font_ khusus Amiri RTL) dan teks terjemahan.
- Pengaturan durasi tampil per konten dan kategori (Doa / Hadist).

### 🏃‍♂️ 6. Running Text (Marquee)
- Manajemen teks berjalan dengan indikator warna sesuai kategori (Informasi, Ayat, Hadits, Ucapan).
- Pengaturan **Global Speed** dan **Individual Speed** per teks.
- Fitur _Live Preview_ di halaman admin panel sebelum teks tayang di layar TV.

### 🎨 7. Theme Color Manager
- Bebas _custom_ warna tema tampilan TV Display sesuai identitas masjid.
- Input warna _Main_, _Dark_, dan _Light_ secara presisi.

---

## 🛠️ Stack Teknologi

- **Framework Backend:** Laravel
- **Frontend Stack:** Livewire (Full-page components), Alpine.js (DOM manipulation & client-side logic)
- **Styling:** Tailwind CSS (Custom Work Sans & Amiri fonts, Glassmorphism UI)
- **Database:** MySQL / PostgreSQL

---

## 📸 Galeri & Tampilan Aplikasi

### 🖥️ Live TV Display
| Layar Start TV | Layar Utama TV Display |
| :---: | :---: |
| <img src="public/assets/screenshot/start_tv_display.png" alt="Start TV"> | <img src="public/assets/screenshot/tv_display.png" alt="TV Display"> |

### ⚙️ Login, Dashboard & Pengaturan Umum
| Halaman Login | Dashboard Admin |
| :---: | :---: |
| <img src="public/assets/screenshot/login.png" alt="Login"> | <img src="public/assets/screenshot/dashboard.png" alt="Dashboard"> |
| **Pengaturan Masjid** | **Laporan Keuangan** |
| <img src="public/assets/screenshot/settings.png" alt="Settings"> | <img src="public/assets/screenshot/keuangan.png" alt="Keuangan"> |
| **Data Pengurus** | |
| <img src="public/assets/screenshot/pengurus.png" alt="Pengurus"> | |

### 📺 Pengaturan Live Display
| Manajemen Banner | Jadwal Sholat |
| :---: | :---: |
| <img src="public/assets/screenshot/banner.png" alt="Banner"> | <img src="public/assets/screenshot/jadwa_sholat.png" alt="Jadwal Sholat"> |
| **Generate Jadwal Sholat** | **Doa & Hadist** |
| <img src="public/assets/screenshot/generate_jadwal_sholat.png" alt="Generate Jadwal"> | <img src="public/assets/screenshot/doa_hadis.png" alt="Doa Hadis"> |
| **Running Text** | **Galeri TV** |
| <img src="public/assets/screenshot/running_text.png" alt="Running Text"> | <img src="public/assets/screenshot/galeri.png" alt="Galeri"> |
| **Upload Foto Galeri** | |
| <img src="public/assets/screenshot/galeri_foto.png" alt="Foto Galeri"> | |

---

## 🚀 Panduan Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/bangameck/masjid-digital.git
   cd masjid-digital
   ```

2. **Install Dependency**

    ```bash
    composer install
    npm install
    ```

3. **Konfigurasi Environment**
   <br>Salin file .env.example menjadi .env lalu sesuaikan konfigurasi database dan variabel sistem.

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Migrasi Database & Seeder**

    ```bash
    php artisan migrate --seed
    ```

5. **Link Storage (Penting untuk Banner)**

    ```bash
    php artisan storage:link
    ```

6. **Jalankan Aplikasi**
    ```bash
    npm run build
    php artisan serve
    ```

---

## 🌐 Link Test / Demo ##

🔗 https://masjid.radevankaproject.web.id

```bash
   username : tamu@masjid.com
   password : masjid123
```
> 💡 **Catatan Penting:** Untuk pengalaman pengguna yang maksimal dan kelancaran fungsi antarmuka, **sangat disarankan untuk mengelola aplikasi ini menggunakan perangkat Desktop (PC/Laptop)**.

---

## 👨‍💻 Dikembangkan Oleh

**RadevankaProject**
<br>
[![Typing SVG](https://readme-typing-svg.demolab.com?font=Work+Sans&weight=800&size=18&pause=1000&color=10B981&vCenter=true&width=600&lines=Membangun+digitalisasi+masjid+untuk+umat...;Developer:+@bangameck;Lokasi:+Pekanbaru,+Riau)](https://git.io/typing-svg)

- 🧑‍💻 **Developer:** [@bangameck](https://instagram.com/bangameck)
- 📍 **Lokasi:** Pekanbaru, Riau, Indonesia 🇮🇩
- 🎯 **Tujuan Aplikasi ini:** *Membangun digitalisasi masjid untuk umat yang lebih baik.*

### ☕ Support Developer
Jika aplikasi **Masjid Digital** ini bermanfaat untuk masjid di tempatmu, dukung pengembangannya agar terus di-*update* dan bebas *bug*! 

Kamu bisa mentraktir saya segelas kopi melalui link di bawah ini:

<a href="https://trakteer.id/rproject" target="_blank">
  <img src="https://img.shields.io/badge/Trakteer-Traktir_Kopi_Developer-E11D48?style=for-the-badge&logo=kofi&logoColor=white" alt="Support via Trakteer">
</a>

---

## 📄 Lisensi
Hak Cipta &copy; 2026 **Masjid Digital**. Seluruh hak cipta dilindungi.
