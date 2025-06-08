<!DOCTYPE html>
<html lang="id"> <!-- Class 'light' akan ditambahkan oleh JS dashboard jika mode light aktif -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITventory</title> <!-- Judul bisa diubah -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/Scuto-logo.svg') }}" type="image/x-icon">
    <style>

      /* === CSS MODAL TAMBAH ASET (DARI DASHBOARD ANDA) === */
.modal { /* Ini untuk modal TAMBAH ASET */
    display: none;
    position: fixed;
    z-index: 1050; /* Lebih rendah dari modal detail jika perlu tumpang tindih */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
}

.modal.show {
    display: flex;
}

.modal-content { /* Ini untuk modal TAMBAH ASET */
    background-color: var(--app-content-secondary-color);
    color: var(--app-content-main-color);
    margin: auto;
    padding: 25px;
    border: 1px solid var(--table-border);
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    box-shadow: var(--filter-shadow);
    position: relative;
}

html.light .modal-content {
    background-color: #fff;
    border-color: #ddd;
}

.modal-header { /* Ini untuk modal TAMBAH ASET */
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--table-border);
    margin-bottom: 20px;
}

html.light .modal-header {
    border-bottom-color: #eee;
}

.modal-title { /* Ini untuk modal TAMBAH ASET */
    font-size: 1.5rem;
    font-weight: 500;
    margin: 0;
}

/* Tombol close untuk modal TAMBAH ASET */
#addAssetModal .close-button { /* Lebih spesifik untuk modal tambah aset */
    color: var(--app-content-main-color);
    font-size: 1.8rem;
    font-weight: bold;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0 5px;
}
html.light #addAssetModal .close-button {
    color: #555;
}
#addAssetModal .close-button:hover,
#addAssetModal .close-button:focus {
    text-decoration: none;
}

.modal-body form label { /* Ini untuk modal TAMBAH ASET */
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}

.modal-body form input[type="text"],
.modal-body form input[type="date"],
.modal-body form select { /* Ini untuk modal TAMBAH ASET */
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid var(--table-border);
    border-radius: 4px;
    background-color: var(--app-bg);
    color: var(--app-content-main-color);
    box-sizing: border-box;
}
html.light .modal-body form input[type="text"],
html.light .modal-body form input[type="date"],
html.light .modal-body form select {
    background-color: #fff;
    border-color: #ccc;
}

.modal-body form input:focus,
.modal-body form select:focus { /* Ini untuk modal TAMBAH ASET */
    border-color: var(--action-color);
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(40, 105, 255, 0.25); /* Ganti RGB jika --action-color berbeda */
}

.modal-footer { /* Ini untuk modal TAMBAH ASET */
    padding-top: 15px;
    border-top: 1px solid var(--table-border);
    margin-top: 20px;
    text-align: right;
}
html.light .modal-footer {
    border-top-color: #eee;
}

.modal-footer .btn { /* Ini untuk modal TAMBAH ASET */
    padding: 10px 20px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    margin-left: 10px;
}
.modal-footer .btn-primary {
    background-color: var(--action-color);
    color: white;
}
.modal-footer .btn-primary:hover {
    background-color: var(--action-color-hover);
}
.modal-footer .btn-secondary {
    background-color: var(--filter-reset);
    color: white;
}
html.light .modal-footer .btn-secondary {
    background-color: #6c757d;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: .25rem;
}
input.is-invalid, select.is-invalid {
    border-color: #dc3545 !important;
}
/* === AKHIR CSS MODAL TAMBAH ASET === */

/* === CSS UNTUK SUMMARY BOX INVENTARIS === */
.inventory-summary-container {
    display: flex;
    flex-wrap: wrap; /* Agar box turun ke baris baru jika tidak muat */
    gap: 16px; /* Jarak antar box */
    margin-bottom: 20px; /* Jarak ke elemen di bawahnya */
    padding: 0 4px; /* Samakan dengan padding products-area-wrapper */
}

.summary-box {
    background-color: var(--app-content-secondary-color); /* Warna background dari tema */
    color: var(--app-content-main-color); /* Warna teks dari tema */
    padding: 20px 15px;
    border-radius: 8px; /* Samakan dengan elemen lain */
    text-align: center;
    flex: 1 1 150px; /* flex-grow, flex-shrink, flex-basis. Biarkan tumbuh, bisa menyusut, basis minimal 150px */
    min-width: 120px; /* Lebar minimal box */
    box-shadow: var(--filter-shadow); /* Bayangan seperti filter */
    transition: transform 0.2s ease-in-out;
}

.summary-box:hover {
    transform: translateY(-10px); /* Efek hover sedikit naik */
}

html.light .summary-box {
    background-color: #f9f9f9; /* Warna background light mode, bisa disesuaikan */
    border: 1px solid #eee;
}

.summary-box-icon {
    font-size: 2.8rem; /* Ukuran ikon */
    margin-bottom: 10px;
    color: var(--action-color); /* Warna ikon, bisa disesuaikan */
}

html.light .summary-box-icon {
    color: var(--action-color); /* Pastikan warna ikon juga bagus di light mode */
}


.summary-box-type {
    font-size: 1.4rem; /* Ukuran teks tipe barang */
    font-weight: 500;
    margin-bottom: 8px;
    text-transform: capitalize; /* Opsional: agar huruf depan besar */
}

.summary-box-count {
    font-size: 2.4rem; /* Ukuran angka count */
    font-weight: bold;
}
/* === AKHIR CSS UNTUK SUMMARY BOX INVENTARIS === */

/* === CSS DASHBOARD (BAGIAN UTAMA) === */
:root {
  /* Variabel dari Dashboard */
  --app-bg: #101827;
  --sidebar: rgba(21, 30, 47,1);
  --sidebar-main-color: #fff;
  --table-border: #1a2131;
  --table-header: #1a2131;
  --app-content-main-color: #fff; /* Teks utama di konten dashboard */
  --sidebar-link: #fff;
  --sidebar-active-link: #1d283c;
  --sidebar-hover-link: #1a2539;
  --action-color: #2869ff;
  --action-color-hover: #6291fd;
  --app-content-secondary-color: #1d283c; /* Background sekunder/konten di dashboard */
  --filter-reset: #2c394f;
  --filter-shadow: rgba(16, 24, 39, 0.8) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
  --sidebar-width-expanded: 200px;
  --sidebar-width-collapsed: 65px;

  /* Variabel dari Modal Detail AWAL (digabungkan) */
  --color-neutral-light: #b0b5b8;       /* Untuk teks/border netral di modal detail */
  --color-neutral-medium: #545e61;     /* Untuk teks/border netral lebih gelap di modal detail */
  --color-modal-background: #394144;  /* Background utama modal detail (dark mode) */
  --color-text-light: #fff;           /* Teks utama di modal detail (dark mode), bisa sama dengan --app-content-main-color */
  --color-accent: #36d3b4;            /* Warna aksen (hijau toska) di modal detail */
  --color-button-secondary: #6c757d; /* Tombol sekunder di modal detail */
}

html.light:root {
  /* Variabel Dashboard Light Mode */
  --app-bg: #fff;
  --sidebar: #f3f6fd;
  --app-content-secondary-color: #f3f6fd;
  --app-content-main-color: #1f1c2e; /* Teks utama di konten dashboard (light mode) */
  --sidebar-link: #1f1c2e;
  --sidebar-hover-link: rgba(195, 207, 244, 0.5);
  --sidebar-active-link: rgba(195, 207, 244, 1);
  --sidebar-main-color: #1f1c2e;
  --filter-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;

  /* Sesuaikan variabel modal detail untuk light mode */
  --color-modal-background: #ffffff;      /* Background modal detail (light mode) */
  --color-text-light: #212529;        /* Teks utama di modal detail (light mode) */
  --color-neutral-light: #6c757d;      /* Teks/border netral di modal detail (light mode) */
  --color-neutral-medium: #495057;     /* Teks/border netral lebih gelap di modal detail (light mode) */
  /* --color-accent tetap sama atau bisa diubah jika perlu kontras berbeda */
  /* --color-button-secondary tetap sama atau bisa diubah */
}

/* CSS Global Dashboard (pertahankan ini) */
html {
    box-sizing: border-box;
    font-size: 62.5%; /* 1rem = 10px */
    scroll-behavior: smooth;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

*, *::before, *::after {
    box-sizing: inherit;
    margin: 0;
    padding: 0;
}

body {
  margin: 0;
  padding: 0;
  overflow-y: auto;
  overflow-x: hidden;
  font-family: 'Poppins', sans-serif; /* Font utama dashboard */
  font-size: 1.6rem; /* Default font size 16px */
  background-color: var(--app-bg);
  color: var(--app-content-main-color); /* Warna teks default untuk body dari dashboard */
  letter-spacing: 1px;
  transition: background 0.2s ease;
}

#dashboard-page {
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
}
.app-container {
  border-radius: 4px;
  width: 100%;
  height: 100%;
  display: flex;
  overflow: hidden;
  box-shadow: var(--filter-shadow); /* Menggunakan variabel shadow dari dashboard */
  max-width: 2000px;
  margin: 0 auto;
}

.sidebar {
  flex-basis: var(--sidebar-width-expanded);
  max-width: var(--sidebar-width-expanded);
  min-width: var(--sidebar-width-expanded); /* Diubah agar konsisten */
  flex-shrink: 0;
  background-color: var(--sidebar);
  display: flex;
  flex-direction: column;
  transition: min-width 0.3s ease-in-out,
              max-width 0.3s ease-in-out,
              flex-basis 0.3s ease-in-out,
              padding 0.3s ease-in-out,
              opacity 0.3s ease-in-out;
  position: relative;
}
.sidebar.collapsed {
    min-width: var(--sidebar-width-collapsed);
    max-width: var(--sidebar-width-collapsed);
    flex-basis: var(--sidebar-width-collapsed);
}
.sidebar.collapsed .sidebar-header .app-icon {
    opacity: 0;
    transform: scale(0);
    width: auto;
    overflow: hidden;
    margin-right: 0; 
}
.sidebar.collapsed .sidebar-header .app-icon svg {
    opacity: 0;
    transform: scale(0);
    width: auto;
    overflow: hidden;
    margin-right: 0; 
}
.sidebar.collapsed .sidebar-list-item a span {
    opacity: 0;
    width: 0;
    overflow: hidden;
    white-space: nowrap;
    margin-left: -10px;
}
.sidebar.collapsed .sidebar-list-item a {
    justify-content: center;
    padding-left: 0;
    padding-right: 0;
}
.sidebar.collapsed .sidebar-list-item a svg {
    margin-right: 0;
}
.sidebar.collapsed .account-info-name,
.sidebar.collapsed .account-info-more {
    display: none;
}
.sidebar.collapsed .account-info {
    justify-content: center;
}
.sidebar.collapsed .sidebar-header {
    justify-content: center;
}
.sidebar.collapsed .sidebar-header .app-icon {
    display: none;
}
.sidebar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px;
  min-height: 50px;
  margin-bottom: 20px;
  margin-top: 5px;
}
.app-icon {
  color: var(--sidebar-main-color);
  transition: opacity 0.3s ease, transform 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: auto;
  overflow: hidden;
  transition: opacity 0.2s ease-in-out 0.1s,
              transform 0.2s ease-in-out 0.1s,
              width 0.2s ease-in-out 0.1s;
}
.app-icon .app-logo-svg {
  width: 30px;
  height: 30px;
  color: var(--sidebar-app-icon-color);
  transition: color 0.3s;
  flex-shrink: 0;
}
.app-icon svg {
  width: 24px;
  height: 24px;
}
.app-name-text {
  font-size: 1.5rem;
  font-weight: 800;
  white-space: nowrap;
  overflow: hidden;
  opacity: 1;
  transition: opacity 0.2s ease-in-out 0.1s, width 0.2s ease-in-out 0.1s;
}
.sidebar-list {
  list-style-type: none;
  padding: 0;
}
.sidebar-list-item {
  position: relative;
  margin-bottom: 4px;
}
.sidebar-list-item a {
  display: flex;
  align-items: center;
  width: 100%;
  padding: 10px 16px;
  color: var(--sidebar-link);
  text-decoration: none;
  font-size: 14px;
  line-height: 24px;
  overflow: hidden;
  white-space: nowrap;
}
.sidebar-list-item a span {
    transition: opacity 0.2s ease-in-out 0.1s, width 0.2s ease-in-out 0.1s;
    white-space: nowrap;
    overflow: hidden;
}
.sidebar-list-item svg {
  margin-right: 8px;
  flex-shrink: 0;
  transition: margin-right 0.3s ease-in-out;
}
.sidebar-list-item:hover {
  background-color: var(--sidebar-hover-link);
}
.sidebar-list-item.active {
  background-color: var(--sidebar-active-link);
}
.sidebar-list-item.active:before {
  content: '';
  position: absolute;
  right: 0;
  background-color: var(--action-color);
  height: 100%;
  width: 4px;
}
.mode-switch {
  background-color: transparent;
  border: none;
  padding: 0;
  color: var(--app-content-main-color);
  display: flex;
  justify-content: center;
  align-items: center;
  margin-left: auto;
  margin-right: 8px;
  cursor: pointer;
}
.mode-switch .moon {
  fill: var(--app-content-main-color);
}
.mode-switch.active .moon {
  fill: none;
}
.account-info {
  display: flex;
  align-items: center;
  padding: 16px;
  margin-top: auto;
}
.account-info-picture {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  overflow: hidden;
  flex-shrink: 0;
}
.account-info-picture img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.account-info-name {
  font-size: 14px;
  color: var(--sidebar-main-color);
  margin: 0 8px;
  overflow: hidden;
  max-width: 100%;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.account-info-more {
  color: var(--sidebar-main-color);
  padding: 0;
  border: none;
  background-color: transparent;
  margin-left: auto;
}
.app-content {
  padding: 16px;
  /* height: 100%; */ /* Bisa menyebabkan masalah jika flex-grow digunakan */
  flex: 1;
  max-height: 100%;
  display: flex;
  flex-direction: column;
  overflow-x: hidden; /* Konten utama bisa scroll vertikal jika perlu */
}
.app-content-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 4px;
}
.app-content-headerText {
  color: var(--app-content-main-color);
  font-size: 30px;
  line-height: 32px;
  margin: 0;
  padding-bottom: 20px;
  padding-top: 10px;
}
.app-content-headerButton {
  background-color: var(--action-color);
  color: #fff;
  font-size: 14px;
  line-height: 24px;
  border: none;
  border-radius: 4px;
  height: 32px;
  padding: 0 16px;
  transition: .2s;
  cursor: pointer;
  flex-shrink: 0;
}
.app-content-header-actions-right {
    display: flex;
    align-items: center; /* Pastikan tombol sejajar vertikal */
    /* margin-left: auto; Tidak perlu jika .app-content-header sudah space-between */
}
.app-content-header-actions-right .mode-switch.action-button {
    margin-right: 8px;
}
.app-content-header-actions-right .mode-switch.action-button svg {
    width: 18px;
    height: 18px;
}
.app-content-headerButton:hover {
  background-color: var(--action-color-hover);
}
.app-content-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 4px;
  gap: 8px; 
}
.app-content-actions-wrapper {
  display: flex;
  align-items: center;
  margin-left: auto;
}
.app-content-actions .search-bar {
  flex-grow: 1;
  max-width: none;
  margin-right: 8px;
}
.app-content-actions-buttons {
  display: flex;
  align-items: center;
  flex-shrink: 0;
  gap: 8px;
}
.app-content-actions .action-button.add-asset-btn {
  background-color: var(--action-color) !important;
  color: white;
  border: none;
}
.action-button.add-asset-btn svg,
.action-button.add-asset-btn i.fas {
    margin-right: 6px !important;
}

