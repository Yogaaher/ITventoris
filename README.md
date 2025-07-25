# Sistem Manajemen Inventaris IT

Aplikasi web untuk melacak perangkat IT yang digunakan oleh berbagai divisi dalam perusahaan.

## Fitur Utama

- CRUD data perangkat pada IT
- Tracking pengguna perangkat
- Mutasi perangkat antar perusahaan
- Pembuatan No asset Otomatis
- Dapat merubah perusahaan dinamis
- User & Admin Leveling
- User Management

## Teknologi yang Digunakan

- Laravel 12.15.0
- MySQL

## Struktur Folder Penting

📁 ITventory/
├── 📁 app/
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/
│   │   │   ├── 📁 Auth/
│   │   │   │   └── LoginController.php
│   │   │   ├── BarangController.php
│   │   │   ├── Controller.php
│   │   │   ├── DashboardController.php
│   │   │   ├── JenisBarangController.php
│   │   │   ├── ManagePageController.php
│   │   │   ├── MutasiController.php
│   │   │   ├── PerusahaanPageController.php
│   │   │   └── SuratController.php
│   │   └── 📁 Middleware/
│   │       └── IsAdmin.php
│   ├── 📁 Models/
│   │   ├── AssetCounter.php
│   │   ├── Barang.php
│   │   ├── JenisBarang.php
│   │   ├── Mutasi.php
│   │   ├── Perusahaan.php
│   │   ├── Sequence.php
│   │   ├── Surat.php
│   │   ├── Track.php
│   │   └── User.php
│   ├── 📁 Observers/
│   │   └── TrackObserver.php
│   └── 📁 Providers/
│       └── AppServiceProvider.php
│
├── 📁 database/
│   ├── 📁 factories/
│   │   └── UserFactory.php
│   ├── 📁 migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── ... (beberapa file migration lainnya)
│   └── 📁 seeders/
│       ├── BarangSeeder.php
│       ├── DatabaseSeeder.php
│       ├── JenisBarangSeeder.php
│       ├── PerusahaanSeeder.php
│       ├── TrackSeeder.php
│       └── UserSeeder.php
│
├── 📁 public/
│   └── 📁 img/
│   │   ├── Background.jpg
│   │   ├── Logo-scuto.png
│   │   └── Scuto-logo.svg
│
├── 📁 resources/
│   ├── 📁 css/
│   │   ├── app.css
│   │   ├── dashboard.css
│   │   ├── perusahaan.css
│   │   ├── serah_terima.css
│   │   └── usermanage.css
│   ├── 📁 js/
│   │   ├── app.js
│   │   ├── bootstrap.js
│   │   ├── dashboard.js
│   │   ├── perusahaan.js
│   │   ├── serah_terima.js
│   │   └── usermanage.js
│   └── 📁 views/
│       ├── 📁 partials/
│       │   ├── company_table_rows.blade.php
│       │   ├── item_type_table_rows.blade.php
│       │   ├── sidebar.blade.php
│       │   └── user_table_rows.blade.php
│       ├── DasboardPage.blade.php
│       ├── LoginPage.blade.php
│       ├── PerusahaanPage.blade.php
│       ├── serah_terima.blade.php
│       ├── SerahTerimaPage.blade.php
│       └── UserManagePage.blade.php
│
├── 📁 routes/
│   ├── console.php
│   └── web.php


## 🛠️ Cara Instalasi dan Menjalankan Proyek Secara Lokal

1. **Clone Repository**

   ```bash
   git clone https://github.com/Yogaaher/ITventoris.git
   cd ITventoris
   ```

2. **Install Dependency Laravel**

   Jalankan perintah berikut untuk meng-install semua dependency utama Laravel:

   ```bash
   composer install
   ```

3. **Install Beberapa Library Tambahan**

   Berikut adalah package tambahan yang perlu diinstall:

   ```bash
   composer require maatwebsite/excel
   composer require barryvdh/laravel-dompdf
   composer require barryvdh/laravel-ide-helper
   composer require doctrine/dbal
   ```

4. **Copy File Environment (.env)**

   ```bash
   cp .env.example .env
   ```

5. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi Database**

   - Buat database baru di MySQL (misalnya: `itventory`)
   - Edit file `.env` dan sesuaikan bagian berikut:

     ```
     DB_DATABASE=itventory
     DB_USERNAME=root
     DB_PASSWORD=your_password
     ```

