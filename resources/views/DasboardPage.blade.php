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
            <div id="deviceInfoModal" class="modal-overlay">
                <div class="modal-content-wrapper device-entry-info-modal">
                    <div class="morph-modal-container">
                        <div class="morph-modal-title">
                            <span><i class="fas fa-info-circle"></i> Detail Perangkat</span>
                            <button id="closeDetailModalButton" class="btn-close" aria-label="Tutup modal">×</button>
                        </div>
                        <div class="morph-modal-body">
                            <div class="device-details-info-header">
                                <i id="modalDeviceImage" class="device-image fas"></i>
                                <div class="device-label">
                                    <span class="device-name" id="modalDeviceName">Nama Perangkat</span>
                                    <span class="device-online-status" id="modalDeviceType">Jenis Barang</span>
                                </div>
                            </div>
                            <div class="asset-detail-content">
                                <div class="info-section">
                                    <dl class="info-item">
                                        <dt>Perusahaan:</dt>
                                        <dd id="modalPerusahaan">_</dd>
                                    </dl>
                                    <dl class="info-item">
                                        <dt>No Asset:</dt>
                                        <dd id="modalNoAsset">_</dd>
                                    </dl>
                                    <dl class="info-item">
                                        <dt>Kuantitas:</dt>
                                        <dd id="modalKuantitas">_</dd>
                                    </dl>
                                    <dl class="info-item">
                                        <dt>Tgl. Pengadaan:</dt>
                                        <dd id="modalTglPengadaan">_</dd>
                                    </dl>
                                    <dl class="info-item">
                                        <dt>Serial Number:</dt>
                                        <dd id="modalSerialNumber">_</dd>
                                    </dl>
                                    <dl class="info-item">
                                        <dt>Lokasi:</dt>
                                        <dd id="modalLokasi">_</dd>
                                    </dl>
                                </div>
                                <hr class="separator">
                                <div class="info-section">
                                    <dl class="info-item">
                                        <dt>Pengguna</dt>
                                        <dd id="modalUser">_</dd>
                                    </dl>
                                    <dl class="info-item">
                                        <dt>Penyerahan:</dt>
                                        <dd id="modalTglPenyerahan">_</dd>
                                    </dl>
                                    <dl class="info-item">
                                        <dt>Pengembalian:</dt>
                                        <dd id="modalTglPengembalian">_</dd>
                                    </dl>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail" id="triggerUserHistoryModalButton">
                                            <i class="fas fa-user-circle"></i> Detail User
                                        </button>
                                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                                        <button class="btn-action btn-update">
                                            <i class="fas fa-edit"></i> Update Aset
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-section">
                                    <dl class="info-item">
                                        <dt>Status:</dt>
                                        <dd id="modalStatus">_</dd>
                                    </dl>
                                    <dl class="info-item">
                                        <dt>Keterangan:</dt>
                                        <dd id="modalKeterangan" class="keterangan-text">_</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="userHistoryModal" class="modal-overlay" style="display: none;">
                <div class="modal-content-wrapper device-entry-info-modal">
                    <div class="morph-modal-container">
                        <div class="morph-modal-title">
                            <span><i class="fas fa-history"></i> Riwayat Pengguna Aset</span>
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

                            <div class="user-history-table-wrapper">
                                <table class="user-history-table" id="userHistoryTableContainer">
                                    <thead id="userHistoryTableHeader">
                                    </thead>
                                    <tbody id="userHistoryTableBody">
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
                                <input type="hidden" id="serahTerimaTrackId" name="track_id">
                                <input type="hidden" id="serahTerimaAssetId" name="asset_id">
                                <input type="hidden" id="serahTerimaSerialNumber" name="serial_number">

                                <div class="info-section" style="margin-bottom: 1.5rem;">
                                    <dl class="info-item">
                                        <dt>Aset:</dt>
                                        <dd id="serahTerimaInfoNamaAset">_</dd>
                                    </dl>
                                    <dl class="info-item">
                                        <dt>Serial Number:</dt>
                                        <dd id="serahTerimaInfoSN">_</dd>
                                    </dl>
                                    <dl class="info-item">
                                        <dt>Perusahaan:</dt>
                                        <dd id="serahTerimaInfoPerusahaan">_</dd>
                                    </dl>
                                </div>

                                <div class="form-group" style="margin-bottom: 1rem;">
                                    <label for="serahTerimaTanggalAwal" style="display: block; margin-bottom: .5rem; font-weight: 500;">Tanggal Serah Terima (Otomatis)</label>
                                    <input type="date" id="serahTerimaTanggalAwal" name="tanggal_awal" class="form-control" readonly>
                                </div>

                                <div class="form-group" id="noAssetBaruPreviewGroup" style="display: none;">
                                    <label for="no_asset_baru_preview">No. Asset Baru (Otomatis)</label>

                                    <input type="text" name="no_asset_baru_preview" id="no_asset_baru_preview" class="form-control auto-asset-preview" readonly
                                        value="-- Pilih Perusahaan Tujuan --">

                                    <div id="noAssetBaruWarning" class="asset-preview-warning" style="display: none;">
                                        Nomor Aset ini akan digenerate ulang saat disimpan.
                                    </div>
                                </div>

                                <div class="form-group" id="perusahaanTujuanGroup" style="display: none; margin-bottom: 1rem;">
                                    <label for="serahTerimaPerusahaanTujuan">Pindahkan ke Perusahaan</label>
                                    <select id="serahTerimaPerusahaanTujuan" name="perusahaan_tujuan" class="form-control">
                                        <option value="">Pilih Perusahaan Tujuan</option>
                                        @foreach($perusahaanOptions as $perusahaan)
                                        <option value="{{ $perusahaan->id }}" data-singkatan="{{ $perusahaan->singkatan }}">{{ $perusahaan->nama_perusahaan }}</option>
                                        @endforeach
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
                                    <button type="button" class="btn btn-secondary" id="cancelSerahTerimaAsetModalBtn">Batal</button>
                                    <button type="submit" class="btn btn-primary" id="submitSerahTerimaAsetBtn">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="addAssetModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Aset Baru</h5>
                        <button type="button" class="close-button" id="closeAddAssetModalBtn">×</button>
                    </div>
                    <div class="modal-body">
                        <form id="addAssetForm">
                            @csrf

                            <div class="form-group">
                                <label for="perusahaan_id">Perusahaan</label>
                                <select name="perusahaan_id" id="perusahaan_id" class="form-control" required>
                                    <option value="">Pilih Perusahaan</option>
                                    @foreach($perusahaanOptions as $perusahaan)
                                    <option value="{{ $perusahaan->id }}" data-singkatan="{{ $perusahaan->singkatan }}">{{ $perusahaan->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="perusahaan_id_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="jenis_barang_id">Jenis Barang</label>
                                <select name="jenis_barang_id" id="jenis_barang_id" class="form-control" required>
                                    <option value="">Pilih Jenis Barang</option>
                                    @foreach($jenisBarangOptions as $jenis)
                                    <option value="{{ $jenis->id }}" data-singkatan="{{ $jenis->singkatan }}">{{ $jenis->nama_jenis }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="jenis_barang_id_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="merek">Merek</label>
                                <input type="text" name="merek" id="merek" class="form-control" required>
                                <div class="invalid-feedback" id="merek_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="kuantitas">Kuantitas</label>
                                <input type="number" name="kuantitas" id="kuantitas" class="form-control" required min="1" value="1">
                                <div class="invalid-feedback" id="kuantitas_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="tgl_pengadaan">Tanggal Pengadaan</label>
                                <div class="date-input-container">
                                    <input type="date" name="tgl_pengadaan" id="tgl_pengadaan" class="form-control" required>
                                </div>
                                <div class="invalid-feedback" id="tgl_pengadaan_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="serial_number">Serial Number</label>
                                <input type="text" name="serial_number" id="serial_number" class="form-control" required>
                                <div class="invalid-feedback" id="serial_number_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <input type="text" name="lokasi" id="lokasi" class="form-control">
                                <div class="invalid-feedback" id="lokasi_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="no_asset_preview">No. Asset (Otomatis)</label>
                                <input type="text" name="no_asset_preview" id="no_asset_preview" class="form-control auto-asset-preview" readonly
                                    value="-- Pilih Perusahaan, Jenis, dan Tanggal --">
                                <div id="noAssetWarning" class="asset-preview-warning" style="display: none;">
                                    Nomor Aset ini akan dibuat secara otomatis saat disimpan.
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelAddAssetModalBtn">Batal</button>
                        <button type="submit" class="btn btn-primary" form="addAssetForm" id="submitAddAssetBtn">Simpan Aset</button>
                    </div>
                </div>
            </div>

            <div id="editAssetModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Aset</h5>
                        <button type="button" class="close-button" id="closeEditAssetModalBtn">×</button>
                    </div>
                    <div class="modal-body">
                        <form id="editAssetForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="edit_asset_id" name="asset_id">

                            <div class="form-group">
                                <label for="edit_perusahaan_id">Perusahaan</label>
                                <select name="perusahaan_id" id="edit_perusahaan_id" class="form-control" required>
                                    <option value="">Pilih Perusahaan</option>
                                    @foreach($perusahaanOptions as $perusahaan)
                                    <option value="{{ $perusahaan->id }}">{{ $perusahaan->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="edit_perusahaan_id_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_jenis_barang_id">Jenis Barang</label>
                                <select name="jenis_barang_id" id="edit_jenis_barang_id" class="form-control" required>
                                    <option value="">Pilih Jenis Barang</option>
                                    @foreach($jenisBarangOptions as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="edit_jenis_barang_id_error"></div>
                            </div>

                            <div class="form-group">
                                @if(auth()->user()->isSuperAdmin())
                                    <label for="edit_no_asset">No. Asset <span style="font-weight:normal; color:var(--action-color-light);">(Bisa diubah oleh Super Admin)</span></label>
                                    <input type="text" name="no_asset" id="edit_no_asset" class="form-control">
                                    <div class="invalid-feedback" id="edit_no_asset_error"></div>
                                @else
                                    <label for="edit_no_asset">No. Asset (Tidak bisa diubah)</label>
                                    <input type="text" name="no_asset" id="edit_no_asset" class="form-control" readonly>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="edit_merek">Merek</label>
                                <input type="text" name="merek" id="edit_merek" class="form-control" required>
                                <div class="invalid-feedback" id="edit_merek_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_kuantitas">Kuantitas</label>
                                <input type="number" name="kuantitas" id="edit_kuantitas" class="form-control" required min="1">
                                <div class="invalid-feedback" id="edit_kuantitas_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_tgl_pengadaan">Tanggal Pengadaan</label>
                                <div class="date-input-container">
                                    <input type="date" name="tgl_pengadaan" id="edit_tgl_pengadaan" class="form-control" required>
                                </div>
                                <div class="invalid-feedback" id="edit_tgl_pengadaan_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_serial_number">Serial Number</label>
                                <input type="text" name="serial_number" id="edit_serial_number" class="form-control" required>
                                <div class="invalid-feedback" id="edit_serial_number_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="edit_lokasi">Lokasi</label>
                                <input type="text" name="lokasi" id="edit_lokasi" class="form-control">
                                <div class="invalid-feedback" id="edit_lokasi_error"></div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelEditAssetModalBtn">Batal</button>
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
        </body>
        </html>