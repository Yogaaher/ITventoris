        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            @vite(['resources/css/dashboard.css','resources/js/dashboard.js'])
            <title>Scuto Asset - Dashboard</title>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
            <link rel="icon" href="{{ asset('img/Scuto-logo.svg') }}" type="image/x-icon">
        </head>

        @php
        $pageData = [
        'isSuperAdmin' => auth()->user()->isSuperAdmin(),
        'urls' => [
        'nextSerial' => url('/aset/nomor-seri-berikutnya'),
        'export' => route('dashboard.export'),
        'storeBarang' => route('barang.store'),
        'serahTerimaStore' => route('aset.serahterima.store'),
        'searchRealtime' => route('dashboard.search.realtime'),
        'dashboardIndex' => route('dashboard.index')
        ]
        ];
        @endphp

        <body data-page-data='@json($pageData)'>
            <!-- Modal Detail Perangkat -->
            <div id="deviceInfoModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-header-content">
                            <i class="fas fa-info-circle modal-header-icon"></i>
                            <h2 class="modal-title">Detail Perangkat</h2>
                        </div>
                        <button type="button" id="closeDetailModalButton" class="close-button" title="Tutup">×</button>
                    </div>
                    <div class="modal-body">
                        <!-- Section 1: Detail Barang (Matching Detail Surat Serah Terima) -->
                        <div class="detail-section">
                            <h3>Detail Barang</h3>
                            <div class="detail-barang-box">
                                <div style="display: flex; align-items: center; gap: 2rem; width: 100%; border-bottom: 1px solid var(--table-border); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                                    <div class="detail-barang-icon">
                                        <i id="modalDeviceImage" class="device-image fas fa-laptop"></i>
                                    </div>
                                    <div class="detail-barang-info">
                                        <div id="modalDeviceName" class="header">Nama Perangkat</div>
                                        <div id="modalDeviceType" class="subheader">Jenis Barang</div>
                                    </div>
                                </div>
                                <dl class="detail-item">
                                    <dt>Perusahaan:</dt>
                                    <dd id="modalPerusahaan">-</dd>
                                </dl>
                                <dl class="detail-item">
                                    <dt>No. Asset:</dt>
                                    <dd id="modalNoAsset">-</dd>
                                </dl>
                                <dl class="detail-item">
                                    <dt>Tgl. Pengadaan:</dt>
                                    <dd id="modalTglPengadaan">-</dd>
                                </dl>
                                <dl class="detail-item">
                                    <dt>Serial Number:</dt>
                                    <dd id="modalSerialNumber">-</dd>
                                </dl>
                                <dl class="detail-item">
                                    <dt>Kuantitas:</dt>
                                    <dd id="modalKuantitas">-</dd>
                                </dl>
                                <dl class="detail-item">
                                    <dt>Lokasi:</dt>
                                    <dd id="modalLokasi">-</dd>
                                </dl>
                            </div>
                        </div>

                        <!-- Section 2: Pengguna Saat Ini -->
                        <div class="detail-section">
                            <h3>Pengguna Saat Ini</h3>
                            <dl class="detail-item">
                                <dt>Nama Pengguna:</dt>
                                <dd id="modalUser">-</dd>
                            </dl>
                            <dl class="detail-item">
                                <dt>Tgl. Penyerahan:</dt>
                                <dd id="modalTglPenyerahan">-</dd>
                            </dl>
                            <dl class="detail-item">
                                <dt>Tgl. Pengembalian:</dt>
                                <dd id="modalTglPengembalian">-</dd>
                            </dl>
                            <div class="action-buttons" style="display: flex; gap: 1.2rem; margin-top: 1.5rem; flex-wrap: wrap;">
                                <button type="button" class="btn btn-secondary" id="triggerUserHistoryModalButton">
                                    <i class="fas fa-history"></i> Riwayat Pengguna
                                </button>
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                                <button type="button" class="btn btn-primary btn-update">
                                    <i class="fas fa-exchange-alt"></i> Serah Terima / Update
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- Section 3: Status & Keterangan -->
                        <div class="detail-section">
                            <h3>Status & Keterangan</h3>
                            <dl class="detail-item">
                                <dt>Status:</dt>
                                <dd id="modalStatus">-</dd>
                            </dl>
                            <dl class="detail-item" style="align-items: flex-start;">
                                <dt>Keterangan:</dt>
                                <dd id="modalKeterangan" class="keterangan-text" style="white-space: pre-wrap;">-</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Riwayat Pengguna -->
            <div id="userHistoryModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-header-content">
                            <i class="fas fa-history modal-header-icon"></i>
                            <h2 class="modal-title">Riwayat Pengguna Aset</h2>
                        </div>
                        <button type="button" id="closeUserHistoryModalButton" class="close-button" title="Tutup">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="detail-section">
                            <h3>Informasi Perangkat</h3>
                            <dl class="detail-item">
                                <dt>Perangkat:</dt>
                                <dd id="historyModalDeviceName">-</dd>
                            </dl>
                            <dl class="detail-item">
                                <dt>Serial Number:</dt>
                                <dd id="historyModalSerialNumber">-</dd>
                            </dl>
                            <dl class="detail-item">
                                <dt>Perusahaan:</dt>
                                <dd id="historyModalCompany">-</dd>
                            </dl>
                        </div>

                        <div class="detail-section">
                            <h3>Daftar Riwayat Pengguna</h3>
                            <div class="user-history-table-wrapper">
                                <table class="user-history-table" id="userHistoryTableContainer">
                                    <thead id="userHistoryTableHeader">
                                    </thead>
                                    <tbody id="userHistoryTableBody">
                                        <tr>
                                            <td colspan="5" style="padding: 15px; text-align: center; color: #94a3b8;">Memuat riwayat...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-button" id="closeUserHistoryModalFooterBtn">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Serah Terima Aset -->
            <div id="serahTerimaAsetModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-header-content">
                            <i class="fas fa-exchange-alt modal-header-icon"></i>
                            <h2 class="modal-title">Serah Terima Aset</h2>
                        </div>
                        <button type="button" id="closeSerahTerimaAsetModalButton" class="close-button" title="Tutup">×</button>
                    </div>
                    <div class="modal-body">
                        <form id="serahTerimaAsetForm" novalidate>
                            @csrf
                            <input type="hidden" id="serahTerimaTrackId" name="track_id">
                            <input type="hidden" id="serahTerimaAssetId" name="asset_id">
                            <input type="hidden" id="serahTerimaSerialNumber" name="serial_number">

                            <div class="detail-section">
                                <h3>Informasi Aset Saat Ini</h3>
                                <dl class="detail-item">
                                    <dt>Aset:</dt>
                                    <dd id="serahTerimaInfoNamaAset">-</dd>
                                </dl>
                                <dl class="detail-item">
                                    <dt>Serial Number:</dt>
                                    <dd id="serahTerimaInfoSN">-</dd>
                                </dl>
                                <dl class="detail-item">
                                    <dt>Perusahaan:</dt>
                                    <dd id="serahTerimaInfoPerusahaan">-</dd>
                                </dl>
                            </div>

                            <div class="form-section">
                                <h3>Perubahan Status & Pengguna</h3>
                                <div class="form-group">
                                    <label for="serahTerimaTanggalAwal">Tanggal Serah Terima (Otomatis)</label>
                                    <input type="date" id="serahTerimaTanggalAwal" name="tanggal_awal" readonly>
                                </div>

                                <div class="form-group" id="noAssetBaruPreviewGroup" style="display: none;">
                                    <label for="no_asset_baru_preview">No. Asset Baru (Otomatis)</label>
                                    <input type="text" name="no_asset_baru_preview" id="no_asset_baru_preview" class="auto-asset-preview" readonly
                                        value="-- Pilih Perusahaan Tujuan --">
                                    <div id="noAssetBaruWarning" class="asset-preview-warning" style="display: none;">
                                        Nomor Aset ini akan digenerate ulang saat disimpan.
                                    </div>
                                </div>

                                <div class="form-group" id="perusahaanTujuanGroup" style="display: none;">
                                    <label for="serahTerimaPerusahaanTujuan">Pindahkan ke Perusahaan</label>
                                    <select id="serahTerimaPerusahaanTujuan" name="perusahaan_tujuan">
                                        <option value="">Pilih Perusahaan Tujuan</option>
                                        @foreach($perusahaanOptions as $perusahaan)
                                        <option value="{{ $perusahaan->id }}" data-singkatan="{{ $perusahaan->singkatan }}">{{ $perusahaan->nama_perusahaan }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="perusahaan_tujuan_serah_error"></div>
                                </div>

                                <div class="form-group-grid">
                                    <div class="form-group">
                                        <label for="serahTerimaUser">Nama Pengguna Baru</label>
                                        <input type="text" id="serahTerimaUser" name="username" required placeholder="Nama Pengguna Baru">
                                        <div class="invalid-feedback" id="username_serah_error"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="serahTerimaStatus">Status Aset</label>
                                        <select id="serahTerimaStatus" name="status" required>
                                            <option value="">Pilih Status</option>
                                            <option value="digunakan">Digunakan</option>
                                            <option value="diperbaiki">Diperbaiki</option>
                                            <option value="dipindah">Dipindah</option>
                                            <option value="non aktif">Non Aktif</option>
                                            <option value="tersedia">Tersedia</option>
                                        </select>
                                        <div class="invalid-feedback" id="status_serah_error"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="serahTerimaKeterangan">Keterangan</label>
                                    <textarea id="serahTerimaKeterangan" name="keterangan" rows="3" required placeholder="Tambahkan catatan atau keterangan..."></textarea>
                                    <div class="invalid-feedback" id="keterangan_serah_error"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-button" id="cancelSerahTerimaAsetModalBtn">Batal</button>
                        <button type="submit" class="btn btn-primary" form="serahTerimaAsetForm" id="submitSerahTerimaAsetBtn">Simpan Perubahan</button>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Aset -->
            <div id="addAssetModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-header-content">
                            <i class="fas fa-plus-square modal-header-icon"></i>
                            <h2 class="modal-title" id="addAssetModalTitle">Tambah Aset Baru</h2>
                        </div>
                        <button type="button" class="close-button" id="closeAddAssetModalBtn" title="Tutup">×</button>
                    </div>
                    <div class="modal-body">
                        <form id="addAssetForm" novalidate>
                            @csrf

                            <!-- Section 1: Perusahaan & Kategori -->
                            <div class="form-section">
                                <h3>Informasi Perusahaan & Kategori</h3>
                                <div class="form-group-grid">
                                    <div class="form-group">
                                        <label for="perusahaan_id">Perusahaan</label>
                                        <select name="perusahaan_id" id="perusahaan_id" required>
                                            <option value="">Pilih Perusahaan</option>
                                            @foreach($perusahaanOptions as $perusahaan)
                                            <option value="{{ $perusahaan->id }}" data-singkatan="{{ $perusahaan->singkatan }}">{{ $perusahaan->nama_perusahaan }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="perusahaan_id_error"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="jenis_barang_id">Jenis Barang</label>
                                        <select name="jenis_barang_id" id="jenis_barang_id" required>
                                            <option value="">Pilih Jenis Barang</option>
                                            @foreach($jenisBarangOptions as $jenis)
                                            <option value="{{ $jenis->id }}" data-singkatan="{{ $jenis->singkatan }}">{{ $jenis->nama_jenis }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="jenis_barang_id_error"></div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 1.4rem;">
                                    <label for="no_asset_preview">No. Asset (Otomatis)</label>
                                    <input type="text" name="no_asset_preview" id="no_asset_preview" class="auto-asset-preview" readonly
                                        value="-- Pilih Perusahaan, Jenis, dan Tanggal --">
                                    <div id="noAssetWarning" class="asset-preview-warning" style="display: none;">
                                        Nomor Aset ini akan dibuat secara otomatis saat disimpan.
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Spesifikasi & Detail -->
                            <div class="form-section">
                                <h3>Detail & Spesifikasi Barang</h3>
                                <div class="form-group">
                                    <label for="merek">Merek / Nama Barang</label>
                                    <input type="text" name="merek" id="merek" placeholder="Contoh: Lenovo ThinkPad" required>
                                    <div class="invalid-feedback" id="merek_error"></div>
                                </div>
                                <div class="form-group-grid">
                                    <div class="form-group">
                                        <label for="kuantitas">Kuantitas</label>
                                        <input type="number" name="kuantitas" id="kuantitas" required min="1" value="1">
                                        <div class="invalid-feedback" id="kuantitas_error"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="tgl_pengadaan">Tanggal Pengadaan</label>
                                        <div class="date-input-container">
                                            <input type="date" name="tgl_pengadaan" id="tgl_pengadaan" required>
                                        </div>
                                        <div class="invalid-feedback" id="tgl_pengadaan_error"></div>
                                    </div>
                                </div>
                                <div class="form-group-grid">
                                    <div class="form-group">
                                        <label for="serial_number">Serial Number</label>
                                        <input type="text" name="serial_number" id="serial_number" placeholder="Serial number unik" required>
                                        <div class="invalid-feedback" id="serial_number_error"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="lokasi">Lokasi</label>
                                        <input type="text" name="lokasi" id="lokasi" placeholder="Contoh: Ruang IT Lt. 2">
                                        <div class="invalid-feedback" id="lokasi_error"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-button" id="cancelAddAssetModalBtn">Batal</button>
                        <button type="submit" class="btn btn-primary" form="addAssetForm" id="submitAddAssetBtn">Simpan Aset</button>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Aset -->
            <div id="editAssetModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-header-content">
                            <i class="fas fa-edit modal-header-icon"></i>
                            <h2 class="modal-title" id="editAssetModalTitle">Edit Aset</h2>
                        </div>
                        <button type="button" class="close-button" id="closeEditAssetModalBtn" title="Tutup">×</button>
                    </div>
                    <div class="modal-body">
                        <form id="editAssetForm" novalidate>
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="edit_asset_id" name="asset_id">

                            <!-- Section 1: Perusahaan & Kategori -->
                            <div class="form-section">
                                <h3>Informasi Perusahaan & Kategori</h3>
                                <div class="form-group-grid">
                                    <div class="form-group">
                                        <label for="edit_perusahaan_id">Perusahaan</label>
                                        <select name="perusahaan_id" id="edit_perusahaan_id" required>
                                            <option value="">Pilih Perusahaan</option>
                                            @foreach($perusahaanOptions as $perusahaan)
                                            <option value="{{ $perusahaan->id }}">{{ $perusahaan->nama_perusahaan }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="edit_perusahaan_id_error"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_jenis_barang_id">Jenis Barang</label>
                                        <select name="jenis_barang_id" id="edit_jenis_barang_id" required>
                                            <option value="">Pilih Jenis Barang</option>
                                            @foreach($jenisBarangOptions as $jenis)
                                            <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="edit_jenis_barang_id_error"></div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 1.4rem;">
                                    @if(auth()->user()->isSuperAdmin())
                                        <label for="edit_no_asset">No. Asset <span style="font-weight:normal; color:var(--action-color-hover);">(Super Admin)</span></label>
                                        <input type="text" name="no_asset" id="edit_no_asset">
                                        <div class="invalid-feedback" id="edit_no_asset_error"></div>
                                    @else
                                        <label for="edit_no_asset">No. Asset (Terkunci)</label>
                                        <input type="text" name="no_asset" id="edit_no_asset" readonly>
                                    @endif
                                </div>
                            </div>

                            <!-- Section 2: Detail & Spesifikasi Barang -->
                            <div class="form-section">
                                <h3>Detail & Spesifikasi Barang</h3>
                                <div class="form-group">
                                    <label for="edit_merek">Merek / Nama Barang</label>
                                    <input type="text" name="merek" id="edit_merek" required placeholder="Contoh: Lenovo ThinkPad">
                                    <div class="invalid-feedback" id="edit_merek_error"></div>
                                </div>
                                <div class="form-group-grid">
                                    <div class="form-group">
                                        <label for="edit_kuantitas">Kuantitas</label>
                                        <input type="number" name="kuantitas" id="edit_kuantitas" required min="1">
                                        <div class="invalid-feedback" id="edit_kuantitas_error"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_tgl_pengadaan">Tanggal Pengadaan</label>
                                        <div class="date-input-container">
                                            <input type="date" name="tgl_pengadaan" id="edit_tgl_pengadaan" required>
                                        </div>
                                        <div class="invalid-feedback" id="edit_tgl_pengadaan_error"></div>
                                    </div>
                                </div>
                                <div class="form-group-grid">
                                    <div class="form-group">
                                        <label for="edit_serial_number">Serial Number</label>
                                        <input type="text" name="serial_number" id="edit_serial_number" required placeholder="Serial number unik">
                                        <div class="invalid-feedback" id="edit_serial_number_error"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_lokasi">Lokasi</label>
                                        <input type="text" name="lokasi" id="edit_lokasi" placeholder="Contoh: Ruang IT Lt. 2">
                                        <div class="invalid-feedback" id="edit_lokasi_error"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-button" id="cancelEditAssetModalBtn">Batal</button>
                        <button type="submit" class="btn btn-primary" form="editAssetForm" id="submitEditAssetBtn">Simpan Perubahan</button>
                    </div>
                </div>
            </div>

            <div id="dashboard-page">
                <div class="app-container">

                    @include('partials.sidebar')

                    <div class="app-content">
                        <div class="app-content-header">
                            <button id="mobile-burger-menu" class="burger-button" title="Buka Menu">
                                <span></span>
                                <span></span>
                                <span></span>
                            </button>
                            <h1 class="app-content-headerText">Data Aset</h1>
                            <div class="app-content-header-actions-right">
                                <button class="mode-switch action-button" title="Switch Theme">
                                    <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="20" height="20" viewBox="0 0 24 24">
                                        <defs></defs>
                                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar?');" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="app-content-headerButton" style="background-color: #e74c3c; margin-left: 8px;">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="inventory-summary-container">
                            @foreach($inventorySummary as $namaJenis => $data)
                            @php
                            $slug = Str::slug($namaJenis);
                            @endphp
                            <div class="summary-box" data-type="{{ $slug }}">
                                <div class="summary-box-icon">
                                    <i class="{{ $data->icon }}"></i>
                                </div>
                                <div class="summary-box-type">{{ $namaJenis }}</div>
                                <div class="summary-box-count" id="summary-count-{{ $slug }}">{{ $data->count }}</div>
                            </div>
                            @endforeach
                        </div>

                        <form action="{{ route('dashboard.index') }}" method="GET" id="filterForm">
                            <div class="app-content-actions">

                                <div class="search-bar-container">
                                    <input class="search-bar"
                                        placeholder="Cari No Asset, Serial, Merek atau Lokasi..."
                                        type="text"
                                        id="mainSearchInput"
                                        autocomplete="off">
                                    <button type="button" class="clear-search-btn" id="clearMainSearchBtn" title="Hapus pencarian">
                                        ×
                                    </button>
                                </div>

                                <div class="app-content-actions-buttons">
                                    <button type="button" id="exportExcelBtn" class="action-button excel-btn">
                                        <i class="fas fa-file-excel"></i>
                                        <span class="action-button-text">Export</span>
                                    </button>
                                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                                    <button type="button" id="openAddAssetModalButton" class="action-button add-asset-btn">
                                        <i class="fas fa-plus-circle"></i>
                                        <span class="action-button-text">Tambah Asset</span>
                                    </button>
                                    @endif
                                    <div class="filter-button-wrapper">
                                        <button type="button" class="action-button filter jsFilter" title="Filter">
                                            <span class="action-button-text">Filter</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter">
                                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                                            </svg>
                                        </button>
                                        <div class="filter-menu">
                                            <label for="filter_perusahaan">Perusahaan</label>
                                            <select name="filter_perusahaan" id="filter_perusahaan">
                                                <option value="">Semua Perusahaan</option>
                                                @foreach($perusahaanOptions as $perusahaan)
                                                <option value="{{ $perusahaan->id }}" {{ (isset($filterPerusahaan) && $filterPerusahaan == $perusahaan->id) ? 'selected' : '' }}>
                                                    {{ $perusahaan->nama_perusahaan }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <label for="filter_status">Status Aset</label>
                                            <select name="filter_status" id="filter_status">
                                                <option value="">Semua Status</option>
                                                <option value="tersedia" {{ (isset($filterStatus) && strtolower($filterStatus) == 'tersedia') ? 'selected' : '' }}>Tersedia</option>
                                                <option value="digunakan" {{ (isset($filterStatus) && strtolower($filterStatus) == 'digunakan') ? 'selected' : '' }}>Digunakan</option>
                                                <option value="diperbaiki" {{ (isset($filterStatus) && strtolower($filterStatus) == 'diperbaiki') ? 'selected' : '' }}>Diperbaiki</option>
                                                <option value="dipindah" {{ (isset($filterStatus) && strtolower($filterStatus) == 'dipindah') ? 'selected' : '' }}>Dipindah</option>
                                                <option value="non aktif" {{ (isset($filterStatus) && strtolower($filterStatus) == 'non aktif') ? 'selected' : '' }}>Non Aktif</option>
                                            </select>
                                            <label for="filter_jenis_barang_container">Jenis Barang</label>
                                            <div id="filter_jenis_barang_container" class="dropdown-checkbox-container">
                                                <div class="dropdown-checkbox-display" tabindex="0">
                                                    <span>Semua Jenis Barang</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                                <div class="dropdown-checkbox-options">
                                                    @foreach($jenisBarangOptions as $jenis)
                                                    <label class="dropdown-checkbox-option">
                                                        <input type="checkbox" name="filter_jenis_barang[]" value="{{ $jenis->id }}">
                                                        <span>{{ $jenis->nama_jenis }}</span>
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>
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

                        <div class="products-area-wrapper tableView" id="productTableArea">
                            <div class="products-header">
                                <div class="product-cell cell-no sortable-header" data-sort-by="no">No</div>
                                <div class="product-cell cell-perusahaan sortable-header" data-sort-by="perusahaan">Perusahaan</div>
                                <div class="product-cell cell-jenis-barang sortable-header" data-sort-by="jenis_barang">Jenis Barang</div>
                                <div class="product-cell cell-no-asset sortable-header" data-sort-by="no_asset">No Asset</div>
                                <div class="product-cell cell-merek sortable-header" data-sort-by="merek">Merek</div>
                                <div class="product-cell cell-tgl-pengadaan sortable-header" data-sort-by="tgl_pengadaan" data-sort-type="date">Tgl. Pengadaan</div>
                                <div class="product-cell cell-serial-number sortable-header" data-sort-by="serial_number">Serial Number</div>
                                <div class="product-cell cell-lokasi sortable-header" data-sort-by="lokasi">Lokasi</div>
                                <div class="product-cell cell-aksi">Aksi</div>
                            </div>

                            <div id="productTableRowsContainer">
                                @if(isset($barangs) && $barangs->count() > 0)
                                @foreach($barangs as $index => $barang)
                                <div class="products-row" data-id="{{ $barang->id }}">
                                    <div class="product-cell cell-no">{{ $barangs->firstItem() + $index }}</div>
                                    <div class="product-cell cell-perusahaan" data-label="Perusahaan">{{ $barang->perusahaan->nama_perusahaan ?? 'N/A' }}</div>
                                    <div class="product-cell cell-jenis-barang" data-label="Jenis Barang">{{ $barang->jenisBarang->nama_jenis ?? 'N/A' }}</div>
                                    <div class="product-cell cell-no-asset" data-label="No Asset">{{ $barang->no_asset }}</div>
                                    <div class="product-cell cell-merek" data-label="Merek">{{ $barang->merek }}</div>
                                    <div class="product-cell cell-tgl-pengadaan" data-label="Tgl. Pengadaan">{{ \Carbon\Carbon::parse($barang->tgl_pengadaan)->format('d-m-Y') }}</div>
                                    <div class="product-cell cell-serial-number" data-label="Serial Number">{{ $barang->serial_number }}</div>
                                    <div class="product-cell cell-lokasi" data-label="Lokasi">{{ $barang->lokasi ?? '-' }}</div>
                                    <div class="product-cell cell-aksi">
                                        <button class="action-btn-table detail-btn-table-js" data-id="{{ $barang->id }}" title="Detail Aset">
                                            <i class="fas fa-info-circle"></i>
                                            <span>Detail</span>
                                        </button>
                                        @if(auth()->user()->isSuperAdmin())
                                        <button class="action-btn-table edit-btn-asset" data-id="{{ $barang->id }}" title="Edit Aset">
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
                                        </button>
                                        <button class="action-btn-table remove-btn-asset" data-id="{{ $barang->id }}" title="Hapus Aset">
                                            <i class="fas fa-trash-alt"></i>
                                            <span>Hapus</span>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="products-row">
                                    <div class="product-cell" style="text-align:center; flex-basis:100%;">Tidak ada data aset ditemukan.</div>
                                </div>
                                @endif
                            </div>

                            <div class="table-footer-controls" id="pagination-controls-container">
                                <div class="footer-section footer-left">
                                    <div class="rows-per-page-wrapper">
                                        <label for="rows-per-page-select">Baris Halaman:</label>
                                        <select id="rows-per-page-select">
                                            <option value="10" selected>10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="40">40</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="footer-section footer-center" id="pagination-buttons-container">
                                </div>

                                <div class="footer-section footer-right">
                                    <div class="pagination-info" id="pagination-info-text">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>