7. **Migrasi dan Seeder Database**

   ```bash
   php artisan migrate --seed
   ```

8. **Jalankan Server Lokal**

   ```bash
   php artisan serve
   ```

9. **Akses Aplikasi**

   Buka browser dan akses:

   ```
   http://localhost:8000
   ```

## Database Setup Manual (Opsional)

1. **UserSeeder.php**

   ```php
   User::create([
       'name' => 'Admin Utama',
       'email' => 'admin@example.com',
       'password' => Hash::make('password123'),
       'role' => 'admin',
       'email_verified_at' => now(),
   ]);
   ```

2. **JenisBarangSeeder.php**

   ```php
   DB::table('jenis_barangs')->insert([
       [
           'id' => 1,
           'nama_jenis' => 'Laptop',
           'singkatan' => 'LTP'
           'icon' => 'fas fa-=laptop', **gunakan font awesome icon**
           'created_at' => now(),
           'updated_at' => now(),
       ],
   ]);
   ```

3. **PerusahaanSeeder.php**
   ```php
   DB::table('perusahaans')->insert([
       [
           'id' => 1,
           'nama_perusahaan' => 'PT SCUTO INDONESIA',
           'singkatan' => 'SCO',
           'created_at' => now(),
           'updated_at' => now(),
       ],
   ]);
   ```

## 👤 Hak Akses Berdasarkan Role

### 🔑 Super Admin
Super Admin memiliki akses penuh ke sistem, termasuk fitur manajemen data dan pengguna.

- **Dashboard**:  
  - Menambahkan data asset baru  
  - Melakukan update data asset (termasuk proses serah terima)  
  - Menghapus dan mengedit data asset yang sudah ada  

- **Serah Terima Asset**:  
  - Menambahkan surat serah terima baru
  - Melakukan edit dan hapus surat serah terima  
  - Mendownload file surat serah terima  

- **Mutasi Barang**:  
  - Dapat menghapus data mutasi  

- **Data Master** (Jenis Barang, Perusahaan):  
  - Dapat menambahkan perusahaan baru
  - Dapat edit dan hapus data perusahaan
  - Dapat menambahkan jenis barang baru
  - Dapat edit dan hapus jenis barang  

- **Manajemen Pengguna (User Management)**:  
  - Menambahkan pengguna baru (semua role)
  - Mengedit data pengguna  
  - Menghapus pengguna dari sistem  

---

### 🛠️ Admin
Admin memiliki peran operasional yang hampir sama dengan Super Admin, namun tanpa akses administratif seperti pengelolaan sistem.

- **Dashboard**:  
  - Menambahkan data asset baru  
  - Melakukan update data asset (termasuk proses serah terima)  

- **Serah Terima Asset**:  
  - Menambahkan surat serah terima baru  
  - Mendownload file surat serah terima  

- **Mutasi Barang**:  
  - Hanya dapat melihat data mutasi  

- **Data Master**:  
  - Hanya dapat melihat data  

- **Manajemen Pengguna**:  
  - Menambahkan pengguna baru  
  - Mengedit data pengguna  
  - Menghapus pengguna  

---

### 👁️ User
User biasa hanya memiliki akses terbatas untuk melihat informasi, tanpa kemampuan untuk menambah, mengubah, atau menghapus data.

- **Semua Halaman**:  
  - Hanya dapat membaca (read-only)  
  - Tidak memiliki akses ke halaman manajemen pengguna


## Kondisi Barang

- **Tersedia**
  - Default Ketika barang di inputkan, dan Ketika barang sedang tidak digunakan
- **Digunakan**
  - Ketika barang sedang digunakan oleh user
- **Diperbaiki**
  - Ketika barang rusak dan default user nya adalah "Team IT - (User sebelumnya)"
- **Dipindah**
  - Ketika barang ingin dipindahkan ke perusahaan lain, System akan otomatis merubah NO assetnya sesuai perusahaan
- **Non Aktif**
  - Ketika barang rusak dan tidak bisa digunakan lagi, System tidak bisa di inputkan user baru ketika statusnya Non Aktif

  # 📥 Standarisasi Input Data Aset

## ➕ Tambah Aset Baru

**Merek dan Spesifikasi:**