@media screen and (max-width: 768px) {
  .app-content-actions {
    flex-direction: column;
    align-items: stretch;
    gap: 10px; /* Jarak vertikal antar elemen */
  }
  .app-content-actions .search-bar {
    order: 1; /* Search bar di atas */
  }
  .app-content-actions-buttons {
    order: 2; /* Tombol-tombol di bawah */
    justify-content: flex-start; /* Mulai dari kiri */
  }
  .app-content-actions .search-bar-container {
    flex-grow: 1; /* Biarkan container yang mengisi ruang jika perlu */
    max-width: 320px; /* Atur max-width pada container */
    margin-right: 8px; /* Atur margin pada container */
    }
}
@media screen and (max-width: 520px) {
  .app-content-actions { flex-direction: column; }
  .app-content-actions .search-bar { max-width: 100%; order: 2; }
  .app-content-actions .app-content-actions-wrapper { padding-bottom: 16px; order: 1; }
}
.search-bar {
  background-color: var(--app-content-secondary-color);
  border: 1px solid var(--app-content-secondary-color);
  color: var(--app-content-main-color);
  font-size: 14px;
  line-height: 24px;
  border-radius: 4px;
  padding: 0px 10px 0px 32px;
  height: 32px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-search'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E");
  background-size: 16px;
  background-repeat: no-repeat;
  background-position: left 10px center;
  width: 100%;
  max-width: 320px;
  transition: .2s;
}
.search-bar-container {
    position: relative;
    display: flex;
    align-items: center;
    flex-grow: 1;
}
.search-bar-container .search-bar {
    padding-right: 35px;
    flex-grow: 1;
    width: 100%;
}
.clear-search-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background-color: transparent;
    border: none;
    color: var(--app-content-main-color);
    cursor: pointer;
    font-size: 2rem;
    padding: 2px 6px;
    line-height: 1;
}
.clear-search-btn:hover {
    color: red;
}
html.light .clear-search-btn {
    color: #555; /* Warna 'X' untuk light mode */
}
html.light .search-bar {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%231f1c2e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-search'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E");
}
.search-bar::placeholder { color: var(--app-content-main-color); }
.search-bar:hover {
  border-color: var(--action-color-hover);
}
.search-bar:focus {
  outline: none;
  border-color: var(--action-color);
}
.action-button { /* Untuk tombol filter, dll. di dashboard */
  border-radius: 4px;
  height: 32px;
  background-color: var(--app-content-secondary-color);
  border: 1px solid var(--app-content-secondary-color);
  display: flex;
  align-items: center;
  color: var(--app-content-main-color);
  font-size: 14px;
  margin-left: 8px;
  padding: 0 8px;
  cursor: pointer;
}
.action-button span { margin-right: 4px; }
.action-button:hover {
  border-color: var(--action-color-hover);
}
.action-button:focus, .action-button.active {
  outline: none;
  color: var(--action-color);
  border-color: var(--action-color);
}
.filter-button-wrapper {
  position: relative;
}
.filter-menu {
  background-color: var(--app-content-secondary-color);
  position: absolute;
  top: calc(100% + 16px);
  right: 0;
  border-radius: 4px;
  padding: 8px;
  width: 220px;
  z-index: 1000; /* Pastikan ini di bawah z-index modal jika filter terbuka di belakang modal */
  box-shadow: var(--filter-shadow);
  visibility: hidden;
  opacity: 0;
  transition: .2s;
}
.filter-menu:before {
  content: '';
  position: absolute;
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid var(--app-content-secondary-color);
  bottom: 100%;
  right: 10px;
}
.filter-menu.active {
  visibility: visible;
  opacity: 1;
  top: calc(100% + 8px);
}
.filter-menu label {
  display: block;
  font-size: 14px;
  color: var(--app-content-main-color);
  margin-bottom: 8px;
}
.filter-menu select {
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-down'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  padding: 8px 24px 8px 8px;
  background-position: right 4px center;
  border: 1px solid var(--app-content-main-color);
  border-radius: 4px;
  color: var(--app-content-main-color);
  font-size: 12px;
  background-color: transparent;
  margin-bottom: 16px;
  width: 100%;
}
.filter-menu select option { font-size: 14px; }
html.light .filter-menu select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%231f1c2e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-down'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
}
.filter-menu select:hover {
  border-color: var(--action-color-hover);
}
.filter-menu select:focus, .filter-menu select.active {
  outline: none;
  color: var(--action-color);
  border-color: var(--action-color);
}
.filter-menu-buttons {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.filter-button {
  border-radius: 2px;
  font-size: 12px;
  padding: 4px 8px;
  cursor: pointer;
  border: none;
  color: #fff;
}
.filter-button.apply {
  background-color: var(--action-color);
}
.filter-button.reset {
  background-color: var(--filter-reset);
}
.filter-menu .filter-button {
  border-radius: 2px;
  font-size: 12px;
  padding: 4px 8px;
  cursor: pointer;
  border: none;
  color: #fff;
  width: 100%; 
  box-sizing: border-box;
}
.filter-menu .filter-button.reset-filter-in-menu:not(:disabled){
    background-color: var(--action-color);
    color: #ffff;
}
html.light .filter-menu .filter-button.reset-filter-in-menu:not(:disabled) {
    background-color: #adb5bd;
    color: #fff;
}
.filter-menu .filter-button.reset-filter-in-menu:disabled {
    background-color: var(--filter-reset); /* Atau warna yang lebih pudar */
    opacity: 0.5; /* Buat lebih transparan agar terlihat disabled */
    color: rgba(255, 255, 255, 0.6); /* Teks putih yang lebih pudar */
    cursor: not-allowed;
}
html.light .filter-menu .filter-button.reset-filter-in-menu:disabled {
    background-color: #adb5bd; /* Abu-abu lebih terang untuk background disabled di light mode */
    color: #6c757d; /* Teks abu-abu lebih gelap */
    opacity: 0.7; /* Atau sesuaikan opacity */
}
.products-area-wrapper {
  width: 100%;
  max-height: 100%; /* Memastikan tabel tidak melebihi kontainer */
  overflow: auto; /* Memungkinkan scroll pada tabel jika kontennya besar */
  padding: 0 4px;
}
.products-area-wrapper.tableView { /* Pastikan class ini ada pada wrapper tabel */
  display: flex;
  flex-direction: column; /* Header dan row akan bertumpuk vertikal */
}
.tableView .products-header {
    background-color: var(--app-content-secondary-color);
    display: flex; /* Baris header */
    align-items: center;
    border-radius: 4px; /* Menggunakan variabel untuk header tabel */
    position: sticky;
    top: 0;
    z-index: 10; /* Agar header tetap di atas saat scroll */
    border-bottom: 2px solid var(--action-color);
}
html.light .tableView .products-header {
    background-color: #f1f3f5; /* Warna background header yang soft untuk light mode */
    border-bottom-color: #dee2e6; /* Garis bawah abu-abu di light mode */
}
.tableView #productTableRowsContainer .products-row:nth-child(even) {
    /* Targetkan semua baris genap (ke-2, 4, 6, dst.) */
    background-color: var(--table-border); /* Warna sedikit beda untuk dark mode */
}
html.light .tableView #productTableRowsContainer .products-row:nth-child(even) {
    background-color: #f9f9f9; /* Warna abu-abu sangat terang untuk light mode */
}
.tableView #productTableRowsContainer .products-row:hover {
    background-color: var(--sidebar-active-link); /* Warna hover yang bagus di dark mode */
    transition: background-color 0.2s ease-in-out; /* Transisi halus */
}
html.light .tableView #productTableRowsContainer .products-row:hover {
    background-color: #e9ecef; /* Warna hover yang bagus di light mode */
}
.products-header .sortable-header {
    cursor: pointer;
    user-select: none; /* Mencegah teks ter-highlight saat di-double click */
    transition: color 0.2s ease-in-out;
}
.products-header .sortable-header:hover {
    color: var(--action-color); /* Warna hover saat kursor di atasnya */
}

/* Style untuk indikator panah sorting */
.products-header .sortable-header::after {
    content: '';
    display: inline-block;
    width: 16px;
    height: 16px;
    margin-left: 8px;
    opacity: 0.4;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    /* Panah default (netral), bisa dikosongkan jika tidak mau */
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M8 9l4-4 4 4M16 15l-4 4-4-4'/%3E%3C/svg%3E");
}

html.light .products-header .sortable-header::after {
     /* Ganti warna stroke untuk light mode */
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%231f1c2e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M8 9l4-4 4 4M16 15l-4 4-4-4'/%3E%3C/svg%3E");
}


/* Saat sorting ASC aktif */
.products-header .sortable-header.sorted-asc {
    color: var(--action-color); /* Warna aktif */
}
.products-header .sortable-header.sorted-asc::after {
    opacity: 1;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%232869ff' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M12 19V5M5 12l7-7 7 7'/%3E%3C/svg%3E"); /* Panah ke atas (A-Z) */
}

/* Saat sorting DESC aktif */
.products-header .sortable-header.sorted-desc {
    color: var(--action-color); /* Warna aktif */
}
.products-header .sortable-header.sorted-desc::after {
    opacity: 1;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%232869ff' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M12 5v14M19 12l-7 7-7-7'/%3E%3C/svg%3E"); /* Panah ke bawah (Z-A) */
}
.tableView .products-row {
  display: flex; /* Setiap baris data */
  align-items: center;
  border-radius: 4px;
}

.tableView .products-row .cell-more-button {
  display: none; /* Jika ada, biasanya untuk tampilan mobile */
}
.tableView .product-cell {
  padding: 8px 12px;
  color: var(--app-content-main-color);
  font-size: 14px;
  display: flex;
  align-items: center;
  white-space: nowrap; /* Mencegah teks wrap */
  overflow: hidden; /* Sembunyikan teks yang berlebih */
  text-overflow: ellipsis; /* Tampilkan "..." jika teks berlebih */
}
.tableView .product-cell.cell-no { flex: 0 0 80px; min-width: 80px; justify-content: center;}
.tableView .product-cell.cell-perusahaan { flex: 1 1 100px; min-width: 100px; }
.tableView .product-cell.cell-jenis-barang { flex: 1 1 120px; min-width: 120px; }
.tableView .product-cell.cell-no-asset { flex: 1 1 180px; min-width: 180px; }
.tableView .product-cell.cell-merek { flex: 1 1 200px; min-width: 200px; }
.tableView .product-cell.cell-tgl-pengadaan { flex: 1 1 140px; min-width: 140px; }
.tableView .product-cell.cell-serial-number { flex: 1 1 150px; min-width: 150px; }
.tableView .product-cell.cell-aksi {
    flex: 0 0 180px; /* Cukupkan lebar untuk dua tombol */
    min-width: 180px;
    justify-content: center;
    overflow: visible; /* Agar tombol tidak terpotong */
}
.tableView .product-cell img {
  width: 32px;
  height: 32px;
  border-radius: 6px;
  margin-right: 6px;
}
.tableView .sort-button {
  padding: 0;
  background-color: transparent;
  border: none;
  cursor: pointer;
  color: var(--app-content-main-color);
  margin-left: 4px;
  display: flex;
  align-items: center;
}
.tableView .sort-button:hover { color: var(--action-color); }
.tableView .sort-button svg { width: 12px; }
.tableView .cell-label {
  display: none; /* Biasanya untuk tampilan mobile */
}
.status {
  border-radius: 4px;
  display: flex;
  align-items: center;
  padding: 4px 8px;
  font-size: 12px;
}
.status:before {
  content: '';
  width: 4px;
  height: 4px;
  border-radius: 50%;
  margin-right: 4px;
}
.status.active {
  color: #2ba972;
  background-color: rgba(43, 169, 114, 0.2);
}
.status.active:before {
  background-color: #2ba972;
}
.status.disabled {
  color: #59719d;
  background-color: rgba(89, 113, 157, 0.2);
}
.status.disabled:before {
  background-color: #59719d;
}
/* Tombol aksi di dalam tabel */
.product-cell.cell-aksi .action-btn-table {
    padding: 6px 12px;
    margin: 0 4px;
    border: 1px solid var(--action-color);
    background-color: transparent;
    color: var(--action-color);
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
    white-space: nowrap; /* Agar teks tombol tidak wrap */
}
.product-cell.cell-aksi .action-btn-table:hover {
    background-color: var(--action-color);
    color: #fff;
}
.product-cell.cell-aksi .action-btn-table.update-btn {
    /* Style khusus jika ada */
}
.product-cell.cell-aksi .action-btn-table.detail-btn {
    border-color: #5cb85c; /* Warna hijau untuk tombol detail di tabel */
    color: #5cb85c;
}
.product-cell.cell-aksi .action-btn-table.detail-btn:hover {
    background-color: #5cb85c;
    color: #fff;
}
/* === AKHIR CSS DASHBOARD === */

