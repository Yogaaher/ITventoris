    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Scuto Asset - Data Master</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-iconpicker/1.10.0/css/bootstrap-iconpicker.min.css" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
        <link rel="icon" href="{{ asset('img/Scuto-logo.svg') }}" type="image/x-icon">
        @vite(['resources/css/perusahaan.css', 'resources/js/perusahaan.js'])
    </head>

    @php
    $pageData = [
        'isSuperAdmin' => auth()->user()->isSuperAdmin(),
        'isAdmin' => auth()->user()->isAdmin(),
        'csrfToken' => csrf_token(),
        'urls' => [
            'company' => [
                'fetch' => route('companies.data'),
                'store' => route('companies.store'),
                'update' => url('/companies/'),
                'delete' => url('/companies/'),
                'edit' => url('/companies/{id}/edit'),
            ],
            'itemType' => [
                'fetch' => route('item-types.data'),
                'store' => route('item-types.store'),
                'update' => url('/item-types/'),
                'delete' => url('/item-types/'),
                'edit' => url('/item-types/{id}/edit'),
            ],
        ],
        'initialData' => [
            'company' => $companies->toArray(),
        ],
    ];
    @endphp

    <body data-page-data='@json($pageData)'>
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
                        <h1 class="app-content-headerText">Data Master</h1>
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

                    <ul class="nav-tabs">
                        <li>
                            <button class="nav-link active" data-tab="company">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                                <span>Perusahaan</span>
                            </button>
                        </li>
                        <li>
                            <button class="nav-link" data-tab="itemType">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                </svg>
                                <span>Jenis Barang</span>
                            </button>
                        </li>
                    </ul>

                    <div class="app-content-actions">
                        <div class="search-bar-container">
                            <input class="search-bar" id="searchInput" placeholder="Cari nama atau singkatan perusahaan..." type="text" autocomplete="off">
                            <button type="button" class="clear-search-btn" id="clearSearchBtn" style="display: none;" title="Hapus pencarian">
                                ×
                            </button>
                        </div>
                        <div class="app-content-actions-buttons">
                            <button type="button" id="openAddModalButton" class="action-button add-company-btn" title="Tambah Data Baru">
                                <i class="fas fa-plus"></i>
                                <span id="addButtonText">Tambah Perusahaan</span>
                            </button>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="company-tab-pane">
                            <div class="products-area-wrapper tableView" id="companyTableArea">
                                <div class="products-header">
                                    <div class="product-cell cell-no">No</div>
                                    <div class="product-cell cell-nama-perusahaan">Nama Perusahaan</div>
                                    <div class="product-cell cell-singkatan">Singkatan</div>
                                    <div class="product-cell cell-dibuat-pada">Dibuat Pada</div>
                                    @if(auth()->user()->isSuperAdmin())
                                    <div class="product-cell cell-aksi" style="justify-content: center;">Aksi</div>
                                    @endif
                                </div>
                                <div id="companyTableRowsContainer">
                                    @include('partials.company_table_rows', ['companies' => $companies])
                                </div>
                                <div class="table-footer-controls" id="company-pagination-container">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="itemType-tab-pane">
                            <div class="products-area-wrapper tableView" id="itemTypeTableArea">
                                <div class="products-header">
                                    <div class="product-cell cell-no">No</div>
                                    <div class="product-cell cell-nama-perusahaan">Nama Jenis</div>
                                    <div class="product-cell cell-singkatan">Singkatan</div>
                                    <div class="product-cell cell-icon">Icon</div>
                                    <div class="product-cell cell-dibuat-pada">Dibuat Pada</div>
                                    @if(auth()->user()->isSuperAdmin())
                                    <div class="product-cell cell-aksi" style="justify-content: center;">Aksi</div>
                                    @endif
                                </div>
                                <div id="itemTypeTableRowsContainer">
                                </div>
                                <div class="table-footer-controls" id="itemType-pagination-container">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="dataModal" class="modal-overlay">
            <div class="modal-content-wrapper device-entry-info-modal">
                <form id="dataForm" novalidate>
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">
                    <input type="hidden" id="dataId" name="data_id" value="">

                    <div class="morph-modal-container">
                        <div class="morph-modal-title">
                            <span id="modalTitle"><i class="fas fa-plus-circle"></i> Tambah Data Baru</span>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Tutup modal">×</button>
                        </div>
                        <div class="morph-modal-body">
                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label for="nama" id="namaLabel">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" required autocomplete="off" placeholder="Masukkan nama...">
                                <div class="invalid-feedback" data-error-for="nama"></div>
                            </div>
                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label for="singkatan">Singkatan</label>
                                <input type="text" name="singkatan" id="singkatan" required autocomplete="off" placeholder="MAX">
                                <div class="invalid-feedback" data-error-for="singkatan"></div>
                            </div>
                            <div class="form-group" id="icon-form-group" style="margin-bottom: 1.5rem; display: none;">
                                <label for="icon">Pilih Icon</label>
                                <button type="button" class="btn btn-secondary iconpicker-component" id="icon-picker-button">
                                    <i class="fas fa-fw fa-heart"></i>
                                </button>
                                <input type="hidden" id="icon" name="icon" value="fas fa-heart">
                                <div class="invalid-feedback" data-error-for="icon"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i>
                                <span>Simpan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-iconpicker/1.10.0/js/bootstrap-iconpicker.bundle.min.js"></script>

    </body>
    </html>