- **Laptop**: `Merek, Tipe, Processor / RAM / Storage Total
- **Handphone**: Merek, Tipe
- **Komputer**: Processor / RAM / Storage
- **Printer**: Merek, Tipe
- **Proyektor**: Merek, Tipe
- **Others**: Merek, Tipe

**Tanggal Pengadaan**:
- Jika tanggal tidak diketahui → default = `1`

**Serial Number:**
- **Laptop**: SN
- **Handphone**: IMEI SIM 1
- **Komputer**: System Model / Manufacture Custom
- **Printer**: SN
- **Proyektor**: SN
- **Others**: SN

## 📦 Serah Terima Aset

### ✅ Status Digunakan

- **Nama Pengguna**:
  - Pribadi: Nama Lengkap
  - Divisi: Nama PIC
  - Umum: IT
- **Keterangan**: Barang Normal / Kondisi Saat Penyerahan

### 🔧 Status Diperbaiki

- **Nama Pengguna**:
  - Pribadi: Team IT - Pemilik sebelumnya
   - Divisi: Nama PIC
  - Umum: IT
- **Keterangan**: Bagian yang rusak / Diservice

### 🔄 Status Dipindah

- **Nama Pengguna**:
  - Pribadi: Nama Lengkap
  - Divisi: Nama PIC
  - Umum: IT
- **Keterangan**: Barang Normal / Kondisi Saat Penyerahan

### 🛑 Status Non Aktif

- **Nama Pengguna**: Team IT  
- **Keterangan**: Sebutkan kerusakan

### ✅ Status Tersedia

- **Keterangan**: `Kondisi`
## 📄 Tambah Surat Serah Terima

Formulir ini digunakan untuk membuat dokumen serah terima aset antara dua pihak (Penerima dan Pemberi). Berikut adalah detail field yang perlu diisi:

---

### 🔍 Data Aset

- **Cari Aset berdasarkan Serial Number atau No. Asset**  
  Masukkan Serial Number atau Nomor Aset yang tersedia dalam sistem untuk mencari data aset yang akan diserahterimakan.

---

### 👤 Data Penerima (Pihak Pertama)

- **Nama Penerima**  
  Masukkan nama lengkap pihak penerima aset.

- **NIK Penerima**  
  Masukkan Nomor Induk Kepegawaian dari penerima.

- **Jabatan Penerima**  
  Masukkan jabatan penerima dalam perusahaan.

---

### 👤 Data Pemberi (Pihak Kedua)

- **Nama Pemberi**  
  Masukkan nama lengkap pihak yang menyerahkan aset.

- **NIK Pemberi**  
  Masukkan Nomor Induk Kepegawaian dari pemberi.

- **Jabatan Pemberi**  
  Masukkan jabatan pemberi dalam perusahaan.

---

### 📝 Informasi Tambahan

- **Nomor Surat**  
  AKAN DI GENERATE OTOMATIS OLEH SISTEM

- **Penanggung Jawab**  
  Nama pihak yang bertanggung jawab atas proses serah terima ini (PIC).

- **Keterangan**  
  Informasi catatan khusus terkait kondisi barang.

---

Form ini akan menghasilkan dokumen PDF serah terima yang dapat diunduh dan digunakan sebagai bukti fisik/legal serah terima barang.


## 👤 Tambah Pengguna Baru

- **Username**: Nama Lengkap
- **Email**: Email Pribadi
- **Password**: Minimal 6 karakter, mengandung angka & huruf
- **Role** (Opsi hanya tersedia pada Super Admin)

## 🏢 Tambah Perusahaan

- **Nama Perusahaan**:
  - Harus mengandung `PT / CV`
  - **Huruf besar semua**
  - Nama lengkap perusahaan
- **Singkatan**:
  - 3 huruf unik

## 🏷️ Tambah Jenis Barang

- **Nama Jenis Barang**  
  - Nama lengkap dari jenis barang.  
  - Contoh: `Air Conditioner`

- **Singkatan**  
  - Kode unik yang terdiri dari 3 huruf untuk merepresentasikan jenis barang tersebut.  
  - Contoh: `ACR` untuk Air Conditioner

- **Ikon (Icon)**  
  - Pilih ikon yang relevan dan sesuai dengan jenis barang.  
  - Jika tidak tersedia ikon spesifik, silakan gunakan ikon alternatif yang tetap menggambarkan fungsi atau karakter barang tersebut.  
  - Contoh: Jika tidak ada ikon khusus untuk *Air Conditioner*, Anda bisa menggunakan ikon seperti `SNOW` sebagai representasi pendingin.