/* === CSS SPESIFIK UNTUK MODAL DETAIL === */
#deviceInfoModal.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    z-index: 1051;
    justify-content: center;
    align-items: center;
    padding: 2rem;
}

#deviceInfoModal .modal-content-wrapper.device-entry-info-modal {
    background-color: var(--color-modal-background);
    border-radius: 1rem;
    width: 65rem;
    max-width: calc(100vw - 4rem);
    max-height: calc(100vh - 4rem);
    overflow: hidden; /* Wrapper tidak scroll, body-nya yang scroll */
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    color: var(--color-text-light);
    position: relative; 
    z-index: 1052;
}

#deviceInfoModal .morph-modal-container {
    display: flex;
    flex-flow: column nowrap;
    align-items: stretch;
    padding: 2.5rem;
    flex-grow: 1;
    overflow: hidden; /* Container tidak scroll */
}

#deviceInfoModal .morph-modal-title {
    flex: 0 0 auto;
    padding-bottom: 1.5rem;
    border-bottom: 0.1rem solid var(--color-neutral-medium);
    font-size: 2.2rem;
    font-weight: 500;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}
#deviceInfoModal .morph-modal-title .fa {
    margin-right: 1rem;
}

/* Tombol close di modal DETAIL */
#deviceInfoModal .btn-close {
    background: none;
    border: none;
    color: var(--color-neutral-light);
    font-size: 2.8rem;
    cursor: pointer;
    padding: 0.5rem;
    line-height: 1;
}
#deviceInfoModal .btn-close:hover {
    color: var(--color-text-light);
}

#deviceInfoModal .morph-modal-body {
    flex: 1 1 auto;
    overflow-y: auto; /* Ini yang akan scroll */
    font-size: 1.5rem;
}

#deviceInfoModal .device-details-info-header {
    display: flex;
    flex-flow: row nowrap;
    align-items: center;
    padding-bottom: 1.5rem;
    margin-bottom: 2.5rem;
    border-bottom: 0.1rem solid var(--color-neutral-medium);
}

#deviceInfoModal .device-image {
    margin-right: 2rem;
    color: var(--color-accent);
    font-size: 5rem;
}

#deviceInfoModal .device-label {
    display: flex;
    flex-direction: column;
}

#deviceInfoModal .device-name,
#deviceInfoModal .device-online-status {
    display: block;
}

#deviceInfoModal .device-name {
    font-size: 2rem;
    font-weight: 500;
}

#deviceInfoModal .device-online-status {
    font-style: italic;
    font-size: 1.4rem;
    color: var(--color-neutral-light);
}

#deviceInfoModal .asset-detail-content .info-section {
    margin-bottom: 2rem;
}
#deviceInfoModal .asset-detail-content .info-section:last-child {
    margin-bottom: 0;
}

#deviceInfoModal .asset-detail-content .info-item {
    display: flex;
    margin-bottom: 1.2rem;
    line-height: 1.6;
}

#deviceInfoModal .asset-detail-content .info-item dt {
    font-weight: 600;
    min-width: 160px;
    padding-right: 1rem;
    flex-shrink: 0;
    color: inherit; /* Mengambil warna dari parent (.modal-content-wrapper) */
}

#deviceInfoModal .asset-detail-content .info-item dd {
    color: var(--color-neutral-light);
    word-break: break-word;
    flex-grow: 1;
}

#deviceInfoModal .asset-detail-content hr.separator {
    border: none;
    border-top: 1px solid var(--color-neutral-medium);
    margin: 2.5rem 0;
}

#deviceInfoModal .asset-detail-content .action-buttons {
    display: flex;
    gap: 1.5rem;
    margin-top: 1.5rem;
    margin-bottom: 2.5rem;
}

#deviceInfoModal .asset-detail-content .btn-action {
    padding: 1rem 2rem;
    font-size: 1.5rem;
    border-radius: 0.5rem;
    border: none;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
}

#deviceInfoModal .asset-detail-content .btn-action.btn-detail {
    background-color: var(--color-accent);
    color: var(--color-modal-background); /* Warna teks kontras dengan background tombol */
}
#deviceInfoModal .asset-detail-content .btn-action.btn-detail:hover {
    background-color: #2cbda2;
}

#deviceInfoModal .asset-detail-content .btn-action.btn-update {
    background-color: var(--color-button-secondary);
    color: var(--color-text-light); /* Warna teks kontras dengan background tombol */
}
#deviceInfoModal .asset-detail-content .btn-action.btn-update:hover {
    background-color: #5a6268;
}

#deviceInfoModal .asset-detail-content .keterangan-text {
    white-space: pre-line;
    line-height: 1.7;
}
/* === AKHIR CSS MODAL DETAIL === */


/* === CSS TAMBAHAN UNTUK MODAL HISTORY USER === */
#userHistoryModal.modal-overlay {
    display: none; /* Awalnya display: none, dikontrol oleh JS */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7); /* Latar belakang overlay standar */
    z-index: 1053; /* LEBIH TINGGI DARI deviceInfoModal (1051) dan addAssetModal (1050) */
    justify-content: center;
    align-items: center;
    padding: 1.5rem; /* Samakan dengan deviceInfoModal jika diinginkan */
    overflow: auto; /* Tambahkan jika kontennya bisa lebih tinggi dari viewport */
}

/* Konten wrapper modal history, sama seperti deviceInfoModal */
#userHistoryModal .modal-content-wrapper.device-entry-info-modal {
    background-color: var(--color-modal-background);
    border-radius: 0.8rem;
    width: 90%;
    max-width: 60rem;
    max-height: calc(100vh - 6rem);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    color: var(--color-text-light);
    position: relative;
    z-index: 1054;
}

/* Kontainer morph modal di dalam history, sama seperti deviceInfoModal */
#userHistoryModal .morph-modal-container {
    display: flex;
    flex-flow: column nowrap;
    align-items: stretch;
    padding: 2rem;
    flex-grow: 1;
    overflow: hidden;
}

/* Judul modal history, sama seperti deviceInfoModal */
#userHistoryModal .morph-modal-title {
    flex: 0 0 auto;
    padding-bottom: 1rem;
    border-bottom: 0.1rem solid var(--color-neutral-medium);
    font-size: 1.8rem; /* Ukuran bisa disesuaikan */
    font-weight: 500;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}
#userHistoryModal .morph-modal-title .fa {
    margin-right: 0.8rem;
    font-size: 1.7rem;
}

/* Tombol close modal history, sama seperti deviceInfoModal */
#userHistoryModal .btn-close {
    background: none;
    border: none;
    color: var(--color-neutral-light);
    font-size: 2.5rem;
    cursor: pointer;
    padding: 0.4rem;
    line-height: 1;
}
#userHistoryModal .btn-close:hover {
    color: var(--color-text-light);
}

/* Body modal history, sama seperti deviceInfoModal */
#userHistoryModal .morph-modal-body {
    flex: 1 1 auto;
    overflow-y: auto;
    font-size: 1.4rem; /* Ukuran bisa disesuaikan */
}

/* Style tabel di dalam modal history (yang sudah Anda buat sebelumnya) */
#userHistoryModal .morph-modal-body p:first-child { /* Target paragraf SN */
    margin-bottom: 1rem; /* Sesuaikan margin bawahnya */
    font-size: 1.3rem; /* Perkecil font-size untuk info SN */
}

#userHistoryModal .morph-modal-body p:first-child strong {
    font-weight: 600; /* Pastikan SN tetap bold */
}

#userHistoryModal .user-history-table-wrapper {
    max-height: 350px; /* Sesuaikan tinggi maksimal sesuai kebutuhan modal */
    overflow-y: auto;
    overflow-x: auto; /* Aktifkan jika tabel bisa lebih lebar dari wrapper */
    border: 1px solid var(--color-neutral-medium);
    border-radius: 0.5rem;
}

#userHistoryModal .user-history-table {
    width: 100%;
    border-collapse: collapse; /* Penting untuk border antar sel */
    /* Font size akan diwarisi dari .morph-modal-body (1.4rem) atau bisa diatur spesifik */
}

#userHistoryModal .user-history-table th,
#userHistoryModal .user-history-table td {
    padding: 8px 12px; /* Padding sel yang lebih nyaman */
    border-bottom: 1px solid var(--table-border);
    text-align: left; /* Default untuk th dan td */
    vertical-align: top; /* Selaras atas, berguna untuk konten multi-baris */
    line-height: 1.5; /* Keterbacaan yang lebih baik */
}

#userHistoryModal .user-history-table th {
    font-weight: 600;
    background-color: var(--color-modal-background); /* Untuk sticky header */
    /* white-space: nowrap; /* Opsional: agar teks header tidak wrap */
}

/* Styling kolom spesifik (gunakan width atau min-width) */
#userHistoryModal .user-history-table .cell-history-user {
    min-width: 170px;
}
#userHistoryModal .user-history-table .cell-history-tgl-awal,
#userHistoryModal .user-history-table .cell-history-tgl-akhir {
    min-width: 170px;
    white-space: nowrap; /* Tanggal biasanya tidak di-wrap */
}
#userHistoryModal .user-history-table .cell-history-status {
    min-width: 100px;
    white-space: nowrap;
}
#userHistoryModal .user-history-table .cell-history-keterangan {
    min-width: 200px;
    white-space: normal; /* Izinkan teks keterangan untuk wrap */
    word-break: break-word; /* Pecah kata jika terlalu panjang */
}

/* Pastikan header tabel (thead > th) tetap terlihat saat scroll */
#userHistoryModal .user-history-table thead th { /* Lebih spesifik untuk sel header */
    position: sticky;
    top: 0;
    z-index: 1; /* Di atas konten tbody saat scroll */
    /* background-color sudah diatur di atas untuk th secara umum */
}

html.light #userHistoryModal .user-history-table thead th {
    background-color: var(--color-modal-background); /* Pastikan variabel ini benar untuk light mode */
}

html.light #userHistoryModal #userHistoryTableHeader {
    background-color: var(--color-modal-background); /* Pastikan ini juga diatur untuk light mode */
}

#userHistoryModal .history-info-list {
    margin-bottom: 1.5rem; /* Jarak sebelum tabel */
    font-size: 1.3rem; /* Sesuaikan ukuran font jika perlu */
}

#userHistoryModal .history-info-item {
    display: flex; /* Membuat dt dan dd sejajar */
    margin-bottom: 0.6rem; /* Jarak antar item info */
    line-height: 1.5;
}

#userHistoryModal .history-info-item dt {
    min-width: 120px; /* Lebar minimum untuk label, sesuaikan */
    flex-shrink: 0; /* Mencegah label menyusut */
    padding-right: 5px; /* Jarak antara label dan titik dua (opsional) */
    color: var(--color-neutral-light); /* Warna label */
    font-weight: 500; /* Sedikit bold untuk label */
    position: relative; /* Untuk posisi titik dua */
}

/* Menambahkan titik dua setelah label dt secara otomatis */
#userHistoryModal .history-info-item dt::after {
    content: ":";
    position: absolute;
    right: 0;
}


#userHistoryModal .history-info-item dd {
    flex-grow: 1; /* Membuat nilai mengambil sisa ruang */
    margin-left: 0; /* Reset margin default dd */
    word-break: break-word; /* Agar nilai yang panjang bisa wrap */
    padding-left: 8px;
}

#userHistoryModal .history-info-item dd strong {
    color: var(--color-text-light); /* Warna teks utama modal untuk nilai */
    font-weight: 600;
}
/* === AKHIR CSS MODAL HISTORY USER === */


/* === CSS UNTUK TOMBOL BURGER === */
.burger-button {
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    width: 30px;
    height: 28px;
    z-index: 10; /* Pastikan di atas elemen lain jika perlu */
    flex-shrink: 0;
}

.burger-button span {
    display: block;
    width: 100%;
    height: 3px;
    background-color: var(--app-content-main-color); /* Warna garis burger */
    border-radius: 3px;
    transition: all 0.3s ease-in-out;
}

/* Animasi X untuk burger button */
.burger-button.active span:nth-child(1) {
    transform: translateY(8px) rotate(45deg);
}
.burger-button.active span:nth-child(2) {
    opacity: 0;
}
.burger-button.active span:nth-child(3) {
    transform: translateY(-8px) rotate(-45deg);
}
/* === AKHIR CSS UNTUK TOMBOL BURGER === */

