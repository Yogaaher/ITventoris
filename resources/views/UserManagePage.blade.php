        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <title>Scuto Asset - User Manage</title>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
            <link rel="icon" href="{{ asset('img/Scuto-logo.svg') }}" type="image/x-icon">
            @vite(['resources/css/usermanage.css', 'resources/js/usermanage.js'])
        </head>

        @php
        $pageData = [
            'isSuperAdmin' => auth()->user()->isSuperAdmin(),
            'authUserId' => auth()->id(),
            'csrfToken' => csrf_token(),
            'urls' => [
                'fetch' => route('users.index'),
                'store' => route('users.store'),
                'edit' => url('/users/{id}/edit'),
                'update' => url('/users/{id}'),
                'delete' => url('/users/{id}'),
                'validateField' => route('users.validate.field'),
            ],
            'initialData' => $users->toArray(),
        ];
        @endphp

        <body data-page-data='@json($pageData)'>
            <div id="dashboard-page">
                <div class="app-container">
                    <div class="sidebar">
                        <div class="sidebar-header">
                            <div class="app-icon">
                                <img src="/img/Scuto-logo.svg" alt="Scuto Logo" class="app-logo-svg">
                                <span class="app-name-text">Scuto Asset</span>
                            </div>
                            <button id="burger-menu" class="burger-button" title="Toggle Sidebar">
                                <span></span>
                                <span></span>
                                <span></span>
                            </button>
                        </div>
                        <ul class="sidebar-list">
                            <li class="sidebar-list-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                                <a href="{{ route('dashboard.index') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                        <polyline points="9 22 9 12 15 12 15 22" />
                                    </svg>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="sidebar-list-item {{ request()->routeIs('surat.index') ? 'active' : '' }}">
                                <a href="{{ route('surat.index') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                    </svg>
                                    <span>Serah Terima</span>
                                </a>
                            </li>
                            <li class="sidebar-list-item {{ request()->routeIs('companies.index') ? 'active' : '' }}">
                                <a href="{{ route('companies.index') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tool">
                                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                                    </svg>
                                    <span>Data Master</span>
                                </a>
                            </li>
                            @if(auth()->user()->role === 'admin' || auth()->user()->isSuperAdmin())
                            <li class="sidebar-list-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                <a href="{{ route('users.index') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    <span>Manage User</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                        <div class="account-info">
                            <div class="account-info-picture">
                                <img src="/img/Logo-scuto.png" alt="Account">
                            </div>
                            <div class="account-info-name">{{ auth()->user()->name ?? 'Guest' }}</div>
                        </div>
                    </div>

                    {{-- KONTEN UTAMA APLIKASI (AREA FILTER DAN TABEL) --}}
                    <div class="app-content">
                        <div class="app-content-header">
                            <button id="mobile-burger-menu" class="burger-button" title="Buka Menu">
                                <span></span>
                                <span></span>
                                <span></span>
                            </button>
                            <h1 class="app-content-headerText">Manajemen User</h1>
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

                        <div class="app-content-actions">
                            <div class="search-bar-container">
                                <input class="search-bar"
                                    id="userSearchInput"
                                    placeholder="Cari username atau email ..."
                                    type="text"
                                    value="{{ $searchKeyword ?? '' }}"
                                    autocomplete="off">
                            </div>
                            <div class="app-content-actions-buttons">
                                <button type="button" id="openAddUserModalButton" class="action-button add-user-btn" title="Tambah User Baru">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Tambah User Baru</span>
                                </button>
                            </div>
                        </div>

                        <div class="products-area-wrapper tableView" id="productTableArea">
                            <div class="products-header">
                                <div class="product-cell cell-no">No</div>
                                <div class="product-cell cell-username">Username</div>
                                <div class="product-cell cell-email">Email</div>
                                <div class="product-cell cell-role">Role</div>
                                <div class="product-cell cell-tanggal-dibuat">Tanggal Dibuat</div>
                                <div class="product-cell cell-aksi">Aksi</div>
                            </div>

                            <div id="productTableRowsContainer">
                            </div>
                            <div class="table-footer-controls" id="pagination-controls-container">
                                <div class="footer-section footer-left">
                                    <div class="rows-per-page-wrapper">
                                        <label for="rows-per-page-select">Baris Halaman:</label>
                                        <select id="rows-per-page-select">
                                            <option value="10">10</option>
                                            <option value="20" selected>20</option>
                                            <option value="30">30</option>
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

            <div id="addUserModal" class="modal-overlay" style="display: none; z-index: 1060;">
                <div class="modal-content-wrapper device-entry-info-modal">
                    <div class="morph-modal-container">
                        <div class="morph-modal-title">
                            {{-- Judul dengan Ikon --}}
                            <span><i class="fas fa-user-plus"></i> Tambah Pengguna Baru</span>
                            <button type="button" class="btn-close" id="closeAddUserModalBtn" aria-label="Tutup modal">×</button>
                        </div>
                        <div class="morph-modal-body">
                            <form id="addUserForm" novalidate>
                                @csrf

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="name">Username</label>
                                    <input type="text" name="name" id="name" required autocomplete="off">
                                    <div class="invalid-feedback" id="name_error"></div>
                                </div>

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="email">Alamat Email</label>
                                    <input type="email" name="email" id="email" required autocomplete="off">
                                    <div class="invalid-feedback" id="email_error"></div>
                                </div>

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="add_role">Role</label>
                                    <select name="role" id="add_role" class="form-control" required style="width: 100%; padding: 12px 14px; border: 1px solid var(--table-border); border-radius: 6px; background-color: var(--app-bg); color: var(--app-content-main-color); font-size: 1.5rem;">
                                        @if(auth()->user()->isSuperAdmin())
                                        <option value="" disabled selected>Pilih Role</option>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                        <option vsalue="super_admin">Super Admin</option>
                                        @else
                                        <option value="user" selected>User</option>
                                        @endif
                                    </select>
                                    <div class="invalid-feedback" id="role_error"></div>
                                </div>

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="password">Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" name="password" id="password" required>
                                        <span class="toggle-password" data-target="password">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback" id="password_error"></div>
                                </div>

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="password_confirmation">Konfirmasi Password</label>
                                    <div class="password-wrapper">
                                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                                        <span class="toggle-password" data-target="password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                    {{-- Konfirmasi password tidak perlu pesan error sendiri --}}
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="cancelAddUserModalBtn">Batal</button>
                            <button type="submit" class="btn btn-primary" id="submitAddUserBtn" form="addUserForm">
                                <i class="fas fa-save"></i>
                                <span>Simpan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================================================================ -->
            <!--        MODAL EDIT PENGGUNA (UPDATE USER)                         -->
            <!-- ================================================================ -->
            <div id="editUserModal" class="modal-overlay" style="display: none; z-index: 1060;">
                <div class="modal-content-wrapper device-entry-info-modal">
                    <div class="morph-modal-container">
                        <div class="morph-modal-title">
                            <span><i class="fas fa-user-edit"></i> Edit Pengguna</span>
                            <button type="button" class="btn-close" id="closeEditUserModalBtn" aria-label="Tutup modal">×</button>
                        </div>
                        <div class="morph-modal-body">
                            <form id="editUserForm" novalidate>
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="edit_user_id" name="user_id">

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="edit_email">
                                        Alamat Email @if(!auth()->user()->isSuperAdmin())(Tidak dapat diubah)@endif
                                    </label>
                                    <input type="email" name="email" id="edit_email" required @if(!auth()->user()->isSuperAdmin()) readonly @endif>
                                    <div class="invalid-feedback" id="edit_email_error"></div>
                                </div>

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="edit_name">Username</label>
                                    <input type="text" name="name" id="edit_name" required>
                                    <div class="invalid-feedback" id="edit_name_error"></div>
                                </div>

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="edit_role">Role</label>
                                    <select name="role" id="edit_role" class="form-control" required style="width: 100%; padding: 12px 14px; border: 1px solid var(--table-border); border-radius: 6px; background-color: var(--app-bg); color: var(--app-content-main-color); font-size: 1.5rem;">
                                        <option value="" disabled>Pilih Role</option>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                        <option value="super_admin">Super Admin</option>
                                    </select>
                                    <div class="invalid-feedback" id="edit_role_error"></div>
                                </div>

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="edit_password">Password Baru (Kosongkan jika tidak ingin diubah)</label>
                                    <div class="password-wrapper">
                                        <input type="password" name="password" id="edit_password" autocomplete="new-password">
                                        <span class="toggle-password" data-target="edit_password"><i class="fas fa-eye"></i></span>
                                    </div>
                                    <div class="invalid-feedback" id="edit_password_error"></div>
                                </div>

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="edit_password_confirmation">Konfirmasi Password Baru</label>
                                    <div class="password-wrapper">
                                        <input type="password" name="password_confirmation" id="edit_password_confirmation" autocomplete="new-password">
                                        <span class="toggle-password" data-target="edit_password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="cancelEditUserModalBtn">Batal</button>
                            <button type="submit" class="btn btn-primary" id="submitEditUserBtn" form="editUserForm">
                                <i class="fas fa-save"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="deviceInfoModal" class="modal-overlay" style="display:none;"></div>
            <div id="userHistoryModal" class="modal-overlay" style="display:none;"></div>
            <div id="serahTerimaAsetModal" class="modal-overlay" style="display:none;"></div>
            <div id="addAssetModal" class="modal" style="display:none;"></div>

        </body>

        </html>