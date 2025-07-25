            <!DOCTYPE html>
            <html lang="id">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <title>Scuto Asset - Serah Terima</title>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
                <link rel="icon" href="{{ asset('img/Scuto-logo.svg') }}" type="image/x-icon">
                @vite(['resources/css/serah_terima.css','resources/js/serah_terima.js'])
            </head>

            @php
            $pageData = [
                'isSuperAdmin' => auth()->user()->isSuperAdmin(),
                'isAdmin' => auth()->user()->isAdmin(),
                'csrfToken' => csrf_token(),
                'urls' => [
                    'serahTerima' => [
                        'fetch' => route('surat.search'),
                        'delete' => url('/surat/'),
                        'detail' => url('/surat/detail/'),
                        'edit' => url('/surat/'),
                        'store' => route('surat.store'),
                        'getProspectiveNomor' => route('surat.getProspectiveNomor'),
                        'findBarang' => route('surat.find-barang'),
                        'download' => url('/surat/download/'),
                    ],
                    'mutasi' => [
                        'fetch' => route('mutasi.data'),
                        'delete' => url('/mutasi/'),
                        'detail' => url('/mutasi/'),
                    ],
                ],
                'initialData' => $serahTerima->toArray(),
            ];
            @endphp

            <body data-page-data='@json($pageData)'>
                <div id="dashboard-page">
                    <div class="app-container">

                          @include('partials.sidebar')

                        <div class="app-content">
                            <div class="app-content-header">
                                <button id="mobile-burger-menu" class="burger-button" title="Buka Menu">
                                    <span></span><span></span><span></span>
                                </button>
                                <h1 class="app-content-headerText">Serah Terima & Mutasi</h1>
                                <div class="app-content-header-actions-right">
                                    <button class="mode-switch action-button" title="Switch Theme">
                                        <svg class="moon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                                            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Yakin ingin keluar?');" style="margin-left:8px;">
                                        @csrf
                                        <button type="submit" class="app-content-headerButton" style="background-color: #e74c3c;">Logout</button>
                                    </form>
                                </div>
                            </div>

                            <ul class="nav-tabs" style="list-style:none; padding-left:0; display:flex; gap:4px; border-bottom: 2px solid var(--table-border); margin-bottom:20px;">
                                <li>
                                    <button class="nav-link active" data-tab="serahTerima" style="display:flex; align-items:center; gap:8px; padding:10px 20px; cursor:pointer; border:none; background:transparent; color:var(--app-content-main-color); opacity:1; font-weight:500; font-size:1.6rem; border-bottom:3px solid var(--action-color);">
                                        <i class="fas fa-file-signature"></i> <span>Serah Terima</span>
                                    </button>
                                </li>
                                <li>
                                    <button class="nav-link" data-tab="mutasi" style="display:flex; align-items:center; gap:8px; padding:10px 20px; cursor:pointer; border:none; background:transparent; color:var(--app-content-main-color); opacity:0.7; font-weight:500; font-size:1.6rem; border-bottom:3px solid transparent;">
                                        <i class="fas fa-random"></i> <span>Mutasi Barang</span>
                                    </button>
                                </li>
                            </ul>

                            <div class="app-content-actions">
                                <div class="search-bar-container">
                                    <input class="search-bar" id="searchInput" placeholder="Cari data serah terima..." type="text">
                                     <button type="button" class="clear-search-btn" id="clearSearchBtn" style="display: none;" title="Hapus pencarian">
                                        ×
                                    </button>
                                </div>
                                <div class="app-content-actions-buttons">
                                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                    <button id="openAddSuratModalButton" class="action-button add-surat-btn">
                                        <i class="fas fa-plus-circle"></i><span id="addButtonText">Tambah Surat</span>
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <div class="tab-content">
                                <div class="tab-pane active" id="serahTerima-tab-pane">
                                    <div class="products-area-wrapper tableView">
                                        <div class="products-header">
                                            <div class="product-cell cell-no">No</div>
                                            <div class="product-cell" style="flex:1.5">No. Surat</div>
                                            <div class="product-cell" style="flex:1.5">Penerima</div>
                                            <div class="product-cell" style="flex:1.5">Pemberi</div>
                                            <div class="product-cell" style="flex:2">Merek</div>
                                            <div class="product-cell" style="flex:1.5">No. Asset</div>
                                            <div class="product-cell" style="flex:2">Serial Number</div>
                                            <div class="product-cell cell-aksi">Aksi</div>
                                        </div>
                                        <div id="serahTerimaTableRowsContainer"></div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="mutasi-tab-pane" style="display:none;">
                                    <div class="products-area-wrapper tableView">
                                        <div class="products-header">
                                            <div class="product-cell cell-no">No</div>
                                            <div class="product-cell" style="flex:2">Serial Number</div>
                                            <div class="product-cell" style="flex:2">Merek Barang</div>
                                            <div class="product-cell" style="flex:1.5">No Asset Lama</div>
                                            <div class="product-cell" style="flex:1.5">No Asset Baru</div>
                                            <div class="product-cell" style="flex:1.5">Tanggal Mutasi</div>
                                            <div class="product-cell cell-aksi">Aksi</div>
                                        </div>
                                        <div id="mutasiTableRowsContainer"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-footer-controls">
                                <div class="footer-section">
                                    <div class="rows-per-page-wrapper">
                                        <label for="rows-per-page-select">Baris:</label>
                                        <select id="rows-per-page-select">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="footer-section">
                                    <div class="pagination-buttons" id="paginationButtonsContainer"></div>
                                </div>
                                <div class="footer-section">
                                    <div class="pagination-info" id="paginationInfoText"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="suratModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-header-content">
                                <i id="suratModalIcon" class="fas fa-plus-square modal-header-icon"></i>
                                <h2 class="modal-title" id="suratModalTitle">Tambah Surat Serah Terima</h2>
                            </div>
                            <button type="button" class="close-button" title="Tutup">×</button>
                        </div>
                        <div class="modal-body">
                            <form id="suratForm" novalidate>
                                @csrf
                                <input type="hidden" id="surat_id" name="surat_id">
                                <input type="hidden" name="barang_id" id="barang_id">

                                <div class="form-section">
                                    <h3>Data Aset</h3>
                                    <div class="form-group">
                                        <label for="search_barang">Cari Aset berdasarkan Serial Number atau No. Asset</label>
                                        <input type="text" id="search_barang" name="search_barang" required placeholder="Masukan atau scan Serial Number/No. Asset">
                                        <div class="invalid-feedback" id="barang_id_error"></div>
                                        <div id="barang-info-box"></div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h3>Data Penerima (Pihak Pertama)</h3>
                                    <div class="form-group-grid">
                                        <div class="form-group">
                                            <label for="nama_penerima">Nama Penerima</label>
                                            <input type="text" id="nama_penerima" name="nama_penerima" required placeholder="Masukan Nama Penerima">
                                            <div class="invalid-feedback" id="nama_penerima_error"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="nik_penerima">NIK Penerima</label>
                                            <input type="text" id="nik_penerima" name="nik_penerima" required placeholder="Masukan NIK Penerima">
                                            <div class="invalid-feedback" id="nik_penerima_error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jabatan_penerima">Jabatan Penerima</label>
                                        <input type="text" id="jabatan_penerima" name="jabatan_penerima" required placeholder="Masukan Jabatan Penerima">
                                        <div class="invalid-feedback" id="jabatan_penerima_error"></div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h3>Data Pemberi (Pihak Kedua)</h3>
                                    <div class="form-group-grid">
                                        <div class="form-group">
                                            <label for="nama_pemberi">Nama Pemberi</label>
                                            <input type="text" id="nama_pemberi" name="nama_pemberi" required placeholder="Masukan Nama Pemberi">
                                            <div class="invalid-feedback" id="nama_pemberi_error"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="nik_pemberi">NIK Pemberi</label>
                                            <input type="text" id="nik_pemberi" name="nik_pemberi" required placeholder="Masukan NIK Pemberi">
                                            <div class="invalid-feedback" id="nik_pemberi_error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jabatan_pemberi">Jabatan Pemberi</label>
                                        <input type="text" id="jabatan_pemberi" name="jabatan_pemberi" required placeholder="Masukan Jabatan Pemberi">
                                        <div class="invalid-feedback" id="jabatan_pemberi_error"></div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h3>Informasi Tambahan</h3>
                                    <div class="form-group">
                                        <label for="no_surat_auto">Nomor Surat</label>
                                        <input type="text" id="no_surat_auto" name="no_surat_auto" placeholder="Akan digenerate otomatis" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="penanggung_jawab">Penanggung Jawab</label>
                                        <input type="text" id="penanggung_jawab" name="penanggung_jawab" required placeholder="Masukan Nama Penanggung Jawab">
                                        <div class="invalid-feedback" id="penanggung_jawab_error"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea id="keterangan" name="keterangan" rows="3" placeholder="Masukan Keterangan"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary close-button">Batal</button>
                            <button type="submit" form="suratForm" class="btn btn-primary" id="submitSuratBtn">Simpan</button>
                        </div>
                    </div>
                </div>

                <div id="detailMutasiModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-header-content">
                            <i class="fas fa-random modal-header-icon"></i>
                            <h2 class="modal-title">Detail Mutasi Barang</h2>
                        </div>
                        <button type="button" class="close-button" title="Tutup">×</button>
                    </div>
                    <div class="modal-body">

                        <div class="detail-section">
                            <h3>Detail Barang</h3>
                            <div class="detail-barang-box" style="margin-bottom: 1.5rem;">
                                <div id="detailMutasiIcon" class="detail-barang-icon">
                                </div>
                                <div class="detail-barang-info">
                                    <div id="detailMutasiMerek" class="header">Memuat...</div>
                                    <div id="detailMutasiJenis" class="subheader">Memuat...</div>
                                </div>
                            </div>
                            <dl class="detail-item">
                                <dt>Serial Number:</dt>
                                <dd id="detailMutasiSN"></dd>
                            </dl>
                            <dl class="detail-item">
                                <dt>Tanggal Mutasi:</dt>
                                <dd id="detailMutasiTanggal"></dd>
                            </dl>
                        </div>

                        <div class="detail-section">
                            <h3>Perusahaan Lama</h3>
                            <dl class="detail-item">
                                <dt>No Asset:</dt>
                                <dd id="detailMutasiAssetLama"></dd>
                            </dl>
                            <dl class="detail-item">
                                <dt>Perusahaan:</dt>
                                <dd id="detailMutasiPerusahaanLama"></dd>
                            </dl>
                            <dl class="detail-item">
                                <dt>Pengguna:</dt>
                                <dd id="detailMutasiPenggunaLama"></dd>
                            </dl>
                        </div>      

                        <div class="detail-section">
                            <h3>Perusaan Baru</h3>
                            <dl class="detail-item">
                                <dt>No Asset:</dt>
                                <dd id="detailMutasiAssetBaru"></dd>
                            </dl>
                            <dl class="detail-item">
                                <dt>Perusahaan:</dt>
                                <dd id="detailMutasiPerusahaanBaru"></dd>
                            </dl>
                            <dl class="detail-item">
                                <dt>Pengguna:</dt>
                                <dd id="detailMutasiPenggunaBaru"></dd>
                            </dl>
                        </div>

                    </div>
                </div>
            </div>

                <div id="detailSuratModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-header-content">
                                <i class="fas fa-info-circle modal-header-icon"></i>
                                <h2 class="modal-title">Detail Serah Terima</h2>
                            </div>
                            <button type="button" class="close-button" title="Tutup">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="detail-section">
                                <h3>Data Penerima</h3>
                                <dl class="detail-item">
                                    <dt>Nama:</dt>
                                    <dd id="detailNamaPenerima"></dd>
                                </dl>
                                <dl class="detail-item">
                                    <dt>NIK:</dt>
                                    <dd id="detailNikPenerima"></dd>
                                </dl>
                                <dl class="detail-item">
                                    <dt>Jabatan:</dt>
                                    <dd id="detailJabatanPenerima"></dd>
                                </dl>
                            </div>
                            <div class="detail-section">
                                <h3>Data Pemberi</h3>
                                <dl class="detail-item">
                                    <dt>Nama:</dt>
                                    <dd id="detailNamaPemberi"></dd>
                                </dl>
                                <dl class="detail-item">
                                    <dt>NIK:</dt>
                                    <dd id="detailNikPemberi"></dd>
                                </dl>
                                <dl class="detail-item">
                                    <dt>Jabatan:</dt>
                                    <dd id="detailJabatanPemberi"></dd>
                                </dl>
                            </div>
                            <div class="detail-section">
                                <h3>Informasi Lainnya</h3>
                                <dl class="detail-item">
                                    <dt>No. Surat:</dt>
                                    <dd id="detailNoSurat"></dd>
                                </dl>
                                <dl class="detail-item">
                                    <dt>Penanggung Jawab:</dt>
                                    <dd id="detailPenanggungJawab"></dd>
                                </dl>
                                <dl class="detail-item" style="align-items: flex-start;">
                                    <dt>Keterangan:</dt>
                                    <dd id="detailKeterangan" style="white-space: pre-wrap;"></dd>
                                </dl>
                            </div>
                            <div class="detail-section">
                                <h3>Detail Barang</h3>
                                <div class="detail-barang-box">
                                    <div style="display: flex; align-items: center; gap: 2rem; width: 100%; border-bottom: 1px solid var(--table-border); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                                        <div class="detail-barang-icon">
                                            <i class="fas ${iconClass}"></i>
                                        </div>
                                        <div class="detail-barang-info">
                                            <div class="header">${surat.barang?.merek || 'N/A'}</div>
                                            <div class="subheader">${surat.barang?.jenis_barang?.nama_jenis || 'N/A'}</div>
                                        </div>
                                    </div>
                                    <dl class="detail-item">
                                        <dt>Perusahaan:</dt>
                                        <dd>${surat.barang?.perusahaan?.nama_perusahaan || 'N/A'}</dd>
                                    </dl>
                                    <dl class="detail-item">
                                        <dt>No Asset:</dt>
                                        <dd>${surat.barang?.no_asset || 'N/A'}</dd>
                                    </dl>
                                    <dl class="detail-item">
                                        <dt>Tgl. Pengadaan:</dt>
                                        <dd>${formatDate(surat.barang?.tgl_pengadaan) || 'N/A'}</dd>
                                    </dl>
                                    <dl class="detail-item">
                                        <dt>Serial Number:</dt>
                                        <dd>${surat.barang?.serial_number || 'N/A'}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>