/* === CSS TAMBAHAN UNTUK MODAL SERAH TERIMA ASET === */
#serahTerimaAsetModal.modal-overlay { /* Targetkan ID dan class bersama-sama */
    display: none; /* Awalnya display: none, dikontrol oleh JS */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7); /* Sama seperti userHistoryModal */
    z-index: 1055; /* Pastikan cukup tinggi, sama atau > userHistoryModal */
    justify-content: center;
    align-items: center;
    padding: 1.5rem;
    overflow: auto;
}

/* Konten wrapper modal serah terima, sama seperti userHistoryModal */
#serahTerimaAsetModal .modal-content-wrapper.device-entry-info-modal {
    background-color: var(--color-modal-background);
    border-radius: 0.8rem;
    width: 90%;
    max-width: 60rem;
    max-height: calc(100vh - 6rem); /* (1.5rem padding atas + 1.5rem padding bawah) * 2 = 6rem */
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    color: var(--color-text-light);
    position: relative;
    z-index: 1056; 
}

/* Kontainer morph modal di dalam serah terima, sama seperti userHistoryModal */
#serahTerimaAsetModal .morph-modal-container {
    display: flex;
    flex-flow: column nowrap;
    align-items: stretch;
    padding: 2rem;
    flex-grow: 1;
    overflow: hidden; /* Kontainer utama tidak scroll, body-nya yang scroll */
}

/* Judul modal serah terima, sama seperti userHistoryModal */
#serahTerimaAsetModal .morph-modal-title {
    flex: 0 0 auto;
    padding-bottom: 1rem;
    border-bottom: 0.1rem solid var(--color-neutral-medium);
    font-size: 1.8rem;
    font-weight: 500;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}
#serahTerimaAsetModal .morph-modal-title .fa {
    margin-right: 0.8rem;
    font-size: 1.7rem;
}

/* Tombol close modal serah terima, sama seperti userHistoryModal */
#serahTerimaAsetModal .btn-close {
    background: none;
    border: none;
    color: var(--color-neutral-light);
    font-size: 2.5rem;
    cursor: pointer;
    padding: 0.4rem;
    line-height: 1;
}
#serahTerimaAsetModal .btn-close:hover {
    color: var(--color-text-light);
}

/* Body modal serah terima, sama seperti userHistoryModal */
#serahTerimaAsetModal .morph-modal-body {
    flex: 1 1 auto; /* Memungkinkan body untuk tumbuh dan menyusut */
    overflow-y: auto; /* Hanya body modal yang scroll jika kontennya panjang */
    font-size: 1.4rem;
    padding-right: 1rem; /* Tambahkan sedikit padding jika scrollbar muncul agar tidak terlalu mepet */
}

/* Styling untuk form elements di dalam #serahTerimaAsetModal */
#serahTerimaAsetModal .morph-modal-body .form-group {
    margin-bottom: 1.2rem;
}
#serahTerimaAsetModal .morph-modal-body label {
    display: block;
    margin-bottom: 0.6rem;
    font-weight: 500;
    color: var(--color-text-light);
}
/* Menggunakan gaya dari modal tambah aset untuk input fields */
#serahTerimaAsetModal .morph-modal-body input[type="date"],
#serahTerimaAsetModal .morph-modal-body input[type="text"],
#serahTerimaAsetModal .morph-modal-body select,
#serahTerimaAsetModal .morph-modal-body textarea {
    width: 100%;
    padding: 10px;
    /* margin-bottom: 15px; Dihapus karena .form-group sudah punya margin-bottom */
    border: 1px solid var(--table-border);
    border-radius: 4px;
    background-color: var(--app-bg); /* Dark mode background untuk input */
    color: var(--app-content-main-color); /* Dark mode text color untuk input */
    box-sizing: border-box;
}

html.light #serahTerimaAsetModal .morph-modal-body input[type="date"],
html.light #serahTerimaAsetModal .morph-modal-body input[type="text"],
html.light #serahTerimaAsetModal .morph-modal-body select,
html.light #serahTerimaAsetModal .morph-modal-body textarea {
    background-color: #fff; /* Light mode background */
    border-color: #ccc;   /* Light mode border */
    color: #212529;       /* Light mode text color */
}

/* Style untuk input tanggal yang readonly */
#serahTerimaAsetModal .morph-modal-body input[type="date"]:read-only {
    background-color: var(--color-neutral-medium); /* Lebih gelap untuk menunjukkan non-interaktif */
    color: var(--color-neutral-light);
    cursor: not-allowed;
}
html.light #serahTerimaAsetModal .morph-modal-body input[type="date"]:read-only {
    background-color: #e9ecef; /* Standar Bootstrap readonly background */
    color: #495057;
}
#serahTerimaAsetModal .morph-modal-body input:focus,
#serahTerimaAsetModal .morph-modal-body select:focus,
#serahTerimaAsetModal .morph-modal-body textarea:focus {
    border-color: var(--action-color);
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(40, 105, 255, 0.25);
}

#serahTerimaAsetModal .morph-modal-body .invalid-feedback {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: .25rem;
    display: block; /* Agar tetap mengambil ruang meski kosong */
}

/* Info section styling (untuk Aset: dan Serial Number:) */
#serahTerimaAsetModal .info-section {
    margin-bottom: 1.5rem;
}
#serahTerimaAsetModal .info-item {
    display: flex;
    margin-bottom: 0.8rem;
    line-height: 1.5;
}
#serahTerimaAsetModal .info-item dt {
    font-weight: 500; /* Sedikit lebih ringan dari 600 agar tidak terlalu dominan */
    min-width: 130px; /* Lebar untuk label */
    padding-right: 0.5rem; /* Jarak dari titik dua */
    flex-shrink: 0;
    color: var(--color-neutral-light);
    position: relative;
}
#serahTerimaAsetModal .info-item dt::after { /* Tambahkan titik dua otomatis */
    content: ":";
    position: absolute;
    right: 0;
}
#serahTerimaAsetModal .info-item dd {
    color: var(--color-text-light);
    word-break: break-word;
    flex-grow: 1;
    padding-left: 8px; /* Jarak setelah titik dua */
}

/* Modal footer styling */
#serahTerimaAsetModal .modal-footer {
    padding-top: 15px;
    border-top: 1px solid var(--color-neutral-medium);
    margin-top: 20px;
    text-align: right;
}
html.light #serahTerimaAsetModal .modal-footer {
    border-top-color: #eee;
}
#serahTerimaAsetModal .modal-footer .btn {
    padding: 10px 20px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    margin-left: 10px;
}
#serahTerimaAsetModal .modal-footer .btn-primary {
    background-color: var(--action-color);
    color: white;
}
#serahTerimaAsetModal .modal-footer .btn-primary:hover {
    background-color: var(--action-color-hover);
}
#serahTerimaAsetModal .modal-footer .btn-secondary {
    background-color: var(--filter-reset); /* Konsisten dengan Add Asset Modal */
    color: white;
}
html.light #serahTerimaAsetModal .modal-footer .btn-secondary {
    background-color: #6c757d;
}
/* === AKHIR CSS MODAL SERAH TERIMA ASET === */

    </style>
