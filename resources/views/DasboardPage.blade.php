<!DOCTYPE html>
<html lang="id"> <!-- Class 'light' akan ditambahkan oleh JS dashboard jika mode light aktif -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Aplikasi</title> <!-- Judul bisa diubah -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

    <style>

        /* === CSS MODAL TAMBAH ASET === */
        .modal {
            display: none; /* Sembunyikan secara default */
            position: fixed; /* Tetap di viewport */
            z-index: 1050; /* Di atas elemen lain */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Scroll jika konten panjang */
            background-color: rgba(0,0,0,0.5); /* Latar belakang semi-transparan */
            justify-content: center; /* Pusatkan modal secara horizontal */
            align-items: center; /* Pusatkan modal secara vertikal */
        }

        .modal.show {
            display: flex; /* Tampilkan modal */
        }

        .modal-content {
            background-color: var(--app-content-secondary-color); /* Warna dari tema dashboard Anda */
            color: var(--app-content-main-color);
            margin: auto;
            padding: 25px;
            border: 1px solid var(--table-border);
            border-radius: 8px;
            width: 90%;
            max-width: 600px; /* Lebar maksimal modal */
            box-shadow: var(--filter-shadow);
            position: relative;
        }

        html.light .modal-content {
            background-color: #fff; /* Warna untuk light mode */
            border-color: #ddd;
        }

        .modal-header {
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

        .modal-title {
            font-size: 1.5rem;
            font-weight: 500;
            margin: 0;
        }

        .close-button {
            color: var(--app-content-main-color);
            font-size: 1.8rem;
            font-weight: bold;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0 5px;
        }
        html.light .close-button {
            color: #555;
        }
        .close-button:hover,
        .close-button:focus {
            /* color: red; */
            text-decoration: none;
        }

        .modal-body form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .modal-body form input[type="text"],
        .modal-body form input[type="date"],
        .modal-body form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid var(--table-border);
            border-radius: 4px;
            background-color: var(--app-bg); /* Sesuaikan dengan input dashboard */
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
        .modal-body form select:focus {
            border-color: var(--action-color);
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(var(--action-color), 0.25); /* Ganti jika var(--action-color) tidak bisa langsung dipakai di rgba */
        }

        .modal-footer {
            padding-top: 15px;
            border-top: 1px solid var(--table-border);
            margin-top: 20px;
            text-align: right;
        }
        html.light .modal-footer {
            border-top-color: #eee;
        }

        .modal-footer .btn {
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
            background-color: var(--filter-reset); /* Atau warna lain */
            color: white;
        }
        html.light .modal-footer .btn-secondary {
            background-color: #6c757d;
        }

        /* Untuk pesan error validasi (opsional, styling bisa disesuaikan) */
        .invalid-feedback {
            color: #dc3545; /* Warna merah untuk error */
            font-size: 0.875em;
            margin-top: .25rem;
        }
        input.is-invalid, select.is-invalid {
            border-color: #dc3545 !important;
        }
        /* === AKHIR CSS MODAL === */

        /* === CSS DASHBOARD === */
        :root {
          --app-bg: #101827;
          --sidebar: rgba(21, 30, 47,1);
          --sidebar-main-color: #fff;
          --table-border: #1a2131;
          --table-header: #1a2131;
          --app-content-main-color: #fff;
          --sidebar-link: #fff;
          --sidebar-active-link: #1d283c;
          --sidebar-hover-link: #1a2539;
          --action-color: #2869ff;
          --action-color-hover: #6291fd;
          --app-content-secondary-color: #1d283c;
          --filter-reset: #2c394f;
          --filter-shadow: rgba(16, 24, 39, 0.8) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
          /* Tambahkan variabel untuk lebar sidebar */
          --sidebar-width-expanded: 200px;
          --sidebar-width-collapsed: 65px;
        }

        html.light:root {
          --app-bg: #fff;
          --sidebar: #f3f6fd;
          --app-content-secondary-color: #f3f6fd;
          --app-content-main-color: #1f1c2e;
          --sidebar-link: #1f1c2e;
          --sidebar-hover-link: rgba(195, 207, 244, 0.5);
          --sidebar-active-link: rgba(195, 207, 244, 1);
          --sidebar-main-color: #1f1c2e;
          --filter-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
          margin: 0;
          padding: 0;
          overflow-y: auto;
          overflow-x: hidden;
          font-family: 'Poppins', sans-serif;
          background-color: var(--app-bg);
          color: var(--app-content-main-color);
          -webkit-font-smoothing: antialiased;
          -moz-osx-font-smoothing: grayscale;
          box-sizing: border-box;
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
          box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
          max-width: 2000px;
          margin: 0 auto;
        }

        .sidebar {
          flex-basis: var(--sidebar-width-expanded);
          max-width: var(--sidebar-width-expanded);
          min-width: 200px;
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
            margin-right: auto;
        }

        .sidebar.collapsed .sidebar-header .app-icon svg {
          display: none;
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
        }
        .app-icon {
          color: var(--sidebar-main-color);
          transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .app-icon svg {
          width: 24px;
          height: 24px;
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
        .sidebar-list-item svg { margin-right: 8px; flex-shrink: 0; transition: margin-right 0.3s ease-in-out; }
        .sidebar-list-item:hover { background-color: var(--sidebar-hover-link); }
        .sidebar-list-item.active { background-color: var(--sidebar-active-link);}
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

        .app-icon {
          color: var(--sidebar-main-color);
        }
        .app-icon svg {
          width: 24px;
          height: 24px;
        }

        .app-content {
          padding: 16px;
          height: 100%;
          flex: 1;
          max-height: 100%;
          display: flex;
          flex-direction: column;
          overflow-x: hidden;
        }
        .app-content-header {
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 0 4px;
        }
        .app-content-headerText {
          color: var(--app-content-main-color);
          font-size: 24px;
          line-height: 32px;
          margin: 0;
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
        .app-content-headerButton:hover {
          background-color: var(--action-color-hover);
        }
        .app-content-actions {
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 16px 4px;
        }
        .app-content-actions-wrapper {
          display: flex;
          align-items: center;
          margin-left: auto;
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

        .action-button {
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
          z-index: 1000;
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

        .products-area-wrapper {
          width: 100%;
          max-height: 100%;
          overflow: auto;
          padding: 0 4px;
        }

        .tableView .products-header,
        .tableView .products-row {
          display: flex;
        }

        .tableView .products-header {
          align-items: center;
          border-radius: 4px;
          background-color: var(--app-content-secondary-color);
          position: sticky;
          top: 0;
          z-index: 10;
        }
        .tableView .products-row {
          align-items: center;
          border-radius: 4px;
        }
        .tableView .products-row:hover {
          box-shadow: var(--filter-shadow);
          background-color: var(--app-content-secondary-color);
        }
        .tableView .products-row .cell-more-button {
          display: none;
        }

      .tableView .product-cell {
        padding: 8px 12px;
        color: var(--app-content-main-color);
        font-size: 14px;
        display: flex;
        align-items: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

        .tableView .product-cell.cell-no { flex: 0 0 40px; min-width: 40px; justify-content: center;}
        .tableView .product-cell.cell-perusahaan { flex: 1 1 100px; min-width: 100px; }
        .tableView .product-cell.cell-jenis-barang { flex: 1 1 120px; min-width: 120px; }
        .tableView .product-cell.cell-no-asset { flex: 1 1 180px; min-width: 180px; }
        .tableView .product-cell.cell-merek { flex: 1 1 200px; min-width: 200px; }
        .tableView .product-cell.cell-tgl-pengadaan { flex: 1 1 140px; min-width: 140px; }
        .tableView .product-cell.cell-serial-number { flex: 1 1 150px; min-width: 150px; }
        .tableView .product-cell.cell-aksi {
            flex: 0 0 180px;
            min-width: 180px;
            justify-content: center;
            overflow: visible;
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
          display: none;
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
            white-space: nowrap;
        }
        .product-cell.cell-aksi .action-btn-table:hover {
            background-color: var(--action-color);
            color: #fff;
        }
        .product-cell.cell-aksi .action-btn-table.update-btn {
        }
        .product-cell.cell-aksi .action-btn-table.detail-btn {
            border-color: #5cb85c;
            color: #5cb85c;
        }
        .product-cell.cell-aksi .action-btn-table.detail-btn:hover {
            background-color: #5cb85c;
            color: #fff;
        }

        /* CSS untuk .gridView telah dihapus karena tidak digunakan lagi */

        /* === AKHIR CSS DASHBOARD === */

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
            z-index: 10;
            flex-shrink: 0;
        }

        .burger-button span {
            display: block;
            width: 100%;
            height: 3px;
            background-color: var(--app-content-main-color);
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

    </style>
</head>
<body>

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
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M507.606 371.054a187.217 187.217 0 00-23.051-19.606c-17.316 19.999-37.648 36.808-60.572 50.041-35.508 20.505-75.893 31.452-116.875 31.711 21.762 8.776 45.224 13.38 69.396 13.38 49.524 0 96.084-19.286 131.103-54.305a15 15 0 004.394-10.606 15.028 15.028 0 00-4.395-10.615zM27.445 351.448a187.392 187.392 0 00-23.051 19.606C1.581 373.868 0 377.691 0 381.669s1.581 7.793 4.394 10.606c35.019 35.019 81.579 54.305 131.103 54.305 24.172 0 47.634-4.604 69.396-13.38-40.985-.259-81.367-11.206-116.879-31.713-22.922-13.231-43.254-30.04-60.569-50.039zM103.015 375.508c24.937 14.4 53.928 24.056 84.837 26.854-53.409-29.561-82.274-70.602-95.861-94.135-14.942-25.878-25.041-53.917-30.063-83.421-14.921.64-29.775 2.868-44.227 6.709-6.6 1.576-11.507 7.517-11.507 14.599 0 1.312.172 2.618.512 3.885 15.32 57.142 52.726 100.35 96.309 125.509zM324.148 402.362c30.908-2.799 59.9-12.454 84.837-26.854 43.583-25.159 80.989-68.367 96.31-125.508.34-1.267.512-2.573.512-3.885 0-7.082-4.907-13.023-11.507-14.599-14.452-3.841-29.306-6.07-44.227-6.709-5.022 29.504-15.121 57.543-30.063 83.421-13.588 23.533-42.419 64.554-95.862 94.134zM187.301 366.948c-15.157-24.483-38.696-71.48-38.696-135.903 0-32.646 6.043-64.401 17.945-94.529-16.394-9.351-33.972-16.623-52.273-21.525-8.004-2.142-16.225 2.604-18.37 10.605-16.372 61.078-4.825 121.063 22.064 167.631 16.325 28.275 39.769 54.111 69.33 73.721zM324.684 366.957c29.568-19.611 53.017-45.451 69.344-73.73 26.889-46.569 38.436-106.553 22.064-167.631-2.145-8.001-10.366-12.748-18.37-10.605-18.304 4.902-35.883 12.176-52.279 21.529 11.9 30.126 17.943 61.88 17.943 94.525.001 64.478-23.58 111.488-38.702 135.912zM266.606 69.813c-2.813-2.813-6.637-4.394-10.615-4.394a15 15 0 00-10.606 4.394c-39.289 39.289-66.78 96.005-66.78 161.231 0 65.256 27.522 121.974 66.78 161.231 2.813 2.813 6.637 4.394 10.615 4.394s7.793-1.581 10.606-4.394c39.248-39.247 66.78-95.96 66.78-161.231.001-65.256-27.511-121.964-66.78-161.231z"/></svg>
              </div>
              <button id="burger-menu" class="burger-button" title="Toggle Sidebar">
                <span></span>
                <span></span>
                <span></span>
              </button>
            </div>
            <ul class="sidebar-list">
              <li class="sidebar-list-item">
                <a href="#">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                  <span>Home</span>
                </a>
              </li>
              <li class="sidebar-list-item active">
                  <a href="{{ route('dashboard.index') }}">  {{-- Link ke halaman dashboard --}}
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                      <span>Data Aset</span>
                  </a>
              </li>
              <li class="sidebar-list-item">
                <a href="#">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
                  <span>Statistics</span>
                </a>
              </li>
              <li class="sidebar-list-item">
                <a href="#">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                  <span>Inbox</span>
                </a>
              </li>
              <li class="sidebar-list-item">
                <a href="#">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                  <span>Notifications</span>
                </a>
              </li>
            </ul>
            <div class="account-info">
              <div class="account-info-picture">
                <img src="https://images.unsplash.com/photo-1527736947477-2790e28f3443?ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTE2fHx3b21hbnxlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=900&q=60" alt="Account">
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
              <h1 class="app-content-headerText">Data Aset</h1>
              <div style="margin-left: auto; display:flex; align-items:center;">
                <button class="mode-switch" title="Switch Theme">
                    <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
                    <defs></defs>
                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                    </svg>
                </button>
                {{-- Tambahkan ID unik untuk tombol ini --}}
                <button id="openAddAssetModalButton" class="app-content-headerButton">+ Tambah Aset</button>
                <button id="logoutButton" class="app-content-headerButton" style="background-color: #e74c3c; margin-left: 8px;">Logout</button>
              </div>
            </div>

            {{-- FORM FILTER MULAI DI SINI --}}
            <form action="{{ route('dashboard.index') }}" method="GET" id="filterForm">
                <div class="app-content-actions"> {{-- Ini adalah container utama untuk search bar dan filter button wrapper --}}
                    <input class="search-bar" placeholder="Cari No Asset ..." type="text" name="search_no_asset" value="{{ $searchNoAsset ?? old('search_no_asset') }}">
                    <div class="app-content-actions-wrapper"> {{-- Ini wrapper untuk tombol filter --}}
                        <div class="filter-button-wrapper">
                            <button type="button" class="action-button filter jsFilter">
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

                                <div class="filter-menu-buttons">
                                    <button type="button" class="filter-button reset" onclick="resetFilters()">
                                        Reset
                                    </button>
                                    <button type="submit" class="filter-button apply">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            {{-- FORM FILTER SELESAI DI SINI --}}

            <div class="products-area-wrapper tableView">
              <div class="products-header">
                <div class="product-cell cell-no">No</div>
                <div class="product-cell cell-perusahaan">Perusahaan</div>
                <div class="product-cell cell-jenis-barang">Jenis Barang</div>
                <div class="product-cell cell-no-asset">No Asset</div>
                <div class="product-cell cell-merek">Merek</div>
                <div class="product-cell cell-tgl-pengadaan">Tgl. Pengadaan</div>
                <div class="product-cell cell-serial-number">Serial Number</div>
                <div class="product-cell cell-aksi">Aksi</div>
              </div>

              {{-- Pastikan variabel $barangs di-pass dari controller --}}
              @if(isset($barangs) && $barangs->count() > 0)
                @foreach($barangs as $index => $barang)
                <div class="products-row">
                    {{-- Menyesuaikan nomor urut dengan paginasi --}}
                    <div class="product-cell cell-no">{{ $barangs->firstItem() + $index }}</div>
                    <div class="product-cell cell-perusahaan" title="{{ $barang->perusahaan }}">{{ $barang->perusahaan }}</div>
                    <div class="product-cell cell-jenis-barang" title="{{ $barang->jenis_barang }}">{{ $barang->jenis_barang }}</div>
                    <div class="product-cell cell-no-asset" title="{{ $barang->no_asset }}">{{ $barang->no_asset }}</div>
                    <div class="product-cell cell-merek" title="{{ $barang->merek }}">{{ $barang->merek }}</div>
                    {{-- Menggunakan format tanggal yang lebih umum dan Carbon untuk parsing --}}
                    <div class="product-cell cell-tgl-pengadaan">{{ \Carbon\Carbon::parse($barang->tgl_pengadaan)->format('d-m-Y') }}</div>
                    <div class="product-cell cell-serial-number" title="{{ $barang->serial_number }}">{{ $barang->serial_number }}</div>
                    <div class="product-cell cell-aksi">
                        <button class="action-btn-table update-btn" onclick="alert('Update untuk ID: {{ $barang->id }} belum diimplementasikan')">Update</button>
                        <button class="action-btn-table detail-btn" onclick="alert('Detail untuk ID: {{ $barang->id }} belum diimplementasikan')">Detail</button>
                    </div>
                </div>
                @endforeach
              @else
                <div class="products-row">
                    <div class="product-cell" style="text-align:center; flex-basis:100%; padding: 20px;">Tidak ada data aset ditemukan.</div>
                </div>
              @endif
            </div>

            {{-- Tampilkan Link Paginasi --}}
            @if (isset($barangs) && $barangs->hasPages())
                <div class="pagination-container" style="margin-top: 20px; display: flex; justify-content: center;">
                    {{-- appends(request()->query()) penting agar filter tetap aktif saat paginasi --}}
                    {{ $barangs->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            @endif

          </div>
          {{-- AKHIR KONTEN UTAMA APLIKASI --}}

        </div>
      </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // === MODAL TAMBAH ASET: Variabel dan Fungsi Dasar ===
            const addAssetModal = document.getElementById('addAssetModal');
            const openAddAssetModalButton = document.getElementById('openAddAssetModalButton'); // Menggunakan ID yang baru ditambahkan
            const closeAddAssetModalBtn = document.getElementById('closeAddAssetModalBtn');
            const cancelAddAssetModalBtn = document.getElementById('cancelAddAssetModalBtn');
            const addAssetForm = document.getElementById('addAssetForm');
            const submitAddAssetBtn = document.getElementById('submitAddAssetBtn');

            function openModalElement(modalElement, formToReset) {
                if (modalElement) {
                    modalElement.classList.add('show');
                    if (formToReset) {
                        formToReset.reset();
                    }
                    clearValidationErrors();
                }
            }

            function closeModalElement(modalElement) {
                if (modalElement) {
                    modalElement.classList.remove('show');
                }
            }

            function clearValidationErrors() {
                document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                document.querySelectorAll('.form-control.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            }

            function displayValidationErrors(errors) {
                clearValidationErrors();
                for (const field in errors) {
                    const errorElement = document.getElementById(`${field}_error`);
                    const inputElement = document.getElementById(field);
                    if (errorElement) {
                        errorElement.textContent = errors[field][0];
                    }
                    if (inputElement) {
                        inputElement.classList.add('is-invalid');
                    }
                }
            }

            // === MODAL TAMBAH ASET: Event Listeners ===
            if (openAddAssetModalButton && addAssetModal) {
                openAddAssetModalButton.addEventListener('click', () => {
                    openModalElement(addAssetModal, addAssetForm);
                });
            }

            if (closeAddAssetModalBtn && addAssetModal) {
                closeAddAssetModalBtn.addEventListener('click', () => closeModalElement(addAssetModal));
            }

            if (cancelAddAssetModalBtn && addAssetModal) {
                cancelAddAssetModalBtn.addEventListener('click', () => closeModalElement(addAssetModal));
            }

            if (addAssetModal) {
                window.addEventListener('click', (event) => {
                    if (event.target == addAssetModal) {
                        closeModalElement(addAssetModal);
                    }
                });
            }

            // === MODAL TAMBAH ASET: Submit Form AJAX ===
            if (addAssetForm && submitAddAssetBtn) {
                addAssetForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const formData = new FormData(addAssetForm);
                    const originalButtonText = submitAddAssetBtn.textContent;
                    submitAddAssetBtn.textContent = 'Menyimpan...';
                    submitAddAssetBtn.disabled = true;
                    clearValidationErrors();

                    fetch("{{ route('barang.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': formData.get('_token'), // CSRF token dari form
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errData => { throw { status: response.status, body: errData }; })
                                             .catch(() => { throw new Error(`HTTP error! status: ${response.status}`); });
                        }
                        return response.json().then(data => ({ status: response.status, body: data }));
                    })
                    .then(({ status, body }) => {
                        if (status === 201 && body.success) {
                            alert(body.success);
                            closeModalElement(addAssetModal);
                            // addAssetForm.reset(); // Sudah di-reset saat membuka modal
                            window.location.reload(); // Reload halaman untuk melihat data baru
                        } else if (body.errors) {
                            displayValidationErrors(body.errors);
                        } else {
                            alert(body.error || 'Terjadi kesalahan saat menyimpan data.');
                            console.error('Server error response:', body);
                        }
                    })
                    .catch(errorInfo => {
                        if (errorInfo && errorInfo.body && errorInfo.body.errors) {
                            displayValidationErrors(errorInfo.body.errors);
                        } else if (errorInfo && errorInfo.body && errorInfo.body.error) {
                            alert(errorInfo.body.error);
                            console.error('Server error caught in .catch:', errorInfo.body);
                        } else {
                            console.error('Error submitting form (catch block):', errorInfo);
                            alert('Terjadi kesalahan koneksi atau server. Silakan cek console browser (F12) untuk detail.');
                        }
                    })
                    .finally(() => {
                        submitAddAssetBtn.textContent = originalButtonText;
                        submitAddAssetBtn.disabled = false;
                    });
                });
            }


            // === FITUR DASHBOARD: Filter Dropdown ===
            const filterToggleButton = document.querySelector(".jsFilter"); // Tombol yang mentoggle filter menu
            if (filterToggleButton) {
                filterToggleButton.addEventListener("click", function () {
                    // `this` adalah tombol .jsFilter yang diklik
                    const filterButtonWrapper = this.closest('.filter-button-wrapper');
                    if (filterButtonWrapper) {
                        const filterMenu = filterButtonWrapper.querySelector(".filter-menu");
                        if (filterMenu) {
                            filterMenu.classList.toggle("active");
                        } else {
                            console.error("Elemen .filter-menu tidak ditemukan di dalam .filter-button-wrapper.");
                        }
                    } else {
                        console.error("Elemen .filter-button-wrapper tidak ditemukan sebagai parent dari tombol filter.");
                    }
                });
            } else {
                console.warn("Tombol dengan kelas .jsFilter tidak ditemukan.");
            }

            // === FITUR DASHBOARD: Theme Switch ===
            const modeSwitch = document.querySelector('.mode-switch');
            if (modeSwitch) {
                modeSwitch.addEventListener('click', function () {
                    document.documentElement.classList.toggle('light');
                    modeSwitch.classList.toggle('active');
                    // Update background dan color body jika diperlukan, tergantung variabel CSS Anda
                    document.body.style.backgroundColor = getComputedStyle(document.documentElement).getPropertyValue('--app-bg');
                    document.body.style.color = getComputedStyle(document.documentElement).getPropertyValue('--app-content-main-color');
                });
            }

            // === FITUR DASHBOARD: Burger Menu Sidebar ===
            const burgerMenuButton = document.getElementById('burger-menu');
            const sidebarElement = document.querySelector('.sidebar');
            if (burgerMenuButton && sidebarElement) {
                // Inisialisasi state tombol burger berdasarkan state sidebar saat halaman dimuat
                if (!sidebarElement.classList.contains('collapsed')) {
                    burgerMenuButton.classList.add('active');
                } else {
                    burgerMenuButton.classList.remove('active');
                }

                burgerMenuButton.addEventListener('click', () => {
                    sidebarElement.classList.toggle('collapsed');
                    burgerMenuButton.classList.toggle('active');
                });
            }

            // === FITUR DASHBOARD: Auto Submit Search Bar on Enter ===
            const searchBarInput = document.querySelector('.search-bar[name="search_no_asset"]');
            if (searchBarInput) {
                searchBarInput.addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault(); // Mencegah submit form default jika search bar ada di dalam form lain
                        const filterForm = document.getElementById('filterForm');
                        if (filterForm) {
                            filterForm.submit();
                        }
                    }
                });
            }

        }); // Akhir dari DOMContentLoaded

        // Fungsi resetFilters harus berada di global scope jika dipanggil dari onclick="resetFilters()" di HTML.
        // Atau, Anda bisa menghapus onclick dari HTML dan menambahkan event listener di dalam DOMContentLoaded.
        function resetFilters() {
            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                const filterPerusahaanSelect = document.getElementById('filter_perusahaan');
                const filterJenisBarangSelect = document.getElementById('filter_jenis_barang');
                const searchBar = filterForm.querySelector('.search-bar[name="search_no_asset"]');

                if(filterPerusahaanSelect) filterPerusahaanSelect.value = '';
                if(filterJenisBarangSelect) filterJenisBarangSelect.value = '';
                if(searchBar) searchBar.value = '';

                filterForm.submit(); // Submit form untuk menerapkan filter yang sudah direset
            }
        }
    </script>
</body>
</html>