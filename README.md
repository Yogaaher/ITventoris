# 📥 Standarisasi Input Data Aset

## ➕ Tambah Aset Baru

**Merek dan Spesifikasi:**

-   **Laptop**: `Merek, Tipe, Processor / RAM / Storage Total
-   **Handphone**: Merek, Tipe
-   **Komputer**: Processor / RAM / Storage
-   **Printer**: Merek, Tipe
-   **Proyektor**: Merek, Tipe
-   **Others**: Merek, Tipe
-   **Monitor**: Monitor masuk kedalam others

**Tanggal Pengadaan**:

-   Jika tanggal tidak diketahui → default = `1`

**Serial Number:**

-   **Laptop**: SN
-   **Handphone**: IMEI SIM 1
-   **Komputer**: System Model / Manufacture Custom
-   **Printer**: SN
-   **Proyektor**: SN
-   **Others**: SN

## 📦 Serah Terima Aset

### ✅ Status Digunakan

-   **Nama Pengguna**:
    -   Pribadi: Nama Lengkap
    -   Divisi: Nama PIC
    -   Umum: IT
-   **Keterangan**: Barang Normal / Kondisi Saat Penyerahan

### 🔧 Status Diperbaiki

-   **Nama Pengguna**:
    -   Pribadi: Team IT - Pemilik sebelumnya
    -   Divisi: Nama PIC
    -   Umum: IT
-   **Keterangan**: Bagian yang rusak / Diservice

### 🔄 Status Dipindah

-   **Nama Pengguna**:
    -   Pribadi: Nama Lengkap
    -   Divisi: Nama PIC
    -   Umum: IT
-   **Keterangan**: Barang Normal / Kondisi Saat Penyerahan

### 🛑 Status Non Aktif

-   **Nama Pengguna**: Team IT
-   **Keterangan**: Sebutkan kerusakan

### ✅ Status Tersedia

-   **Keterangan**: `Kondisi`

## 👤 Tambah Pengguna Baru

-   **Username**: Nama Lengkap
-   **Email**: Email Pribadi
-   **Password**: Minimal 6 karakter, mengandung angka & huruf

## 🏢 Tambah Perusahaan

-   **Nama Perusahaan**:
    -   Harus mengandung `PT / CV`
    -   **Huruf besar semua**
    -   Nama lengkap perusahaan
-   **Singkatan**:
    -   3 huruf unik