</head>
<body>

    {{-- =================================== --}}
    {{--        MODAL DETAIL ASET (DARI FILE MODAL ANDA, DENGAN ID UNTUK DATA DINAMIS) --}}
    {{-- =================================== --}}
    <div id="deviceInfoModal" class="modal-overlay">
        <div class="modal-content-wrapper device-entry-info-modal">
            <div class="morph-modal-container">
                <div class="morph-modal-title">
                    <span><i class="fas fa-info-circle"></i> Detail Perangkat</span>
                    {{-- Tombol close untuk modal detail, berikan ID unik --}}
                    <button id="closeDetailModalButton" class="btn-close" aria-label="Tutup modal">×</button>
                </div>
                <div class="morph-modal-body">
                    <div class="device-details-info-header">
                        <i id="modalDeviceImage" class="device-image fas fa-desktop"></i>
                        <div class="device-label">
                            <span class="device-name" id="modalDeviceName">Nama Perangkat</span>
                            <span class="device-online-status" id="modalDeviceType">Jenis Barang</span>
                        </div>
                    </div>
                    <div class="asset-detail-content">
                        <div class="info-section">
                            <dl class="info-item"><dt>No:</dt><dd id="modalNo">_</dd></dl>
                            <dl class="info-item"><dt>Perusahaan:</dt><dd id="modalPerusahaan">_</dd></dl>
                            <dl class="info-item"><dt>No Asset:</dt><dd id="modalNoAsset">_</dd></dl>
                            <dl class="info-item"><dt>Tgl. Pengadaan:</dt><dd id="modalTglPengadaan">_</dd></dl>
                            <dl class="info-item"><dt>Serial Number:</dt><dd id="modalSerialNumber">_</dd></dl>
                        </div>
                        <hr class="separator">
                        <div class="info-section">
                            <dl class="info-item"><dt>Pengguna</dt><dd id="modalUser">_</dd></dl>
                            <dl class="info-item"><dt>Penyerahan:</dt><dd id="modalTglPenyerahan">_</dd></dl>
                            <dl class="info-item"><dt>Pengembalian:</dt><dd id="modalTglPengembalian">_</dd></dl>
                            <div class="action-buttons">
                                <button class="btn-action btn-detail" id="triggerUserHistoryModalButton">
                                    <i class="fas fa-user-circle"></i> Detail User
                                </button>
                                <button class="btn-action btn-update" onclick="alert('Update Aset dari modal detail belum diimplementasikan')">
                                    <i class="fas fa-edit"></i> Update Aset
                                </button>
                            </div>
                        </div>
                        <div class="info-section">
                             <dl class="info-item"><dt>Status:</dt><dd id="modalStatus">_</dd></dl>
                             <dl class="info-item"><dt>Keterangan:</dt><dd id="modalKeterangan" class="keterangan-text">_</dd></dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- AKHIR MODAL DETAIL ASET --}}

    {{-- ================================================================ --}}
    {{-- MODAL BARU: HISTORY PENGGUNA BERDASARKAN SERIAL NUMBER           --}}
    {{-- ================================================================ --}}
    <div id="userHistoryModal" class="modal-overlay" style="display: none;"> {{-- Awalnya display: none --}}
        <div class="modal-content-wrapper device-entry-info-modal"> {{-- Menggunakan class yang sama untuk styling --}}
            <div class="morph-modal-container">
                <div class="morph-modal-title">
                    <span><i class="fas fa-history"></i> Riwayat Pengguna Aset</span>
                    {{-- Tombol close untuk modal history, berikan ID unik --}}
                    <button id="closeUserHistoryModalButton" class="btn-close" aria-label="Tutup modal">×</button>
                </div>
                <div class="morph-modal-body">

                    <dl class="history-info-list">
                        <div class="history-info-item">
                            <dt>Perangkat</dt>
                            <dd><strong id="historyModalDeviceName">_</strong></dd>
                        </div>
                        <div class="history-info-item">
                            <dt>Serial Number</dt>
                            <dd><strong id="historyModalSerialNumber">_</strong></dd>
                        </div>
                        <div class="history-info-item">
                            <dt>Perusahaan</dt>
                            <dd><strong id="historyModalCompany">_</strong></dd>
                        </div>
                    </dl>
                    
                    {{-- Kontainer PEMBUNGKUS untuk tabel history (untuk scrolling) --}}
                    <div class="user-history-table-wrapper">
                        <table class="user-history-table" id="userHistoryTableContainer"> <!-- ID ini bisa berguna untuk styling tabelnya sendiri -->
                            <thead id="userHistoryTableHeader">
                                {{-- Header akan diisi oleh JavaScript --}}
                            </thead>
                            <tbody id="userHistoryTableBody">
                                {{-- Baris Data History akan diisi oleh JavaScript --}}
                                <tr>
                                    <td colspan="5" style="padding: 15px; text-align: center; color: var(--color-neutral-light);">Memuat riwayat...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- AKHIR MODAL HISTORY PENGGUNA --}}

    {{-- ================================================================ --}}
    {{-- MODAL BARU: SERAH TERIMA ASET / UPDATE PENGGUNA                --}}
    {{-- ================================================================ --}}
    <div id="serahTerimaAsetModal" class="modal-overlay" style="display: none;">
        <div class="modal-content-wrapper device-entry-info-modal">
            <div class="morph-modal-container">
                <div class="morph-modal-title">
                    <span><i class="fas fa-exchange-alt"></i> Serah Terima Aset</span>
                    <button id="closeSerahTerimaAsetModalButton" class="btn-close" aria-label="Tutup modal">×</button>
                </div>
                <div class="morph-modal-body">
                    <form id="serahTerimaAsetForm">
                        @csrf
                        <input type="hidden" id="serahTerimaAssetId" name="asset_id">
                        <input type="hidden" id="serahTerimaSerialNumber" name="serial_number">

                        <div class="info-section" style="margin-bottom: 1.5rem;">
                            <dl class="info-item"><dt>Aset:</dt><dd id="serahTerimaInfoNamaAset">_</dd></dl>
                            <dl class="info-item"><dt>Serial Number:</dt><dd id="serahTerimaInfoSN">_</dd></dl>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="serahTerimaTanggalAwal" style="display: block; margin-bottom: .5rem; font-weight: 500;">Tanggal Serah Terima (Otomatis)</label>
                            <input type="date" id="serahTerimaTanggalAwal" name="tanggal_awal" class="form-control" readonly>
                        </div>

                        <!-- Form fields untuk pindah perusahaan (tambahkan perusahaan baru disini) -->
                         <div class="form-group" id="perusahaanTujuanGroup" style="display: none; margin-bottom: 1rem;">
                            <label for="serahTerimaPerusahaanTujuan" style="display: block; margin-bottom: .5rem; font-weight: 500;">Pindahkan ke Perusahaan</label>
                            <select id="serahTerimaPerusahaanTujuan" name="perusahaan_tujuan" class="form-control" style="background-color: var(--app-bg); border-color: var(--table-border); color: var(--app-content-main-color); width: 100%; padding: 8px; border-radius: 4px;">
                                <option value="">Pilih Perusahaan Tujuan</option>
                                <option value="SCO">SCO</option>
                                <option value="SCT">SCT</option>
                                <option value="SCP">SCP</option>
                                <option value="Migen">Migen</option>
                            </select>
                            <div class="invalid-feedback" id="perusahaan_tujuan_serah_error"></div>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="serahTerimaUser" style="display: block; margin-bottom: .5rem; font-weight: 500;">Nama Pengguna Baru</label>
                            <input type="text" id="serahTerimaUser" name="username" class="form-control" required style="background-color: var(--app-bg); border-color: var(--table-border); color: var(--app-content-main-color); width: 100%; padding: 8px; border-radius: 4px;">
                            <div class="invalid-feedback" id="username_serah_error"></div>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="serahTerimaStatus" style="display: block; margin-bottom: .5rem; font-weight: 500;">Status Aset</label>
                            <select id="serahTerimaStatus" name="status" class="form-control" required style="background-color: var(--app-bg); border-color: var(--table-border); color: var(--app-content-main-color); width: 100%; padding: 8px; border-radius: 4px;">
                                <option value="">Pilih Status</option>
                                <option value="digunakan">Digunakan</option>
                                <option value="diperbaiki">Diperbaiki</option>
                                <option value="dipindah">Dipindah</option>
                                <option value="non aktif">Non Aktif</option>
                                <option value="tersedia">Tersedia</option>
                            </select>
                            <div class="invalid-feedback" id="status_serah_error"></div>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="serahTerimaKeterangan" style="display: block; margin-bottom: .5rem; font-weight: 500;">Keterangan</label>
                            <textarea id="serahTerimaKeterangan" name="keterangan" class="form-control" rows="3" required style="background-color: var(--app-bg); border-color: var(--table-border); color: var(--app-content-main-color); width: 100%; padding: 8px; border-radius: 4px;"></textarea>
                            <div class="invalid-feedback" id="keterangan_serah_error"></div>
                        </div>

                        <div class="modal-footer" style="padding-top: 1rem; border-top: 1px solid var(--color-neutral-medium); text-align: right;">
                             {{-- Tombol modal-footer dari modal tambah aset, disesuaikan class & ID nya --}}
                            <button type="button" class="btn btn-secondary" id="cancelSerahTerimaAsetModalBtn" style="background-color: var(--filter-reset); color: white; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer; font-weight: 500; margin-left: 10px;">Batal</button>
                            <button type="submit" class="btn btn-primary" id="submitSerahTerimaAsetBtn" style="background-color: var(--action-color); color: white; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer; font-weight: 500; margin-left: 10px;">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- AKHIR MODAL SERAH TERIMA ASET --}}

    <!-- =================================== -->
    <!--        MODAL TAMBAH ASET           -->
    <!-- =================================== -->
    <div id="addAssetModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Aset Baru</h5>
                <button type="button" class="close-button" id="closeAddAssetModalBtn">×</button>
            </div>
            <div class="modal-body">
                <form id="addAssetForm">
                    @csrf <!-- Penting untuk keamanan form Laravel -->

                    <div class="form-group">
                        <label for="perusahaan">Perusahaan</label>
                        <select name="perusahaan" id="perusahaan" class="form-control" required>
                            <option value="">Pilih Perusahaan</option>
                            <option value="SCO">SCO</option>
                            <option value="SCT">SCT</option>
                            <option value="SCP">SCP</option>
                            <option value="Migen">Migen</option>
                        </select>
                        <div class="invalid-feedback" id="perusahaan_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="jenis_barang">Jenis Barang</label>
                        <select name="jenis_barang" id="jenis_barang" class="form-control" required>
                            <option value="">Pilih Jenis Barang</option>
                            <option value="Laptop">Laptop</option>
                            <option value="HP">HP</option>
                            <option value="PC/AIO">PC/AIO</option>
                            <option value="Printer">Printer</option>
                            <option value="Proyektor">Proyektor</option>
                            <option value="Others">Others</option>
                        </select>
                        <div class="invalid-feedback" id="jenis_barang_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="no_asset">No. Asset</label>
                        <input type="text" name="no_asset" id="no_asset" class="form-control" required>
                        <div class="invalid-feedback" id="no_asset_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="merek">Merek</label> <!-- Mengganti 'No Barang' dengan 'Merek' -->
                        <input type="text" name="merek" id="merek" class="form-control" required>
                        <div class="invalid-feedback" id="merek_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="tgl_pengadaan">Tanggal Pengadaan</label>
                        <input type="date" name="tgl_pengadaan" id="tgl_pengadaan" class="form-control" required>
                        <div class="invalid-feedback" id="tgl_pengadaan_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="serial_number">Serial Number</label>
                        <input type="text" name="serial_number" id="serial_number" class="form-control" required>
                        <div class="invalid-feedback" id="serial_number_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelAddAssetModalBtn">Batal</button>
                <button type="submit" class="btn btn-primary" form="addAssetForm" id="submitAddAssetBtn">Simpan Aset</button>
            </div>
        </div>
    </div>

    <!-- =================================== -->
    <!--       HALAMAN DASHBOARD SECTION       -->
    <!-- =================================== -->
    <div id="dashboard-page"> <!-- Biarkan ini sebagai pembungkus utama halaman dashboard -->
        <div class="app-container">
          <div class="sidebar"> <!-- Akan diberi class .collapsed oleh JS -->
            <div class="sidebar-header">
              <div class="app-icon">
                <img src="/img/Scuto-logo.svg" alt="Scuto Logo" class="app-logo-svg">
                <span class="app-name-text">ITventory</span>
              </div>
              <button id="burger-menu" class="burger-button" title="Toggle Sidebar">
                <span></span>
                <span></span>
                <span></span>
              </button>
            </div>
            <ul class="sidebar-list">
              <li class="sidebar-list-item">
                <a href="{{ route('dashboard.index') }}">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                  <span>Dashboard</span>
                </a>
              </li>
              <li class="sidebar-list-item">
                  <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>User Manage</span>
                  </a>
              </li>
            </ul>
            <div class="account-info">
              <div class="account-info-picture">
                <img src="{{ asset('img/Logo-scuto.png') }}" alt="Account">
              </div>
              <div class="account-info-name">Monica G.</div>
              <button class="account-info-more">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
              </button>
            </div>
          </div>

        {{-- KONTEN UTAMA APLIKASI (AREA FILTER DAN TABEL) --}}
        <div class="app-content">
        <div class="app-content-header">
            <h1 class="app-content-headerText">Data Aset Inventaris</h1>
            {{-- Tombol Mode dan Logout dipindahkan ke sini dan dibungkus --}}
            <div class="app-content-header-actions-right"> {{-- Wrapper baru untuk tombol kanan atas --}}
            <button class="mode-switch action-button" title="Switch Theme"> {{-- Tambahkan class action-button --}}
                <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="20" height="20" viewBox="0 0 24 24"> {{-- Ukuran ikon bisa disesuaikan --}}
                <defs></defs>
                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                </svg>
            </button>
            <button id="logoutButton" class="app-content-headerButton" style="background-color: #e74c3c; margin-left: 8px;">Logout</button>
            </div>
        </div>

            {{-- +++ AWAL BAGIAN BARU UNTUK SUMMARY BOX +++ --}}
        <div class="inventory-summary-container">
            @php
                $typeIcons = [
                    'Laptop' => 'fas fa-laptop',
                    'HP' => 'fas fa-mobile-alt',
                    'PC/AIO' => 'fas fa-desktop',
                    'Printer' => 'fas fa-print',
                    'Proyektor' => 'fas fa-video',
                    'Others' => 'fas fa-boxes'
                ];
            @endphp
            @foreach($inventorySummary as $type => $count)
                <div class="summary-box">
                    <div class="summary-box-icon">
                        <i class="{{ $typeIcons[$type] ?? 'fas fa-question-circle' }}"></i>
                    </div>
                    <div class="summary-box-type">{{ $type }}</div>
                    <div class="summary-box-count" id="summary-count-{{ strtolower(str_replace('/', '-', $type)) }}">{{ $count }}</div>
                </div>
            @endforeach
        </div>
        {{-- +++ AKHIR BAGIAN BARU UNTUK SUMMARY BOX +++ --}}

        {{-- FORM FILTER DAN TOMBOL AKSI --}}
        <form action="{{ route('dashboard.index') }}" method="GET" id="filterForm">
            <div class="app-content-actions">
                
            <div class="search-bar-container">
                    <input class="search-bar"
                           placeholder="Cari No Asset, Serial, atau Merek..."
                           type="text"
                           name="search_no_asset"
                           value="{{ $searchKeyword ?? old('search_no_asset') }}"
                           id="mainSearchInput"
                           autocomplete="off">
                    <button type="button" class="clear-search-btn" id="clearMainSearchBtn" style="display: none;" title="Hapus pencarian">
                        × {{-- Karakter 'X' (kali) --}}
                    </button>
                </div>
                
                {{-- Tombol aksi (Tambah & Filter) dipindahkan ke sini --}}
                <div class="app-content-actions-buttons">
                    <button type="button" id="openAddAssetModalButton" class="action-button add-asset-btn" title="Tambah Aset">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Asset</span>
                    </button>
                    <div class="filter-button-wrapper">
                        <button type="button" class="action-button filter jsFilter" title="Filter">
                            <span>Filter</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        </button>
                         <div class="filter-menu">
                                <label for="filter_perusahaan">Perusahaan</label>
                                <select name="filter_perusahaan" id="filter_perusahaan">
                                    <option value="">Semua Perusahaan</option>
                                    @if(isset($perusahaanOptions))
                                        @foreach($perusahaanOptions as $perusahaan)
                                            <option value="{{ $perusahaan }}" {{ (isset($filterPerusahaan) && $filterPerusahaan == $perusahaan) ? 'selected' : '' }}>
                                                {{ $perusahaan }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>

                                <label for="filter_jenis_barang">Jenis Barang</label>
                                <select name="filter_jenis_barang" id="filter_jenis_barang">
                                    <option value="">Semua Jenis Barang</option>
                                     @if(isset($jenisBarangOptions))
                                        @foreach($jenisBarangOptions as $jenis)
                                            <option value="{{ $jenis }}" {{ (isset($filterJenisBarang) && $filterJenisBarang == $jenis) ? 'selected' : '' }}>
                                                {{ $jenis }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="filter-menu-buttons" style="margin-top: 15px; text-align: center;">
                                    <button type="button" class="filter-button reset-filter-in-menu" id="resetFilterInMenuBtn" disabled>
                                        Reset Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
        {{-- FORM FILTER SELESAI DI SINI --}}

            {{-- Area tabel produk --}}
            <div class="products-area-wrapper tableView" id="productTableArea">
                {{-- Header Tabel (tetap ada atau bisa juga digenerate JS jika mau) --}}
                <div class="products-header">
                    <div class="product-cell cell-no sortable-header" data-sort-by="no">No</div>
                    <div class="product-cell cell-perusahaan sortable-header" data-sort-by="perusahaan">Perusahaan</div>
                    <div class="product-cell cell-jenis-barang sortable-header" data-sort-by="jenis_barang">Jenis Barang</div>
                    <div class="product-cell cell-no-asset sortable-header" data-sort-by="no_asset">No Asset</div>
                    <div class="product-cell cell-merek sortable-header" data-sort-by="merek">Merek</div>
                    <div class="product-cell cell-tgl-pengadaan sortable-header" data-sort-by="tgl_pengadaan" data-sort-type="date">Tgl. Pengadaan</div>
                    <div class="product-cell cell-serial-number sortable-header" data-sort-by="serial_number">Serial Number</div>
                    <div class="product-cell cell-aksi">Aksi</div>
                </div>

                {{-- Container untuk baris-baris produk yang akan diisi oleh JS --}}
                <div id="productTableRowsContainer">
                    {{-- Load Awal dari Controller index() --}}
                    @if(isset($barangs) && $barangs->count() > 0)
                        @foreach($barangs as $index => $barang)
                        <div class="products-row">
                            <div class="product-cell cell-no">{{ $barangs->firstItem() + $index }}</div>
                            <div class="product-cell cell-perusahaan" title="{{ $barang->perusahaan }}">{{ $barang->perusahaan }}</div>
                            <div class="product-cell cell-jenis-barang" title="{{ $barang->jenis_barang }}">{{ $barang->jenis_barang }}</div>
                            <div class="product-cell cell-no-asset" title="{{ $barang->no_asset }}">{{ $barang->no_asset }}</div>
                            <div class="product-cell cell-merek" title="{{ $barang->merek }}">{{ $barang->merek }}</div>
                            <div class="product-cell cell-tgl-pengadaan">{{ \Carbon\Carbon::parse($barang->tgl_pengadaan)->format('d-m-Y') }}</div>
                            <div class="product-cell cell-serial-number" title="{{ $barang->serial_number }}">{{ $barang->serial_number }}</div>
                            <div class="product-cell cell-aksi">
                                <button class="action-btn-table detail-btn-table-js" onclick="openDetailModal({{ $barang->id }})" title="Detail Aset">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Detail</span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="products-row">
                            <div class="product-cell" style="text-align:center; flex-basis:100%; padding: 20px;">Tidak ada data aset ditemukan.</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Container untuk Paginasi --}}
            <div class="pagination-container" style="margin-top: 20px; display: flex; justify-content: center;" id="realtimePaginationContainer">
                @if (isset($barangs) && $barangs->hasPages())
                    {{ $barangs->links('pagination::bootstrap-4') }}
                @endif
            </div>

          </div>
          {{-- AKHIR KONTEN UTAMA APLIKASI --}}

        </div>
      </div>

    {{-- ... (Bagian HTML Anda di atas) ... --}}

    <script>
        // ==============================================================================
        // VARIABEL GLOBAL & FUNGSI HELPER YANG BISA DIAKSES DARI MANA SAJA
        // ==============================================================================
        let currentAssetIdForSerahTerima = null;
        let debounceTimer;
        let lastKnownUserFromDetail = '-';
        const DEBOUNCE_DELAY = 500;

        function formatDate(dateString) {
            if (!dateString) return '-';
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) {
                    // Jika formatnya sudah YYYY-MM-DD dari input date, coba parse langsung
                    const parts = dateString.split('-');
                    if (parts.length === 3) {
                        const year = parseInt(parts[0], 10);
                        const month = parseInt(parts[1], 10) -1; // Month is 0-indexed
                        const day = parseInt(parts[2], 10);
                        const parsedDate = new Date(year, month, day);
                        if (!isNaN(parsedDate.getTime())) {
                             const d = String(parsedDate.getDate()).padStart(2, '0');
                             const m = String(parsedDate.getMonth() + 1).padStart(2, '0');
                             const y = parsedDate.getFullYear();
                             return `${d}-${m}-${y}`;
                        }
                    }
                    console.warn("Invalid date string for formatting:", dateString);
                    return dateString;
                }
                // Format 'dd-mm-yyyy'
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            } catch (e) {
                console.error("Error formatting date:", dateString, e);
                return dateString;
            }
        }

        function handleSerahTerimaStatusChange() {
            const serahTerimaStatusSelect = document.getElementById('serahTerimaStatus');
            const serahTerimaUserInput = document.getElementById('serahTerimaUser');
            const perusahaanTujuanGroup = document.getElementById('perusahaanTujuanGroup');
            const serahTerimaPerusahaanTujuanSelect = document.getElementById('serahTerimaPerusahaanTujuan');

            // Pastikan elemen ada sebelum dimanipulasi (penting jika fungsi dipanggil sebelum modal siap)
            if (!serahTerimaStatusSelect || !serahTerimaUserInput || !perusahaanTujuanGroup || !serahTerimaPerusahaanTujuanSelect) {
                return;
            }

            const selectedStatus = serahTerimaStatusSelect.value;

            serahTerimaUserInput.placeholder = 'Masukkan nama pengguna';
            serahTerimaUserInput.value = '';
            serahTerimaUserInput.readOnly = false;
            perusahaanTujuanGroup.style.display = 'none';
            serahTerimaPerusahaanTujuanSelect.value = '';
            serahTerimaPerusahaanTujuanSelect.removeAttribute('required');

            // Bersihkan error messages terkait
            const usernameErrorEl = document.getElementById('username_serah_error');
            const perusahaanTujuanErrorEl = document.getElementById('perusahaan_tujuan_serah_error');
            if (usernameErrorEl) usernameErrorEl.textContent = '';
            if (perusahaanTujuanErrorEl) perusahaanTujuanErrorEl.textContent = '';
            serahTerimaUserInput.classList.remove('is-invalid');
            serahTerimaPerusahaanTujuanSelect.classList.remove('is-invalid');

            const previousUser = (lastKnownUserFromDetail && lastKnownUserFromDetail !== '-' && lastKnownUserFromDetail.trim() !== '') ? lastKnownUserFromDetail : 'User Sebelumnya Tidak Diketahui';

            if (selectedStatus === 'digunakan') {
                // Input pengguna biasa, tidak ada perubahan dari reset di atas.
            } else if (selectedStatus === 'tersedia') {
                serahTerimaUserInput.value = 'Team IT';
                serahTerimaUserInput.readOnly = true;
            } else if (selectedStatus === 'diperbaiki') {
                serahTerimaUserInput.value = `Team IT - ${previousUser}`;
            } else if (selectedStatus === 'non aktif') {
                serahTerimaUserInput.value = 'Team IT';
                serahTerimaUserInput.readOnly = true;
            } else if (selectedStatus === 'dipindah') {
                perusahaanTujuanGroup.style.display = 'block';
                serahTerimaPerusahaanTujuanSelect.setAttribute('required', 'required');
            }
        }

        function openDetailModal(barangId) {
            const detailModalOverlay = document.getElementById('deviceInfoModal');
            if (!detailModalOverlay) return;

            const modalDeviceName = document.getElementById('modalDeviceName');
            const modalDeviceType = document.getElementById('modalDeviceType');
            const modalNo = document.getElementById('modalNo');
            const modalPerusahaan = document.getElementById('modalPerusahaan');
            const modalNoAsset = document.getElementById('modalNoAsset');
            const modalTglPengadaan = document.getElementById('modalTglPengadaan');
            const modalSerialNumber = document.getElementById('modalSerialNumber');
            const modalUser = document.getElementById('modalUser');
            const modalTglPenyerahan = document.getElementById('modalTglPenyerahan');
            const modalTglPengembalian = document.getElementById('modalTglPengembalian');
            const modalStatus = document.getElementById('modalStatus');
            const modalKeterangan = document.getElementById('modalKeterangan');
            const deviceImage = document.getElementById('modalDeviceImage');
            const modalPerusahaanTeks = document.getElementById('modalPerusahaan');
            const btnUpdateAsetModalDetail = detailModalOverlay.querySelector('.btn-action.btn-update');

            if (!modalDeviceName || !btnUpdateAsetModalDetail /* ...tambahkan cek null untuk elemen lain jika perlu...*/) {
                console.error('Beberapa elemen modal detail tidak ditemukan.');
                return;
            }

            // Reset UI modal detail
            modalDeviceName.textContent = 'Memuat...';
            modalDeviceType.textContent = '_';
            modalNo.textContent = '_';
            modalPerusahaan.textContent = '_';
            modalNoAsset.textContent = '_';
            modalTglPengadaan.textContent = '_';
            modalSerialNumber.textContent = '_';
            modalUser.textContent = '_';
            modalTglPenyerahan.textContent = '_';
            modalTglPengembalian.textContent = '_';
            modalStatus.innerHTML = '_';
            modalKeterangan.textContent = '_';
            deviceImage.className = 'device-image fas fa-spinner fa-spin';

            currentAssetIdForSerahTerima = barangId;

            fetch(`/barang/detail/${barangId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.barang) {
                        const barang = data.barang;
                        const track = data.track;

                        modalDeviceName.textContent = barang.merek || 'N/A';
                        modalDeviceType.textContent = barang.jenis_barang || 'N/A';
                        modalNo.textContent = barang.id || 'N/A';
                        modalPerusahaan.textContent = barang.perusahaan || 'N/A';
                        modalNoAsset.textContent = barang.no_asset || 'N/A';
                        modalTglPengadaan.textContent = formatDate(barang.tgl_pengadaan);
                        modalSerialNumber.textContent = barang.serial_number || 'N/A';
                        modalPerusahaanTeks.textContent = barang.perusahaan || 'N/A';

                        if (track) {
                            modalUser.textContent = track.username || 'N/A';
                            lastKnownUserFromDetail = track.username || '-';
                            modalTglPenyerahan.textContent = formatDate(track.tanggal_awal);
                            modalTglPengembalian.textContent = track.tanggal_ahir ? formatDate(track.tanggal_ahir) : '-';
                            modalStatus.innerHTML = `<span style="font-weight: bold; color: var(--color-accent);">${track.status || 'N/A'}</span>`;
                            modalKeterangan.textContent = track.keterangan || 'Tidak ada keterangan.';
                        } else {
                            modalUser.textContent = '-';
                            modalTglPenyerahan.textContent = '-';
                            modalTglPengembalian.textContent = '-';
                            lastKnownUserFromDetail = '-';
                            modalStatus.innerHTML = `<span style="font-weight: bold; color: var(--color-accent);">${barang.status || 'N/A'}</span>`; // Fallback ke status barang
                            modalKeterangan.textContent = 'Belum ada riwayat serah terima.';
                        }

                        const triggerHistoryButton = document.getElementById('triggerUserHistoryModalButton');
                        if (triggerHistoryButton && barang.serial_number) {
                            const newTriggerHistoryButton = triggerHistoryButton.cloneNode(true);
                            triggerHistoryButton.parentNode.replaceChild(newTriggerHistoryButton, triggerHistoryButton);
                            const deviceFullName = `${barang.merek || ''} (${barang.jenis_barang || 'Tipe Tidak Diketahui'})`;
                            const currentCompany = barang.perusahaan || 'N/A';
                            newTriggerHistoryButton.addEventListener('click', () => openUserHistoryModal(barang.serial_number, deviceFullName, currentCompany));
                        }

                        // Setup tombol "Update Aset" di modal detail untuk memanggil handleOpenSerahTerimaModal
                        btnUpdateAsetModalDetail.dataset.assetId = barang.id;
                        btnUpdateAsetModalDetail.dataset.serialNumber = barang.serial_number;
                        // Pastikan nama perangkat tidak mengandung karakter yang bisa merusak HTML jika tidak di-escape
                        const deviceNameForModal = `${barang.merek || 'Aset'} (${barang.jenis_barang || 'Tipe Tidak Diketahui'})`;
                        btnUpdateAsetModalDetail.dataset.assetId = barang.id;
                        btnUpdateAsetModalDetail.dataset.serialNumber = barang.serial_number;
                        btnUpdateAsetModalDetail.dataset.deviceName = deviceNameForModal.replace(/"/g, '"');

                        const newBtnUpdate = btnUpdateAsetModalDetail.cloneNode(true); // Reclone untuk listener baru
                        btnUpdateAsetModalDetail.parentNode.replaceChild(newBtnUpdate, btnUpdateAsetModalDetail);
                        newBtnUpdate.removeAttribute('onclick');
                        newBtnUpdate.addEventListener('click', handleOpenSerahTerimaModal);


                        let iconClass = 'fas fa-desktop'; // default
                        if (barang.jenis_barang === 'Laptop') iconClass = 'fas fa-laptop';
                        else if (barang.jenis_barang === 'HP') iconClass = 'fas fa-mobile-alt';
                        else if (barang.jenis_barang === 'Printer') iconClass = 'fas fa-print';
                        else if (barang.jenis_barang === 'Proyektor') iconClass = 'fas fa-video';
                        else if (barang.jenis_barang === 'Others') iconClass = 'fas fa-box-open';
                        deviceImage.className = `device-image ${iconClass}`;

                        detailModalOverlay.style.display = 'flex';
                    } else {
                        alert(data.error || 'Data tidak ditemukan.');
                        // Reset UI ke keadaan error jika perlu
                        modalDeviceName.textContent = 'Error';
                        deviceImage.className = 'device-image fas fa-exclamation-triangle';
                        lastKnownUserFromDetail = '-';
                    }
                })
                .catch(error => {
                    console.error('Error fetching detail barang:', error);
                    alert('Gagal mengambil detail barang.');
                    modalDeviceName.textContent = 'Error';
                    deviceImage.className = 'device-image fas fa-exclamation-triangle';
                    lastKnownUserFromDetail = '-';
                });
        }

        function handleOpenSerahTerimaModal(event) {
            const assetId = event.currentTarget.dataset.assetId;
            const serialNumber = event.currentTarget.dataset.serialNumber;
            const deviceName = event.currentTarget.dataset.deviceName;

            const serahModal = document.getElementById('serahTerimaAsetModal');
            const serahForm = document.getElementById('serahTerimaAsetForm');

            if (!serahModal || !serahForm) {
                console.error("Modal serah terima atau formnya tidak ditemukan.");
                return;
            }

            serahForm.reset(); // Reset form, termasuk select status

            document.getElementById('serahTerimaAssetId').value = assetId;
            document.getElementById('serahTerimaSerialNumber').value = serialNumber;
            document.getElementById('serahTerimaInfoNamaAset').textContent = deviceName;
            document.getElementById('serahTerimaInfoSN').textContent = serialNumber;

            const today = new Date().toISOString().slice(0, 10);
            document.getElementById('serahTerimaTanggalAwal').value = today;

            // PENTING: Panggil handleSerahTerimaStatusChange SETELAH form direset
            // Ini akan mengatur UI berdasarkan nilai default dari select status (yang akan kosong)
            handleSerahTerimaStatusChange();

            // Bersihkan pesan error validasi sebelumnya jika ada
            serahForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            serahForm.querySelectorAll('.form-control.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            serahModal.style.display = 'flex';
        }

        function openUserHistoryModal(serialNumber, deviceName, company) {
            const historyModal = document.getElementById('userHistoryModal');
            const historyModalSerialNumberEl = document.getElementById('historyModalSerialNumber');
            const historyModalDeviceNameEl = document.getElementById('historyModalDeviceName');
            const historyModalCompanyEl = document.getElementById('historyModalCompany');
            const historyTableBodyEl = document.getElementById('userHistoryTableBody');
            const historyTableHeaderEl = document.getElementById('userHistoryTableHeader');

            if (!historyModal || !historyModalSerialNumberEl || !historyModalDeviceNameEl || !historyModalCompanyEl || !historyTableBodyEl || !historyTableHeaderEl) {
                console.error("Satu atau lebih elemen modal history tidak ditemukan. Periksa ID.");
                alert("Kesalahan: Komponen modal history tidak lengkap.");
                return; // Keluar dari fungsi jika elemen tidak ditemukan
            }
            // Tidak ada kurung kurawal pembuka baru di sini

            historyModalDeviceNameEl.textContent = deviceName || 'Tidak Diketahui';
            historyModalSerialNumberEl.textContent = serialNumber || 'N/A';
            historyModalCompanyEl.textContent = company || 'N/A'; // Pastikan ini ada
            
            historyTableBodyEl.innerHTML = '<tr><td colspan="5" style="padding:15px; text-align:center;">Memuat riwayat... <i class="fas fa-spinner fa-spin"></i></td></tr>';
            historyTableHeaderEl.innerHTML = ''; 

            historyModal.style.display = 'flex';

            fetch(`/history/user/${encodeURIComponent(serialNumber)}`)
                .then(response => { // Tambahkan penanganan error fetch yang lebih baik
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Server returned an error (history):', response.status, text);
                            let errorData = { message: `HTTP error ${response.status}.` };
                            try { errorData = JSON.parse(text); } catch (e) { /* abaikan jika bukan JSON */ }
                            throw errorData;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    historyTableHeaderEl.innerHTML = `
                        <tr>
                            <th class="cell-history-user">Pengguna</th>
                            <th class="cell-history-tgl-awal">Penyerahan</th>
                            <th class="cell-history-tgl-akhir">Pengembalian</th>
                            <th class="cell-history-status">Status</th>
                            <th class="cell-history-keterangan">Keterangan</th>
                        </tr>
                    `;
                    if (data && data.success && Array.isArray(data.history) && data.history.length > 0) {
                        let tableRowsHTML = '';
                        data.history.forEach(item => {
                            const username = (item && item.username) ? item.username : '-';
                            const tglAwal = (item && item.tanggal_awal) ? formatDate(item.tanggal_awal) : '-';
                            const tglAkhir = (item && item.tanggal_ahir) ? formatDate(item.tanggal_ahir) : '-';
                            const status = (item && item.status) ? item.status : '-';
                            const keterangan = (item && item.keterangan) ? item.keterangan : '-';
                            tableRowsHTML += `
                                <tr>
                                    <td class="cell-history-user" title="${username}">${username}</td>
                                    <td class="cell-history-tgl-awal">${tglAwal}</td>
                                    <td class="cell-history-tgl-akhir">${tglAkhir}</td>
                                    <td class="cell-history-status" title="${status}">${status}</td>
                                    <td class="cell-history-keterangan" title="${keterangan}">${keterangan}</td>
                                </tr>`;
                        });
                        historyTableBodyEl.innerHTML = tableRowsHTML;
                    } else {
                        historyTableBodyEl.innerHTML = '<tr><td colspan="5" style="padding:15px; text-align:center;">Tidak ada riwayat pengguna.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching atau memproses user history:', error);
                    let displayError = 'Gagal memuat riwayat.';
                    if (error && typeof error === 'object' && error.message) displayError = error.message;
                    else if (typeof error === 'string') displayError = error;
                    
                    historyTableBodyEl.innerHTML = `<tr><td colspan="5" style="padding:15px; text-align:center; color:red;">${displayError}</td></tr>`;
                    historyTableHeaderEl.innerHTML = `
                        <tr>
                            <th class="cell-history-user">Pengguna</th>
                            <th class="cell-history-tgl-awal">Penyerahan</th>
                            <th class="cell-history-tgl-akhir">Pengembalian</th>
                            <th class="cell-history-status">Status</th>
                            <th class="cell-history-keterangan">Keterangan</th>
                        </tr>
                    `;
                });
            }

        // ==============================================================================
        // DOMContentLoaded: INSIALISASI EVENT LISTENERS DAN FUNGSI YANG BERGANTUNG DOM
        // ==============================================================================
        document.addEventListener('DOMContentLoaded', () => {

            const productTableRowsContainer = document.getElementById('productTableRowsContainer');
            const paginationContainer = document.getElementById('realtimePaginationContainer');
            const filterPerusahaanSelect = document.getElementById('filter_perusahaan');
            const filterJenisBarangSelect = document.getElementById('filter_jenis_barang');

            const addAssetModal = document.getElementById('addAssetModal');
            const openAddAssetModalButton = document.getElementById('openAddAssetModalButton');
            const closeAddAssetModalBtn = document.getElementById('closeAddAssetModalBtn');
            const cancelAddAssetModalBtn = document.getElementById('cancelAddAssetModalBtn');
            const addAssetForm = document.getElementById('addAssetForm');
            const submitAddAssetBtn = document.getElementById('submitAddAssetBtn');
            const resetFilterInMenuBtn = document.getElementById('resetFilterInMenuBtn');

            const detailModalOverlayElement = document.getElementById('deviceInfoModal');
            const closeDetailModalButtonElement = document.getElementById('closeDetailModalButton');

            const mainSearchInput = document.getElementById('mainSearchInput');
            const clearMainSearchBtn = document.getElementById('clearMainSearchBtn');
            const filterToggleButton = document.querySelector(".jsFilter");
            const filterMenu = document.querySelector(".filter-button-wrapper .filter-menu");

            const tableHeader = document.querySelector('.products-header');
            const tableBody = document.getElementById('productTableRowsContainer');

            let originalRowsHTML = ''; // Akan menyimpan HTML asli dari tabel
            let activeSortKey = null;
            let activeSortDirection = 'none';

            // Fungsi untuk menangkap kondisi asli tabel sebagai string HTML
            function captureOriginalState() {
                if (tableBody) {
                    originalRowsHTML = tableBody.innerHTML;
                }
                // Reset visual dan state saat data baru dimuat
                activeSortKey = null;
                activeSortDirection = 'none';
                tableHeader.querySelectorAll('.sortable-header').forEach(header => {
                    header.classList.remove('sorted-asc', 'sorted-desc');
                });
            }

            // Pasang satu event listener utama pada seluruh header tabel
            tableHeader.addEventListener('click', (e) => {
                const headerCell = e.target.closest('.sortable-header');
                if (!headerCell) return;

                const sortKey = headerCell.dataset.sortBy;
                const sortType = headerCell.dataset.sortType || 'text';

                // --- MULAI LOGIKA BARU ---
                if (activeSortKey !== sortKey) {
                    // Klik pada kolom BARU
                    activeSortKey = sortKey;
                    // SPECIAL CASE: Jika kolom 'No' yang diklik, langsung ke 'desc'
                    if (sortKey === 'no') {
                        activeSortDirection = 'desc';
                    } else {
                        // Kolom lain mulai dari 'asc' seperti biasa
                        activeSortDirection = 'asc';
                    }
                } else {
                    // Klik pada kolom yang SAMA
                    // SPECIAL CASE: Jika kolom 'No' yang sedang aktif
                    if (sortKey === 'no') {
                        // Siklusnya hanya 'desc' -> 'none'
                        activeSortDirection = 'none';
                    } else {
                        // Siklus normal untuk kolom lain
                        if (activeSortDirection === 'asc') {
                            activeSortDirection = 'desc';
                        } else {
                            activeSortDirection = 'none';
                        }
                    }
                }

                // 2. Terapkan sorting atau reset
                if (activeSortDirection === 'none') {
                    activeSortKey = null;
                    if (tableBody) {
                        tableBody.innerHTML = originalRowsHTML; // Kembalikan ke HTML asli
                    }
                } else {
                    const rows = Array.from(tableBody.querySelectorAll('.products-row'));
                    
                    // Cari index kolom yang benar berdasarkan 'data-sort-by'
                    const allHeaders = Array.from(tableHeader.querySelectorAll('.product-cell'));
                    const columnIndex = allHeaders.findIndex(th => th.dataset.sortBy === sortKey);
                    
                    if (columnIndex === -1) return; // Jika kolom tidak ditemukan, keluar

                    rows.sort((rowA, rowB) => {
                        const cellsA = rowA.querySelectorAll('.product-cell');
                        const cellsB = rowB.querySelectorAll('.product-cell');

                        const valueA = cellsA[columnIndex] ? cellsA[columnIndex].textContent.trim() : '';
                        const valueB = cellsB[columnIndex] ? cellsB[columnIndex].textContent.trim() : '';
                        
                        let comparison = 0;
                        if (sortType === 'date') {
                            comparison = parseDate(valueA) - parseDate(valueB);
                        } else {
                            comparison = valueA.localeCompare(valueB, undefined, { numeric: true, sensitivity: 'base' });
                        }

                        return activeSortDirection === 'asc' ? comparison : -comparison;
                    });

                    // Kosongkan tabel dan isi kembali dengan baris yang sudah diurutkan
                    tableBody.innerHTML = '';
                    rows.forEach(row => tableBody.appendChild(row));
                }

                // 3. Perbarui tampilan panah di semua header
                tableHeader.querySelectorAll('.sortable-header').forEach(header => {
                    header.classList.remove('sorted-asc', 'sorted-desc');
                    if (header.dataset.sortBy === activeSortKey) {
                        if (activeSortDirection === 'asc') {
                            header.classList.add('sorted-asc');
                        } else if (activeSortDirection === 'desc') {
                            header.classList.add('sorted-desc');
                        }
                    }
                });
            });

            // Fungsi helper modal, bisa tetap di sini jika hanya dipakai di dalam DOMContentLoaded
            function openModalElement(modalElement, formToReset = null) {
                if (modalElement) {
                    modalElement.classList.add('show');
                    if (formToReset) {
                        formToReset.reset();
                    }
                    if (formToReset) {
                        formToReset.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                        formToReset.querySelectorAll('.form-control.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    }
                }
            }

            function closeModalElement(modalElement) {
                if (modalElement) {
                    modalElement.classList.remove('show');
                }
            }

            function parseDate(dateStr) {
                const parts = dateStr.split('-');
                // parts[2] = YYYY, parts[1] = MM, parts[0] = DD
                return new Date(parts[2], parts[1] - 1, parts[0]);
            }
            
            function displayValidationErrorsOnForm(errors, formElement) {
                if (!formElement) return;
                // Clear previous errors on this form
                formElement.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                formElement.querySelectorAll('.form-control.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                for (const field in errors) {
                    let errorElement = formElement.querySelector(`#${field}_error`); // Default untuk Add Asset
                    if (formElement.id === 'serahTerimaAsetForm' && !errorElement) { // Fallback untuk Serah Terima
                        errorElement = formElement.querySelector(`#${field}_serah_error`);
                    }
                    
                    const inputElement = formElement.querySelector(`[name="${field}"]`);
                    if (errorElement) {
                        errorElement.textContent = errors[field][0];
                    }
                    if (inputElement) {
                        inputElement.classList.add('is-invalid');
                    }
                }
            }

            function performRealtimeSearch(page = 1) {
                if (!productTableRowsContainer || !paginationContainer) return;
                const keyword = mainSearchInput ? mainSearchInput.value : '';
                const perusahaan = filterPerusahaanSelect ? filterPerusahaanSelect.value : '';
                const jenisBarang = filterJenisBarangSelect ? filterJenisBarangSelect.value : '';

                productTableRowsContainer.innerHTML = '<div class="products-row"><div class="product-cell" style="text-align:center; flex-basis:100%; padding: 40px;">Memuat data... <i class="fas fa-spinner fa-spin"></i></div></div>';
                paginationContainer.innerHTML = '';

                const params = new URLSearchParams({
                    search_no_asset: keyword,
                    filter_perusahaan: perusahaan,
                    filter_jenis_barang: jenisBarang,
                    page: page
                });

                fetch(`{{ route('dashboard.search.realtime') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(responseData => {
                    let tableRowsHtml = '';
                    if (responseData.data && responseData.data.length > 0) {
                        let currentItemNumber = responseData.first_item || 1;
                        responseData.data.forEach(barang => {
                            let tglPengadaanFormatted = formatDate(barang.tgl_pengadaan);
                            tableRowsHtml += `
                                <div class="products-row">
                                    <div class="product-cell cell-no">${currentItemNumber++}</div>
                                    <div class="product-cell cell-perusahaan" title="${barang.perusahaan || ''}">${barang.perusahaan || ''}</div>
                                    <div class="product-cell cell-jenis-barang" title="${barang.jenis_barang || ''}">${barang.jenis_barang || ''}</div>
                                    <div class="product-cell cell-no-asset" title="${barang.no_asset || ''}">${barang.no_asset || ''}</div>
                                    <div class="product-cell cell-merek" title="${barang.merek || ''}">${barang.merek || ''}</div>
                                    <div class="product-cell cell-tgl-pengadaan">${tglPengadaanFormatted}</div>
                                    <div class="product-cell cell-serial-number" title="${barang.serial_number || ''}">${barang.serial_number || ''}</div>
                                    <div class="product-cell cell-aksi">
                                        <button class="action-btn-table detail-btn-table-js" onclick="openDetailModal(${barang.id})" title="Detail Aset">
                                            <i class="fas fa-info-circle"></i>
                                            <span>Detail</span>
                                        </button>
                                    </div>
                                </div>`;
                        });
                    } else {
                        tableRowsHtml = `<div class="products-row"><div class="product-cell" style="text-align:center; flex-basis:100%; padding: 20px;">Tidak ada data aset ditemukan.</div></div>`;
                    }
                    productTableRowsContainer.innerHTML = tableRowsHtml;
                    captureOriginalState();
                    if (responseData.links) {
                        paginationContainer.innerHTML = responseData.links;
                        setupAjaxPagination();
                    }
                    if (responseData.inventorySummary) {
                        updateInventorySummary(responseData.inventorySummary);
                    }
                    captureOriginalState();
                    if (responseData.links) {
                        paginationContainer.innerHTML = responseData.links;
                        setupAjaxPagination();
                    }
                })
                .catch(error => {
                    console.error('Error performing real-time search:', error);
                    productTableRowsContainer.innerHTML = `<div class="products-row"><div class="product-cell" style="text-align:center; flex-basis:100%; padding: 20px; color:red;">Gagal memuat data.</div></div>`;
                });
            }

            function setupAjaxPagination() {
                if (!paginationContainer) return;
                paginationContainer.querySelectorAll('.pagination a').forEach(link => {
                    const newLink = link.cloneNode(true);
                    link.parentNode.replaceChild(newLink, link);
                    newLink.addEventListener('click', function(event) {
                        event.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page') || 1;
                        performRealtimeSearch(page);
                    });
                });
            }

            function updateInventorySummary(summaryData) {
                if (!summaryData) return;

                for (const type in summaryData) {
                    const safeIdPart = type.toLowerCase().replace('/', '-'); 
                    const elementId = `summary-count-${safeIdPart}`;
                    const countElement = document.getElementById(elementId);

                    if (countElement) {
                        countElement.textContent = summaryData[type];
                    } else {
                        // Ini untuk debugging jika ada ID yang tidak cocok
                        console.warn(`Elemen summary dengan ID '${elementId}' tidak ditemukan.`);
                    }
                }
            }

            function checkAndUpdateFilterStates() {
                if (!filterPerusahaanSelect || !filterJenisBarangSelect || !resetFilterInMenuBtn) return;
                const perusahaanAktif = filterPerusahaanSelect.value !== '';
                const jenisBarangAktif = filterJenisBarangSelect.value !== '';
                const isAnyFilterActive = perusahaanAktif || jenisBarangAktif;
                resetFilterInMenuBtn.disabled = !isAnyFilterActive;
            }

            setupAjaxPagination();
            checkAndUpdateFilterStates();
            captureOriginalState();

            // Event Listeners untuk Search dan Filter
            if (mainSearchInput) {
                mainSearchInput.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => performRealtimeSearch(1), DEBOUNCE_DELAY);
                });
                mainSearchInput.addEventListener('keypress', (event) => {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        clearTimeout(debounceTimer);
                        performRealtimeSearch(1);
                    }
                });
            }
            if (clearMainSearchBtn && mainSearchInput) {
                clearMainSearchBtn.addEventListener('click', () => {
                    mainSearchInput.value = '';
                    performRealtimeSearch(1);
                });
            }
            if (filterPerusahaanSelect) {
                filterPerusahaanSelect.addEventListener('change', () => {
                    performRealtimeSearch(1);
                    checkAndUpdateFilterStates(); // Panggil ini
                });
            }
            if (filterJenisBarangSelect) {
                filterJenisBarangSelect.addEventListener('change', () => {
                    performRealtimeSearch(1);
                    checkAndUpdateFilterStates(); // Panggil ini
                });
            }
            if (resetFilterInMenuBtn) {
                resetFilterInMenuBtn.addEventListener('click', () => {
                    if (filterPerusahaanSelect) filterPerusahaanSelect.value = '';
                    if (filterJenisBarangSelect) filterJenisBarangSelect.value = '';
                    if (mainSearchInput) mainSearchInput.value = ''; // Tambahkan ini jika diinginkan

                    performRealtimeSearch(1);
                    checkAndUpdateFilterStates(); 

                    if (filterMenu && filterMenu.classList.contains('active')) {
                        filterMenu.classList.remove('active');
                    }
                });
            }

            // Modal Tambah Aset
            if (openAddAssetModalButton && addAssetModal) {
                openAddAssetModalButton.addEventListener('click', () => openModalElement(addAssetModal, addAssetForm));
            }
            // ... (listener tombol close/cancel/overlay click untuk addAssetModal) ...
            if (closeAddAssetModalBtn) closeAddAssetModalBtn.addEventListener('click', () => closeModalElement(addAssetModal));
            if (cancelAddAssetModalBtn) cancelAddAssetModalBtn.addEventListener('click', () => closeModalElement(addAssetModal));
            if (addAssetModal) window.addEventListener('click', (e) => { if (e.target === addAssetModal) closeModalElement(addAssetModal); });


            if (addAssetForm && submitAddAssetBtn) {
                addAssetForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const formData = new FormData(addAssetForm);
                    // ... (AJAX submit untuk tambah aset) ...
                    submitAddAssetBtn.textContent = 'Menyimpan...';
                    submitAddAssetBtn.disabled = true;

                    fetch("{{ route('barang.store') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json' },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success || (data.status && data.status === 201)) { // Cek kedua kondisi
                            alert(data.message || data.success || 'Aset berhasil ditambahkan.');
                            closeModalElement(addAssetModal);
                            performRealtimeSearch(); // Refresh tabel
                        } else if (data.errors) {
                            displayValidationErrorsOnForm(data.errors, addAssetForm);
                        } else {
                            alert(data.error || data.message || 'Gagal menambahkan aset.');
                        }
                    })
                    .catch(err => {
                        console.error("Error add asset:", err);
                        alert('Terjadi kesalahan. Cek konsol.');
                         if (err.body && err.body.errors) { // Jika error dari response.json().then(errData => { throw { body: errData } })
                            displayValidationErrorsOnForm(err.body.errors, addAssetForm);
                        }
                    })
                    .finally(() => {
                        submitAddAssetBtn.textContent = 'Simpan Aset';
                        submitAddAssetBtn.disabled = false;
                    });
                });
            }

            // Modal Detail Aset (hanya menutup)
            if (closeDetailModalButtonElement) closeDetailModalButtonElement.addEventListener('click', () => { if(detailModalOverlayElement) detailModalOverlayElement.style.display = 'none'; });
            if (detailModalOverlayElement) {
                detailModalOverlayElement.addEventListener('click', (e) => { if (e.target === detailModalOverlayElement) detailModalOverlayElement.style.display = 'none'; });
                document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && detailModalOverlayElement.style.display === 'flex') detailModalOverlayElement.style.display = 'none'; });
            }


            // Modal Serah Terima Aset
            const serahTerimaAsetModalElement = document.getElementById('serahTerimaAsetModal');
            const serahTerimaStatusSelect = document.getElementById('serahTerimaStatus');
            const closeSerahTerimaBtn = document.getElementById('closeSerahTerimaAsetModalButton');
            const cancelSerahTerimaBtn = document.getElementById('cancelSerahTerimaAsetModalBtn');
            const serahTerimaForm = document.getElementById('serahTerimaAsetForm');
            const submitSerahTerimaBtn = document.getElementById('submitSerahTerimaAsetBtn');

            if (serahTerimaStatusSelect) {
                serahTerimaStatusSelect.addEventListener('change', handleSerahTerimaStatusChange); // Panggil fungsi global
            }
            // ... (listener tombol close/cancel/overlay click untuk serahTerimaAsetModal) ...
            if (closeSerahTerimaBtn) closeSerahTerimaBtn.addEventListener('click', () => { if(serahTerimaAsetModalElement) serahTerimaAsetModalElement.style.display = 'none';});
            if (cancelSerahTerimaBtn) cancelSerahTerimaBtn.addEventListener('click', () => { if(serahTerimaAsetModalElement) serahTerimaAsetModalElement.style.display = 'none';});
            if (serahTerimaAsetModalElement) {
                 serahTerimaAsetModalElement.addEventListener('click', (e) => { if (e.target === serahTerimaAsetModalElement) serahTerimaAsetModalElement.style.display = 'none'; });
                 document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && serahTerimaAsetModalElement.style.display === 'flex') serahTerimaAsetModalElement.style.display = 'none'; });
            }

            if (serahTerimaForm && submitSerahTerimaBtn) {
                serahTerimaForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const formData = new FormData(serahTerimaForm);
                    // ... (AJAX submit untuk serah terima aset) ...
                    submitSerahTerimaBtn.textContent = 'Menyimpan...';
                    submitSerahTerimaBtn.disabled = true;

                    fetch("{{ route('aset.serahterima.store') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json' },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message || 'Data serah terima berhasil disimpan.');
                            if(serahTerimaAsetModalElement) serahTerimaAsetModalElement.style.display = 'none';
                            // Cek apakah modal detail terbuka untuk aset yang sama
                            if (detailModalOverlayElement && detailModalOverlayElement.style.display === 'flex' && currentAssetIdForSerahTerima == formData.get('asset_id')) {
                                openDetailModal(currentAssetIdForSerahTerima); // Re-fetch dan buka lagi modal detail
                            } else {
                                performRealtimeSearch(); // Refresh tabel jika modal detail tidak terbuka atau beda aset
                            }
                        } else if (data.errors) {
                            displayValidationErrorsOnForm(data.errors, serahTerimaForm);
                        } else {
                            alert(data.message || 'Gagal menyimpan data serah terima.');
                        }
                    })
                    .catch(err => {
                        console.error("Error serah terima:", err);
                        alert('Terjadi kesalahan. Cek konsol.');
                         if (err.body && err.body.errors) {
                            displayValidationErrorsOnForm(err.body.errors, serahTerimaForm);
                        }
                    })
                    .finally(() => {
                        submitSerahTerimaBtn.textContent = 'Simpan Perubahan';
                        submitSerahTerimaBtn.disabled = false;
                    });
                });
            }

            // Modal User History (hanya menutup)
            const userHistoryModalElement = document.getElementById('userHistoryModal');
            const closeUserHistoryBtn = document.getElementById('closeUserHistoryModalButton');
            if (closeUserHistoryBtn) closeUserHistoryBtn.addEventListener('click', () => { if(userHistoryModalElement) userHistoryModalElement.style.display = 'none';});
            if (userHistoryModalElement) {
                userHistoryModalElement.addEventListener('click', (e) => { if (e.target === userHistoryModalElement) userHistoryModalElement.style.display = 'none'; });
                document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && userHistoryModalElement.style.display === 'flex') userHistoryModalElement.style.display = 'none'; });
            }

            // Fitur UI Dashboard Lainnya (Theme, Sidebar, Filter Toggle)
            if (filterToggleButton && filterMenu) {
                filterToggleButton.addEventListener("click", function (event) {
                    event.stopPropagation(); // Mencegah event menyebar ke document
                    filterMenu.classList.toggle("active");
                });
            }
            if (resetFilterInMenuBtn) {
                resetFilterInMenuBtn.addEventListener('click', () => {
                    if (filterPerusahaanSelect) filterPerusahaanSelect.value = '';
                    if (filterJenisBarangSelect) filterJenisBarangSelect.value = '';


                    performRealtimeSearch(1);
                    checkAndUpdateFilterStates(); 

                    if (filterMenu && filterMenu.classList.contains('active')) {
                        filterMenu.classList.remove('active');
                    }
                });
            }

            if (filterMenu && filterToggleButton) { // Pastikan kedua elemen ada
                document.addEventListener('click', function(event) {
                    const isClickInsideFilterMenu = filterMenu.contains(event.target);
                    const isClickOnFilterToggleButton = filterToggleButton.contains(event.target);

                    if (filterMenu.classList.contains('active') && !isClickInsideFilterMenu && !isClickOnFilterToggleButton) {
                        filterMenu.classList.remove('active');
                    }
                });
            }

            const modeSwitch = document.querySelector('.mode-switch');
            if (modeSwitch) {
                modeSwitch.addEventListener('click', function () {
                    document.documentElement.classList.toggle('light');
                    modeSwitch.classList.toggle('active');
                    // Anda mungkin ingin menyimpan preferensi tema di localStorage
                });
                 // Load tema dari localStorage jika ada
                if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('light');
                    modeSwitch.classList.add('active');
                }
            }
            const burgerMenuButton = document.getElementById('burger-menu');
            const sidebarElement = document.querySelector('.sidebar');
            const SIDEBAR_COLLAPSED_KEY = 'sidebarCollapsedITventory'; // Gunakan nama unik
            function initializeSidebarState() {
                if (!burgerMenuButton || !sidebarElement) return;
                const isCollapsed = localStorage.getItem(SIDEBAR_COLLAPSED_KEY) === 'true';
                if (isCollapsed) {
                    sidebarElement.classList.add('collapsed');
                    burgerMenuButton.classList.remove('active');
                } else {
                    sidebarElement.classList.remove('collapsed');
                    burgerMenuButton.classList.add('active');
                }
            }
            initializeSidebarState();
            if (burgerMenuButton && sidebarElement) {
                burgerMenuButton.addEventListener('click', () => {
                    sidebarElement.classList.toggle('collapsed');
                    burgerMenuButton.classList.toggle('active');
                    localStorage.setItem(SIDEBAR_COLLAPSED_KEY, sidebarElement.classList.contains('collapsed'));
                });
            }
            function setActiveSidebarLink() {
                const currentPathname = window.location.pathname; // e.g., "/dashboard" or "/"
                const sidebarNavItems = document.querySelectorAll('.sidebar .sidebar-list-item');
                let exactMatchFound = false;

                // Hapus class 'active' dari semua item terlebih dahulu
                sidebarNavItems.forEach(item => {
                    item.classList.remove('active');
                });
                // Jika tidak ada yang aktif, coba aktifkan link dashboard jika URL adalah root
                if (!document.querySelector('.sidebar .sidebar-list-item.active')) {
                    const dashboardLinkEl = document.querySelector('.sidebar-list-item a[href="{{ route('dashboard.index') }}"]');
                     if (dashboardLinkEl && (window.location.pathname === '/' || window.location.pathname === new URL(dashboardLinkEl.href).pathname )) {
                        dashboardLinkEl.parentElement.classList.add('active');
                    }
                }
            }
            setActiveSidebarLink();
        });
    </script>
</body>
</html>