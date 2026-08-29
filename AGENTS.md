# 🤖 AGENTS.md — Panduan Konteks & Standar Pengembangan AI Agent (ITventoris)

Dokumen ini adalah **single source of truth** bagi AI Coding Agent dan developer yang bekerja pada codebase **ITventoris (Scuto Asset Management System)**. 

Setiap AI Agent yang masuk ke project ini **WAJIB membaca file ini terlebih dahulu** dan **WAJIB memperbarui bagian "Progress & Handoff Log"** di bagian akhir setelah menyelesaikan suatu tugas.

---

## 📌 1. Aturan Wajib untuk AI Agent (Agent Rules & Protocols)

1. **BACA DULU SEBELUM MENGUBAH**: Pahami alur penomoran aset, tracking, dan role permission sebelum mengedit controller/model terkait.
2. **GUNAKAN TRANSAKSI DATABASE**: Setiap operasi yang melibatkan generate nomor (`AssetCounter`, `Sequence`), perpindahan data (`Mutasi`), atau multiple table delete WAJIB dibungkus `DB::beginTransaction()` / `DB::transaction()` dengan `lockForUpdate()`.
3. **HINDARI `$request->all()`**: Gunakan selalu `$validator->validated()` atau `$request->only(...)` demi keamanan *Mass Assignment*.
4. **RESPON AJAX KONSISTEN**: Semua endpoint AJAX harus mengembalikan response JSON yang jelas (`{success: true/false, message/errors: ...}`) dengan HTTP status code yang tepat (200, 201, 403, 422, 500).
5. **UPDATE PROGRESS LOG**: Setelah menyelesaikan instruksi user, tambahkan catatan perubahan ke tabel **[Progress & Handoff Log](#-8-progress--handoff-log)**.

---

## 🏗️ 2. Tech Stack & Environment

- **Framework**: Laravel 12.x (PHP 8.2+)
- **Database**: MySQL / SQLite (dukungan DBAL)
- **Frontend**: Blade Templating, Vite, Vanilla JavaScript (Modular ES6), CSS Custom Properties
- **Library Pendukung**:
  - `barryvdh/laravel-dompdf`: Export Berita Acara Serah Terima (BST) ke PDF
  - `spatie/simple-excel`: Export data inventaris ke format XLSX
  - `FontAwesome 5`: Ikon antarmuka
- **Timezone**: `Asia/Jakarta` (WIB)
- **Locale**: `id` (Bahasa Indonesia)

---

## 👥 3. Matriks Role & Hak Akses (Authorization)

| Fitur / Aksi | Super Admin (`super_admin`) | Admin (`admin`) | User Biasa (`user`) |
| :--- | :---: | :---: | :---: |
| **Dashboard & Cari Aset Realtime** | ✅ | ✅ | ✅ (View Only) |
| **Lihat Detail & Riwayat Tracking Aset** | ✅ | ✅ | ✅ (View Only) |
| **Export Excel Data Aset** | ✅ | ✅ | ✅ |
| **Tambah Aset Baru** | ✅ | ✅ | ❌ |
| **Edit / Hapus Aset** | ✅ | ❌ | ❌ |
| **Update Status / Serah Terima Aset** | ✅ | ✅ | ❌ |
| **Edit / Hapus Riwayat Tracking** | ✅ | ❌ | ❌ |
| **Buat Surat Serah Terima (BST)** | ✅ | ✅ | ❌ |
| **Download PDF Surat BST** | ✅ | ✅ | ❌ |
| **Edit / Hapus Surat BST** | ✅ | ❌ | ❌ |
| **Hapus Riwayat Mutasi** | ✅ | ❌ | ❌ |
| **Master Data (Perusahaan & Jenis)** | ✅ (CRUD Penuh) | ❌ (View Only) | ❌ (View Only) |
| **Manajemen User** | ✅ (Semua Role) | ✅ (Role User saja) | ❌ |

- **Middleware**: `is_admin` (`App\Http\Middleware\IsAdmin`) melindungi endpoint Admin & Super Admin. Jika unauthorized dan request berupa AJAX/JSON, kembalikan respon `403 JSON`.

---

## 🏷️ 4. Logika Bisnis Utama (Core Business Logic)

### A. Format Penomoran Aset Otomatis (`no_asset`)
Format: `{SINGKATAN_PERUSAHAAN}/{SINGKATAN_JENIS}/{TAHUN}/{BULAN}/{NOMOR_4_DIGIT}`
- *Contoh*: `SCT/LPT/2025/08/0001`
- *Mekanisme*: Mengambil counter dari tabel `asset_counters` berdasarkan `perusahaan_id`. Gunakan `lockForUpdate()` saat increment untuk mencegah race condition.

### B. Format Penomoran Surat Serah Terima (`no_surat`)
Format: `BST/{TAHUN}/{BULAN}/{NOMOR_3_DIGIT}`
- *Contoh*: `BST/2025/08/001`
- *Mekanisme*: Menggunakan model `Sequence` dengan key `surat_nomor_YYYY_MM` dan `lockForUpdate()`.

### C. Alur Lifecycle Status & Tracking Aset
1. **Pembuatan Aset Baru**:
   - Status awal: `tersedia`.
   - `Barang::booted()` otomatis membuat entri `Track` pertama (username: `-`, status: `tersedia`, tanggal_awal: `now()`).
2. **Serah Terima / Perubahan Status**:
   - Status yang valid: `digunakan`, `diperbaiki`, `dipindah`, `non aktif`, `tersedia`.
   - Menutup track sebelumnya dengan mengeset `tanggal_ahir = now()`.
   - Membuat entri `Track` baru dengan data user, keterangan, dan status baru.
   - Status di tabel `barang` otomatis sinkron via `TrackObserver`.
3. **Mutasi Antar Perusahaan (`status = dipindah`)**:
   - Validasi perusahaan tujuan tidak boleh sama dengan perusahaan asal.
   - Generate nomor aset baru untuk perusahaan tujuan.
   - Catat log perpindahan ke tabel `mutasis` (mencatat `barang_id`, `no_asset_lama`, `perusahaan_lama_id`, `pengguna_lama`, `no_asset_baru`, `perusahaan_baru_id`, `pengguna_baru`, `tanggal_mutasi`).
   - Update `perusahaan_id` dan `no_asset` pada model `Barang`.

---

## 📂 5. Peta Direktori & File Kunci

```text
ITventoris/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/LoginController.php       # Autentikasi masuk/keluar
│   │   │   ├── BarangController.php          # Store aset baru & preview nomor seri
│   │   │   ├── DashboardController.php       # Query tabel aset, filter, export excel, serah terima
│   │   │   ├── ManagePageController.php      # CRUD User & AJAX realtime field validation
│   │   │   ├── MutasiController.php          # Data mutasi barang (list, detail, destroy)
│   │   │   ├── PerusahaanPageController.php  # Master data perusahaan (AJAX CRUD)
│   │   │   ├── JenisBarangController.php     # Master data jenis barang (AJAX CRUD)
│   │   │   └── SuratController.php           # CRUD Surat BST, PDF generator (DomPDF)
│   │   └── Middleware/
│   │       └── IsAdmin.php                   # Proteksi hak akses Admin/Super Admin
│   ├── Models/                               # Barang, Track, Surat, Mutasi, Perusahaan, User, dll
│   ├── Observers/
│   │   ├── ActivityLogObserver.php           # Audit log otomatis (filter data sensitif)
│   │   └── TrackObserver.php                 # Auto-sync status barang saat track dibuat
│   └── Traits/
│       └── LogsActivity.php                  # Trait untuk model yang dipantau audit log
├── resources/
│   ├── css/                                  # Styling per halaman (dashboard, serah_terima, dll)
│   ├── js/                                   # Logika frontend vanilla JS per halaman
│   └── views/
│       ├── DasboardPage.blade.php            # Tampilan utama tabel inventaris & modal aksi
│       ├── SerahTerimaPage.blade.php         # Tampilan serah terima BST & riwayat mutasi
│       ├── PerusahaanPage.blade.php          # Tampilan master data (tab Perusahaan & Jenis)
│       ├── UserManagePage.blade.php          # Tampilan manajemen user
│       ├── LoginPage.blade.php               # Halaman login
│       ├── serah_terima.blade.php            # Template HTML dokumen PDF cetak BST
│       └── partials/                         # Komponen sidebar dan rows AJAX
└── routes/
    └── web.php                               # Definisi seluruh routing aplikasi
```

---

## ⚡ 6. Standar & Konvensi Penulisan Kode

1. **Keamanan Data**:
   - Model `User` menyembunyikan `password` dan `remember_token` di `$hidden`.
   - `ActivityLogObserver` otomatis menghapus field `$hidden` sebelum menulis ke tabel `activity_logs`.
2. **Kerapihan Blade**:
   - Pastikan setiap tag pembuka container memiliki penutup yang pas.
   - Hindari menulis placeholder syntax JavaScript (seperti `${...}`) di dalam HTML statis Blade.
   - Nama file view wajib menggunakan ekstensi huruf kecil `.blade.php`.
3. **Validasi & Error Handling**:
   - Berikan pesan error bahasa Indonesia yang jelas pada Form Request / Validator.
   - Gunakan `try - catch` dengan `Log::error(...)` untuk menangani exception di controller.

---

## 💻 7. Perintah Pengembangan (Development Commands)

```bash
# === Local Tanpa Docker ===
php artisan serve
npm run dev
php artisan migrate
php -l app/Http/Controllers/DashboardController.php

# === Menjalankan dengan Docker ===
# Build & jalankan semua container (app, webserver, db, phpmyadmin)
docker compose up -d --build

# Menjalankan migrasi database di container
docker compose exec app php artisan migrate

# Menjalankan composer install / update di container
docker compose exec app composer install

# Menjalankan Vite build di container
docker compose exec app npm run build
# atau untuk realtime dev asset watching
docker compose exec app npm run dev

# Melihat status container & logs
docker compose ps
docker compose logs -f app
```

---

## 📝 8. Progress & Handoff Log

Setiap AI Agent atau developer yang melakukan modifikasi kode **harus menambahkan entri baru di tabel ini** sebelum sesi berakhir.

### Format Pengisian:
- **Tanggal & Waktu**: Waktu perubahan.
- **Agent / Developer**: Nama atau ID Agent.
- **Status Tugas**: `[DONE]` / `[IN PROGRESS]` / `[BLOCKED]`.
- **Perubahan yang Dilakukan**: Rincian file dan perbaikan yang dibuat.
- **Catatan untuk Agent Selanjutnya**: Instruksi atau hal yang perlu diperhatikan berikutnya.

---

### Riwayat Aktivitas:

| **2026-08-29 (10)** | **Antigravity AI** | `DONE` | • **Efek Hover Merah Tombol Batal Dashboard**: Menerapkan aturan CSS `:hover` berwarna merah (`#ef4444`, `box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35)`) pada tombol Batal / `.modal-footer .btn-secondary` di seluruh modal Dashboard, sekaligus mempertahankan hover neutral untuk tombol aksi non-batal seperti *Riwayat Pengguna*. | Seluruh tombol Batal di modal Dashboard kini secara konsisten berubah warna menjadi merah tegas saat di-hover. |
| **2026-08-29 (9)** | **Antigravity AI** | `DONE` | • **Perbaikan Tombol Modal Detail Perangkat**: Menerapkan style `.btn`, `.btn-primary`, `.btn-secondary` universal pada `dashboard.css` (termasuk untuk container `.action-buttons`) sehingga tombol "Riwayat Pengguna" dan "Serah Terima / Update" berpenampilan modern dengan warna & efek hover yang sesuai.<br>• **Perbaikan Navigasi Modal Riwayat Pengguna**: Menghapus inline style `historyModal.style.display = 'flex'` yang sebelumnya mengunci modal agar tidak bisa ditutup/di-back, menambahkan fungsi `style.removeProperty('display')` pada `closeModalElement`, serta menambahkan tombol footer *"Kembali"* (`#closeUserHistoryModalFooterBtn`). | Tombol di modal detail perangkat kini tampil presisi dan rapi, serta modal riwayat pengguna dapat ditutup/kembali dengan lancar baik lewat tombol `×`, tombol *Kembali*, maupun tombol Escape. |
| **2026-08-29 (8)** | **Antigravity AI** | `DONE` | • **Penyempurnaan Tuntas Style Modal Dashboard (Persis Tambah Surat Serah Terima)**: Memperbaiki CSS kartu `.form-section` dan `.detail-section` pada `dashboard.css` (background card `rgba(13, 19, 34, 0.6)`, border halus, title `h3` biru `#3b82f6` tanpa border-bottom berlebih, input `#0d1322` dengan border `rgba(255,255,255,0.12)`, 2-column `.form-group-grid`).<br>• **Perbaikan Build Vite & Dependency Rollup**: Mengatasi error dependency `@rollup/rollup-darwin-arm64`, membersihkan re-deklarasi variabel pada `dashboard.js`, serta melakukan re-bundle penuh `npm run build` sehingga seluruh asset CSS dan JS terbaru termuat sempurna di browser. | Seluruh modal di Dashboard (Tambah Aset, Edit Aset, Detail Perangkat, Serah Terima, Riwayat) kini 100% presisi dan identik visualnya dengan modal Tambah Surat Serah Terima. |
| **2026-08-29 (7)** | **Antigravity AI** | `DONE` | • **Penyelarasan Total Modal Dashboard & Detail Surat Serah Terima**: Menyamakan 100% struktur HTML, CSS, dan JS seluruh modal di Dashboard (`#deviceInfoModal`, `#addAssetModal`, `#editAssetModal`, `#serahTerimaAsetModal`, `#userHistoryModal`) dengan modal standarisasi Detail Surat Serah Terima (`detailSuratModal` & `suratModal`).<br>• **Pembersihan Logika Lama & Duplikasi**: Menghapus seluruh selector usang (`.morph-modal-title`, legacy `.morph-modal-body`), menghapus event listener duplikat dan manipulasi `style.display = 'none/flex'`, serta beralih penuh ke manajemen kelas standar `.modal.show`, `body.modal-open`, dan fungsi universal `openModalElement` / `closeModalElement`. | Seluruh modal dashboard kini 100% presisi mengikuti standarisasi modal Serah Terima Aset, bersih dari kode tumpukan legacy. |
| **2026-08-29 (6)** | **Antigravity AI** | `DONE` | • **Unifikasi Style Modal Dashboard**: Menyelaraskan seluruh modal di halaman Dashboard (`#deviceInfoModal`, `#addAssetModal`, `#editAssetModal`, `#serahTerimaAsetModal`, `#userHistoryModal`) dengan modal di halaman Serah Terima Aset.<br>• **Standardisasi Markup & CSS**: Menggunakan header `.modal-header-content` + `.modal-header-icon`, close button `.close-button`, kartu section `.form-section` & `.detail-section`, list info `.detail-item`, box info barang `.detail-barang-box`, serta membersihkan rules CSS legacy/duplikat pada `dashboard.css`. | Seluruh modal di Dashboard dan Serah Terima kini memiliki hierarki visual, warna, font Poppins, radius, backdrop blur, dan animasi yang 100% seragam. |
| **2026-08-29 (5)** | **Antigravity AI** | `DONE` | • **Detail Perangkat & Edit Aset UI Overhaul**: Menyesuaikan tampilan modal Detail Perangkat (`#deviceInfoModal`), Edit Aset (`#editAssetModal`), Tambah Aset (`#addAssetModal`), Serah Terima Aset (`#serahTerimaAsetModal`), dan Riwayat Pengguna (`#userHistoryModal`) dengan layout kartu tersegmentasi (`.detail-section`, `.form-section`) sesuai desain referensi.<br>• **Visual Polish & Styling**: Judul section biru tegas (`#2869ff`), container kartu `#0b1322` dengan border halus `1px solid rgba(255,255,255,0.07)`, list pasangan key-value (`.detail-item dt` & `dd`) rapi, serta box icon perangkat `.detail-barang-box`. | Desain modal Detail Perangkat dan Edit Aset kini 100% presisi mengikuti referensi gambar dengan kontras dan hierarki yang jelas. |
| **2026-08-29 (4)** | **Antigravity AI** | `DONE` | • **Backdrop Blur & Glassmorphism Overlay**: Menambahkan `backdrop-filter: blur(8px)` dan warna overlay `rgba(8, 12, 22, 0.75)` pada seluruh modal (`.modal` dan `.modal-overlay`).<br>• **Harmonisasi Modal & Color Palette**: Menyelaraskan seluruh modal dialog di Dashboard, Serah Terima, Master Data, dan User Manage dengan surface `#141c2e` (Dark) / `#ffffff` (Light), border `1px solid rgba(255,255,255,0.1)` / `#e2e8f0`, soft outer shadow `0 20px 50px -10px rgba(0,0,0,0.65)`, dan animasi slide-up halus (`@keyframes modalSlideUp`).<br>• **Grid & Form Controls**: Menerapkan 2-column `.form-grid` pada modal tambah/edit aset, membersihkan inline style `margin-bottom` dan background hardcoded di modal serah terima, user manage, dan data master. | Seluruh modal di semua halaman kini menggunakan token visual dan efek blur yang konsisten 100%. |
| **2026-08-29 (3)** | **Antigravity AI** | `DONE` | • **Status Filter Dashboard**: Menambahkan filter status aset (`tersedia`, `digunakan`, `diperbaiki`, `dipindah`, `non aktif`) pada `DashboardController`, `DasboardPage.blade.php`, `dashboard.js`, serta export Excel.<br>• **Rate Limiting Login**: Menambahkan proteksi brute force (5 kali per 60 detik) di `LoginController.php`.<br>• **UI Harmonization & Consistency**: Menyelaraskan seluruh styling modal, border radius (10px modal, 6px input/button), font-family Poppins, focus ring, menghapus CDN Bootstrap 4 yang bentrok di `PerusahaanPage.blade.php`, dan membersihkan inline style di `UserManagePage.blade.php`. | Seluruh halaman UI kini seragam dalam tipografi, radius, modal dialog, dan input. Filter status di dashboard sudah reaktif secara realtime via AJAX. |
| **2026-08-29 (2)** | **Antigravity AI** | `DONE` | • **Docker Integration**: Menambahkan `Dockerfile` (PHP 8.2 FPM, Composer, Node 20, ekstensi gd, zip, pdo_mysql, intl, bcmath, exif).<br>• **Docker Compose**: Membuat `docker-compose.yml` dengan service `app`, `webserver` (Nginx), `db` (MySQL 8), dan `phpmyadmin`.<br>• **Nginx & PHP Config**: Menambahkan `.docker/nginx/default.conf`, `.docker/php/local.ini`, `.docker/entrypoint.sh`, dan `.dockerignore`.<br>• **Environment Config**: Memperbarui `.env.example` dengan kredensial default Docker. | Semua file Docker sudah siap. User/Agent berikutnya bisa menjalankan aplikasi via `docker compose up -d --build`. Akses web di port 8080 dan phpMyAdmin di port 8081. |
| **2026-08-29 (1)** | **Antigravity AI** | `DONE` | • **Fix Bug Mutasi**: Memperbaiki urutan `$previousTrack` di `DashboardController` agar `pengguna_lama` tidak undefined.<br>• **Pembersihan Controller**: Menghapus method `store` duplikat di `DashboardController`, menggunakan `$validator->validated()` pada update.<br>• **Middleware IsAdmin**: Menambahkan respon JSON `403` untuk request AJAX/API.<br>• **Security Log**: Memfilter atribut `$hidden` pada `ActivityLogObserver`.<br>• **Model Barang**: Menghapus duplikasi key `$fillable`.<br>• **Routing Cleanup**: Hapus debug route `/props`, spesifikasi method pada `users` resource (`only: index, store, update, destroy`).<br>• **Blade Fixes**: Rename `LoginPage.blade.php`, perbaiki typo role option di `UserManagePage`, tutup tag div di `DasboardPage`, perbaiki typo di `serah_terima.blade.php` & `SerahTerimaPage`.<br>• **Config**: Set timezone ke `Asia/Jakarta`.<br>• **Dokumentasi**: Membuat file panduan arsitektur `AGENTS.md`. | Struktur kode sudah bersih dan lolos verifikasi sintaks. Jika user meminta fitur baru, ikuti panduan arsitektur dan format penomoran di dokumen ini. |

---
*(Tambahkan baris baru di atas saat ada pembaruan berikutnya)*
