document.addEventListener('DOMContentLoaded', () => {
    const pageDataElement = document.body;
    const PAGE_DATA = JSON.parse(pageDataElement.dataset.pageData);
    const csrfToken = PAGE_DATA.csrfToken;
    const isSuperAdmin = PAGE_DATA.isSuperAdmin;
    const isAdmin = PAGE_DATA.isAdmin;

    const config = {
        serahTerima: {
            fetchUrl: PAGE_DATA.urls.serahTerima.fetch,
            deleteUrl: PAGE_DATA.urls.serahTerima.delete,
            detailUrl: PAGE_DATA.urls.serahTerima.detail,
            editUrl: PAGE_DATA.urls.serahTerima.edit,
            downloadUrl: PAGE_DATA.urls.serahTerima.download,
            rowsContainerId: 'serahTerimaTableRowsContainer',
            searchPlaceholder: 'Cari no surat, nama, no asset...',
            addButtonText: 'Tambah Surat',
            addButtonVisible: true,
        },
        mutasi: {
            fetchUrl: PAGE_DATA.urls.mutasi.fetch,
            deleteUrl: PAGE_DATA.urls.mutasi.delete,
            detailUrl: PAGE_DATA.urls.mutasi.detail,
            rowsContainerId: 'mutasiTableRowsContainer',
            searchPlaceholder: 'Cari serial number, merek, no asset...',
            addButtonVisible: false,
        }
    };

    let currentTab = 'serahTerima';
    let currentPage = 1;

    const ui = {
        tableBody: document.getElementById('productTableRowsContainer'),
        searchInput: document.getElementById('searchInput'),
        rowsPerPageSelect: document.getElementById('rows-per-page-select'),
        paginationInfo: document.getElementById('paginationInfoText'),
        paginationButtons: document.getElementById('paginationButtonsContainer'),
        sortableHeaders: document.querySelectorAll('.sortable-header'),
        suratModal: document.getElementById('suratModal'),
        detailSuratModal: document.getElementById('detailSuratModal'),
        suratForm: document.getElementById('suratForm'),
        searchBarangInput: document.getElementById('search_barang'),
        barangInfoBox: document.getElementById('barang-info-box'),
        barangIdInput: document.getElementById('barang_id'),
        submitSuratBtn: document.getElementById('submitSuratBtn'),
        burgerMenu: document.getElementById('burger-menu'),
        mobileBurgerMenu: document.getElementById('mobile-burger-menu'),
        sidebar: document.querySelector('.sidebar'),
        modeSwitch: document.querySelector('.mode-switch')
    };

    let state = {
        currentPage: 1,
        rowsPerPage: 10,
        searchTerm: '',
        sort: {
            key: 'null',
            direction: 'none'
        },
        searchBarangDebounce: null,
        initialEditData: null,
    };
    window.latestPaginationData = null;


    const fetchData = () => {
        const conf = config[currentTab];
        const search = ui.searchInput.value;
        const perPage = ui.rowsPerPageSelect.value;
        const url = `${conf.fetchUrl}?page=${currentPage}&per_page=${perPage}&search=${search}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                renderContent(data.data);
                renderPagination(data);
            })
            .catch(error => console.error('Fetch error:', error));
    };

    document.querySelector('.tab-content').addEventListener('click', (e) => {
        const conf = config[currentTab];
        const detailButton = e.target.closest('.detail-btn');
        const editButton = e.target.closest('.edit-btn');
        const deleteButton = e.target.closest('.delete-btn');

        if (detailButton) {
            const id = detailButton.dataset.id;
            fetch(`${conf.detailUrl}${id}`)
                .then(res => res.json())
                .then(data => {
                    if (currentTab === 'serahTerima') {
                        showDetailModal(data);
                    } else if (currentTab === 'mutasi') {
                        showDetailMutasiModal(data);
                    }
                })
                .catch(error => console.error('Gagal mengambil detail:', error));
        }

        if (editButton && currentTab === 'serahTerima') {
            const id = editButton.dataset.id;
            fetch(`/surat/${id}`)
                .then(res => res.json())
                .then(data => openModalForEdit(data))
                .catch(error => console.error('Gagal mengambil data untuk edit:', error));
        }

        if (deleteButton) {
            const id = deleteButton.dataset.id;
            if (confirm('Anda yakin ingin menghapus data ini?')) {
                fetch(`${conf.deleteUrl}${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.success);
                            fetchData();
                        } else {
                            alert(data.error || 'Gagal menghapus data.');
                        }
                    })
                    .catch(error => console.error('Gagal menghapus:', error));
            }
        }
    });


    const renderContent = (items) => {
        const conf = config[currentTab];
        const container = document.getElementById(conf.rowsContainerId);
        container.innerHTML = '';

        if (!items || items.length === 0) {
            container.innerHTML = `<div class="products-row"><div class="product-cell" style="flex:1;text-align:center;padding:2rem;">Tidak ada data ditemukan.</div></div>`;
            return;
        }

        const offset = (currentPage - 1) * ui.rowsPerPageSelect.value;
        items.forEach((item, index) => {
            const row = document.createElement('div');
            row.className = 'products-row';
            const rowNumber = offset + index + 1;

            if (currentTab === 'serahTerima') {
                row.innerHTML = renderSerahTerimaRow(item, item.original_row_number || rowNumber);
            } else if (currentTab === 'mutasi') {
                row.innerHTML = renderMutasiRow(item, rowNumber);
            }
            container.appendChild(row);
        });
    };

    const renderSerahTerimaRow = (surat, number) => {
        const merek = surat.merek || 'N/A';
        let actions = `<button class="action-btn-table detail-btn" data-id="${surat.id}" title="Lihat Detail"><i class="fas fa-info-circle"></i></button>`;

        if (isAdmin || isSuperAdmin) {
            actions = `<a href="${config.serahTerima.downloadUrl}${surat.id}" class="action-btn-table download-btn" title="Download PDF" target="_blank" rel="noopener noreferrer"><i class="fas fa-download"></i></a>` + actions;
        }

        if (isSuperAdmin) {
            actions += `<button class="action-btn-table edit-btn" data-id="${surat.id}" title="Edit Surat"><i class="fas fa-edit"></i></button>
                        <button class="action-btn-table delete-btn" data-id="${surat.id}" title="Hapus Surat"><i class="fas fa-trash-alt"></i></button>`;
        }
        return `
        <div class="product-cell cell-no">${number}</div>
        <div class="product-cell" style="flex:1.5">${surat.no_surat}</div>
        <div class="product-cell" style="flex:1.5">${surat.nama_penerima}</div>
        <div class="product-cell" style="flex:1.5">${surat.nama_pemberi}</div>
        <div class="product-cell" style="flex:2">${merek}</div>
        <div class="product-cell" style="flex:1.5">${surat.no_asset || ''}</div>
        <div class="product-cell" style="flex:2">${surat.serial_number || ''}</div>
        <div class="product-cell cell-aksi">${actions}</div>
    `;
    };

    const renderMutasiRow = (mutasi, number) => {
        const merek = mutasi.barang?.merek || 'N/A';
        let actions = `<button class="action-btn-table detail-btn" data-id="${mutasi.id}" title="Lihat Detail"><i class="fas fa-info-circle"></i></button>`;
        if (isSuperAdmin) {
            actions += `<button class="action-btn-table delete-btn" data-id="${mutasi.id}" title="Hapus Mutasi"><i class="fas fa-trash-alt"></i></button>`;
        }
        return `
        <div class="product-cell cell-no">${number}</div>
        <div class="product-cell" style="flex:2">${mutasi.barang?.serial_number || ''}</div>
        <div class="product-cell" style="flex:2">${merek}</div>
        <div class="product-cell" style="flex:1.5">${mutasi.no_asset_lama}</div>
        <div class="product-cell" style="flex:1.5">${mutasi.no_asset_baru}</div>
        <div class="product-cell" style="flex:1.5">${new Date(mutasi.tanggal_mutasi).toLocaleDateString('id-ID')}</div>
        <div class="product-cell cell-aksi">${actions}</div>
    `;
    };

    const renderPagination = (data) => {
        const infoText = data.total > 0 ?
            `Menampilkan <b>${data.from}</b> - <b>${data.to}</b> dari <b>${data.total}</b> hasil` :
            'Tidak ada data ditemukan.';
        ui.paginationInfo.innerHTML = infoText;
        ui.paginationButtons.innerHTML = '';

        if (!data.total || data.last_page === 1) {
            ui.paginationButtons.innerHTML = `
                <button class="pagination-btn" disabled>«</button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn" disabled>»</button>
            `;
            return;
        }

        const links = data.links || [];
        links.forEach(link => {
            const btn = document.createElement('button');
            btn.className = 'pagination-btn';
            let label = link.label;
            if (label.includes('Previous')) {
                label = '«';
            } else if (label.includes('Next')) {
                label = '»';
            }
            btn.innerHTML = label;

            btn.disabled = !link.url;
            if (link.active) {
                btn.classList.add('active');
            }

            if (link.url) {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    state.currentPage = new URL(link.url).searchParams.get('page');
                    fetchData();
                });
            }
            ui.paginationButtons.appendChild(btn);
        });
    };

    const resetForm = (form) => {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        ui.barangInfoBox.style.display = 'none';
        ui.barangInfoBox.innerHTML = '';
        ui.barangIdInput.value = '';
        if (ui.submitSuratBtn) {
            ui.submitSuratBtn.disabled = true;
            ui.submitSuratBtn.removeAttribute('data-tooltip');
        }
        state.initialEditData = null;
    };

    const setupUI = () => {
        const SIDEBAR_COLLAPSED_KEY = 'sidebarCollapsedITventory';

        const applyTheme = () => {
            const theme = localStorage.getItem('theme');
            document.documentElement.classList.toggle('light', theme === 'light');
            if (ui.modeSwitch) ui.modeSwitch.classList.toggle('active', theme === 'light');
        };

        const initializeSidebar = () => {
            if (!ui.sidebar || window.innerWidth <= 1024) return;
            const isCollapsed = localStorage.getItem(SIDEBAR_COLLAPSED_KEY) === 'true';
            ui.sidebar.classList.toggle('collapsed', isCollapsed);
            if (ui.burgerMenu) ui.burgerMenu.classList.toggle('active', !isCollapsed);
        };

        if (ui.modeSwitch) {
            ui.modeSwitch.addEventListener('click', () => {
                document.documentElement.classList.toggle('light');
                ui.modeSwitch.classList.toggle('active');
                localStorage.setItem('theme', document.documentElement.classList.contains('light') ? 'light' : 'dark');
            });
        }

        [ui.burgerMenu, ui.mobileBurgerMenu].forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    btn.classList.toggle('active');
                    if (window.innerWidth <= 1024) {
                        ui.sidebar.classList.toggle('mobile-open');
                        document.body.classList.toggle('sidebar-open-overlay');
                    } else {
                        ui.sidebar.classList.toggle('collapsed');
                        localStorage.setItem(SIDEBAR_COLLAPSED_KEY, ui.sidebar.classList.contains('collapsed'));
                    }
                });
            }
        });

        document.body.addEventListener('click', (event) => {
            if (event.target === document.body && document.body.classList.contains('sidebar-open-overlay')) {
                ui.sidebar.classList.remove('mobile-open');
                document.body.classList.remove('sidebar-open-overlay');
                if (ui.mobileBurgerMenu) ui.mobileBurgerMenu.classList.remove('active');
            }
        });

        applyTheme();
        initializeSidebar();
    };

    const updateHeaderArrows = () => {
        ui.sortableHeaders.forEach(header => {
            const sortBy = header.dataset.sortBy;
            header.classList.remove('sorted-asc', 'sorted-desc');

            if (sortBy === state.sort.key) {
                if (sortBy === 'no') {
                    if (state.sort.direction === 'desc') {
                        header.classList.add('sorted-desc');
                    }
                } else {
                    if (state.sort.direction === 'asc') {
                        header.classList.add('sorted-asc');
                    } else if (state.sort.direction === 'desc') {
                        header.classList.add('sorted-desc');
                    }
                }
            }
        });
    };

    const openModal = (modalElement) => {
        if (modalElement) {
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');
        }
    };

    const closeModal = (modalElement) => {
        if (modalElement) {
            modalElement.classList.remove('show');
            if (!document.querySelector('.modal.show')) {
                document.body.classList.remove('modal-open');
            }
        }
    };

    const openModalForEdit = (surat) => {
        resetForm(ui.suratForm);

        document.getElementById('suratModalTitle').textContent = 'Edit Surat Serah Terima';
        document.getElementById('suratModalIcon').className = 'fas fa-edit modal-header-icon';
        document.getElementById('submitSuratBtn').textContent = 'Update';
        document.getElementById('submitSuratBtn').disabled = true;

        document.getElementById('surat_id').value = surat.id;
        document.getElementById('no_surat_auto').value = surat.no_surat;
        document.getElementById('barang_id').value = surat.barang_id;
        document.getElementById('nama_penerima').value = surat.nama_penerima;
        document.getElementById('nik_penerima').value = surat.nik_penerima;
        document.getElementById('jabatan_penerima').value = surat.jabatan_penerima;
        document.getElementById('nama_pemberi').value = surat.nama_pemberi;
        document.getElementById('nik_pemberi').value = surat.nik_pemberi;
        document.getElementById('jabatan_pemberi').value = surat.jabatan_pemberi;
        document.getElementById('penanggung_jawab').value = surat.penanggung_jawab;
        document.getElementById('keterangan').value = surat.keterangan || '';

        state.initialEditData = {
            barang_id: surat.barang_id,
            nama_penerima: surat.nama_penerima,
            nik_penerima: surat.nik_penerima,
            jabatan_penerima: surat.jabatan_penerima,
            nama_pemberi: surat.nama_pemberi,
            nik_pemberi: surat.nik_pemberi,
            jabatan_pemberi: surat.jabatan_pemberi,
            penanggung_jawab: surat.penanggung_jawab,
            keterangan: surat.keterangan || ''
        };

        const searchBarang = document.getElementById('search_barang');
        searchBarang.value = surat.barang?.serial_number || surat.barang?.no_asset || '';
        searchBarang.disabled = false;

        ui.barangInfoBox.innerHTML = `
        <p><strong>Aset Terpilih:</strong> ${surat.barang?.merek || 'N/A'}</p>
        <p><strong>No Asset:</strong> ${surat.barang?.no_asset || 'N/A'}</p>
        <p><strong>S/N:</strong> ${surat.barang?.serial_number || 'N/A'}</p>`;
        ui.barangInfoBox.style.display = 'block';

        openModal(ui.suratModal);
    };

    const showDetailModal = (surat) => {
        const modalBody = ui.detailSuratModal.querySelector('.modal-body');
        if (!modalBody) return;

        const formatDate = (dateString) => {
            if (!dateString) return 'N/A';
            const options = {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        };

        const iconClassFromDb = surat.barang?.jenis_barang?.icon || 'fas fa-question-circle';

        modalBody.innerHTML = `
        <div class="detail-section">
            <h3>Pihak Pertama (Penerima)</h3>
            <div class="pihak-info">
                <p class="pihak-nama">${surat.nama_penerima || 'N/A'}</p>
                <p class="pihak-subinfo">${surat.nik_penerima || 'N/A'}</p>
                <p class="pihak-subinfo">${surat.jabatan_penerima || 'N/A'}</p>
            </div>
        </div>
        <div class="detail-section">
            <h3>Pihak Kedua (Pemberi)</h3>
            <div class="pihak-info">
                <p class="pihak-nama">${surat.nama_pemberi || 'N/A'}</p>
                <p class="pihak-subinfo">${surat.nik_pemberi || 'N/A'}</p>
                <p class="pihak-subinfo">${surat.jabatan_pemberi || 'N/A'}</p>
            </div>
        </div>

        <div class="detail-section">
            <h3>Informasi Surat</h3>
            <dl class="detail-item"><dt>No. Surat:</dt><dd>${surat.no_surat || 'N/A'}</dd></dl>
            <dl class="detail-item"><dt>Tgl. Dibuat:</dt><dd>${formatDate(surat.created_at)}</dd></dl>
            <dl class="detail-item"><dt>Penanggung Jawab:</dt><dd>${surat.penanggung_jawab || 'N/A'}</dd></dl>
            <dl class="detail-item" style="align-items: flex-start;"><dt>Keterangan:</dt><dd style="white-space: pre-wrap;">${surat.keterangan || '-'}</dd></dl>
        </div>

        <div class="detail-section">
            <h3>Detail Barang</h3>
            <div class="detail-barang-box">
                <div class="detail-barang-icon">
                    <i class="${iconClassFromDb}"></i>
                </div>
                <div class="detail-barang-info">
                    <div class="header">${surat.barang?.merek || 'N/A'}</div>
                    <div class="subheader">${surat.barang?.jenis_barang?.nama_jenis || 'N/A'}</div>
                </div>
            </div>
            <hr style="border: none; border-top: 1px solid var(--table-border); margin: 1.5rem 0;">
            <dl class="detail-item"><dt>Perusahaan:</dt><dd>${surat.barang?.perusahaan?.nama_perusahaan || 'N/A'}</dd></dl>
            <dl class="detail-item"><dt>No Asset:</dt><dd>${surat.barang?.no_asset || 'N/A'}</dd></dl>
            <dl class="detail-item"><dt>Tgl. Pengadaan:</dt><dd>${formatDate(surat.barang?.tgl_pengadaan)}</dd></dl>
            <dl class="detail-item"><dt>Serial Number:</dt><dd>${surat.barang?.serial_number || 'N/A'}</dd></dl>
        </div>
    `;
        openModal(ui.detailSuratModal);
    };

    const showDetailMutasiModal = (mutasi) => {
        const modal = document.getElementById('detailMutasiModal');
        if (!modal) return;

        const formatDate = (dateString) => {
            if (!dateString) return 'N/A';
            const options = {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        };

        const iconClass = mutasi.barang?.jenis_barang?.icon || 'fas fa-question-circle';

        document.getElementById('detailMutasiIcon').className = `detail-barang-icon ${iconClass}`;
        document.getElementById('detailMutasiMerek').textContent = mutasi.barang?.merek || 'N/A';
        document.getElementById('detailMutasiJenis').textContent = mutasi.barang?.jenis_barang?.nama_jenis || 'N/A';
        document.getElementById('detailMutasiSN').textContent = mutasi.barang?.serial_number || 'N/A';
        document.getElementById('detailMutasiTanggal').textContent = formatDate(mutasi.tanggal_mutasi);

        document.getElementById('detailMutasiAssetLama').textContent = mutasi.no_asset_lama || 'N/A';
        document.getElementById('detailMutasiPerusahaanLama').textContent = mutasi.perusahaan_lama?.nama_perusahaan || 'N/A';
        document.getElementById('detailMutasiPenggunaLama').textContent = mutasi.pengguna_lama || 'N/A';

        document.getElementById('detailMutasiAssetBaru').textContent = mutasi.no_asset_baru || 'N/A';
        document.getElementById('detailMutasiPerusahaanBaru').textContent = mutasi.perusahaan_baru?.nama_perusahaan || 'N/A';
        document.getElementById('detailMutasiPenggunaBaru').textContent = mutasi.pengguna_baru || 'N/A';

        openModal(modal);
    };

    document.querySelector('.nav-tabs').addEventListener('click', (e) => {
        const navButton = e.target.closest('.nav-link');
        if (!navButton || navButton.classList.contains('active')) return;

        currentTab = navButton.dataset.tab;

        document.querySelectorAll('.nav-link').forEach(link => {
            const isActive = link === navButton;
            link.classList.toggle('active', isActive);
            link.style.opacity = isActive ? '1' : '0.7';
            link.style.borderBottomColor = isActive ? 'var(--action-color)' : 'transparent';
        });

        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.style.display = (pane.id === `${currentTab}-tab-pane`) ? 'block' : 'none';
        });

        const conf = config[currentTab];
        ui.searchInput.placeholder = conf.searchPlaceholder;
        const addButton = document.getElementById('openAddSuratModalButton'); 
        if (addButton) {
            addButton.style.display = conf.addButtonVisible ? 'flex' : 'none';
        }

        currentPage = 1;
        ui.searchInput.value = '';
        fetchData();
    });

    const setupSmartModalClosure = (modalElement, closeFunction) => {
        if (!modalElement) return;

        let isMouseDownOnOverlay = false;
        modalElement.addEventListener('mousedown', (event) => {
            if (event.target === modalElement) {
                isMouseDownOnOverlay = true;
            }
        });

        modalElement.addEventListener('mouseup', (event) => {
            if (event.target === modalElement && isMouseDownOnOverlay) {
                closeFunction(modalElement);
            }
            isMouseDownOnOverlay = false;
        });

        modalElement.addEventListener('mouseleave', () => {
            isMouseDownOnOverlay = false;
        });
    };

    ui.sortableHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const sortKey = header.dataset.sortBy;

            if (sortKey === 'no') {
                if (state.sort.key !== 'no') {
                    state.sort.key = 'no';
                    state.sort.direction = 'asc';
                } else {
                    state.sort.direction = (state.sort.direction === 'asc') ? 'desc' : 'asc';
                }
            } else {
                if (state.sort.key !== sortKey) {
                    state.sort.key = sortKey;
                    state.sort.direction = 'asc';
                } else {
                    if (state.sort.direction === 'asc') {
                        state.sort.direction = 'desc';
                    } else {
                        state.sort.key = null;
                        state.sort.direction = 'none';
                    }
                }
            }

            updateHeaderArrows();
            fetchData();
        });
    });

    ui.searchInput.addEventListener('input', () => {
        clearTimeout(state.searchDebounce);
        state.searchDebounce = setTimeout(() => {
            state.searchTerm = ui.searchInput.value;
            state.currentPage = 1;
            fetchData();
        }, 500);
    });

    ui.rowsPerPageSelect.addEventListener('change', () => {
        state.rowsPerPage = parseInt(ui.rowsPerPageSelect.value, 10);
        state.currentPage = 1;
        fetchData();
    });

    const openAddSuratModalButton = document.getElementById('openAddSuratModalButton');
    if (openAddSuratModalButton) {
        openAddSuratModalButton.addEventListener('click', () => {
            resetForm(ui.suratForm);
            document.getElementById('suratModalTitle').textContent = 'Tambah Surat Serah Terima';
            document.getElementById('suratModalIcon').className = 'fas fa-plus-square modal-header-icon';
            document.getElementById('submitSuratBtn').textContent = 'Simpan';
            document.getElementById('surat_id').value = '';
            document.getElementById('search_barang').disabled = false;

            const noSuratInput = document.getElementById('no_surat_auto');
            noSuratInput.value = '';
            noSuratInput.placeholder = 'Memuat nomor...';

            fetch(PAGE_DATA.urls.serahTerima.getProspectiveNomor)
                .then(res => res.json())
                .then(data => {
                    if (data.nomor_surat) {
                        noSuratInput.value = data.nomor_surat;
                    } else {
                        noSuratInput.placeholder = 'Gagal memuat nomor';
                    }
                })
                .catch(() => {
                    noSuratInput.placeholder = 'Gagal memuat nomor';
                });

            openModal(ui.suratModal);
        });
    }

    const mutasiModal = document.getElementById('detailMutasiModal');

    [ui.suratModal, ui.detailSuratModal, mutasiModal].forEach(modal => { 
        if (modal) {
            modal.querySelectorAll('.close-button').forEach(btn => {
                btn.addEventListener('click', () => closeModal(modal));
            });
            setupSmartModalClosure(modal, () => closeModal(modal));
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (ui.suratModal && ui.suratModal.classList.contains('show')) {
                closeModal(ui.suratModal);
            }
            if (ui.detailSuratModal && ui.detailSuratModal.classList.contains('show')) {
                closeModal(ui.detailSuratModal);
            }
            if (mutasiModal && mutasiModal.classList.contains('show')) {
                closeModal(mutasiModal);
            }
        }
    });

    ui.searchBarangInput.addEventListener('input', (e) => {
        clearTimeout(state.searchBarangDebounce);
        state.searchBarangDebounce = setTimeout(() => {
            const query = e.target.value;
            if (query.length < 3) {
                ui.barangInfoBox.style.display = 'none';
                ui.submitSuratBtn.disabled = true;
                return;
            }

            fetch(`${PAGE_DATA.urls.serahTerima.findBarang}?query=${query}`)
                .then(response => response.json())
                .then(barang => {
                    ui.barangInfoBox.style.display = 'block';
                    ui.barangIdInput.value = '';
                    ui.submitSuratBtn.disabled = true;

                    if (barang) {
                        ui.barangInfoBox.className = '';
                        ui.barangInfoBox.innerHTML = `
                        <p><strong>Aset Ditemukan:</strong> ${barang.merek}</p>
                        <p><strong>No Asset:</strong> ${barang.no_asset}</p>
                        <p><strong>Serial Number:</strong> ${barang.serial_number}</p>
                    `;
                        ui.barangIdInput.value = barang.id;
                        ui.submitSuratBtn.disabled = false;
                    } else {
                        ui.barangInfoBox.className = 'info-box-error';
                        ui.barangInfoBox.innerHTML = `
                        <h4><i class="fas fa-exclamation-triangle"></i> Aset Tidak Ditemukan</h4>
                        <p>Pastikan Serial Number atau No. Asset yang Anda masukkan sudah benar dan tidak ada salah ketik.</p>
                    `;
                    }
                });
        }, 500);
    });

    ui.suratForm.addEventListener('input', () => {
        if (!state.initialEditData) return;

        const currentData = {
            barang_id: parseInt(document.getElementById('barang_id').value, 10),
            nama_penerima: document.getElementById('nama_penerima').value,
            nik_penerima: document.getElementById('nik_penerima').value,
            jabatan_penerima: document.getElementById('jabatan_penerima').value,
            nama_pemberi: document.getElementById('nama_pemberi').value,
            nik_pemberi: document.getElementById('nik_pemberi').value,
            jabatan_pemberi: document.getElementById('jabatan_pemberi').value,
            penanggung_jawab: document.getElementById('penanggung_jawab').value,
            keterangan: document.getElementById('keterangan').value
        };

        let hasChanged = false;
        for (const key in state.initialEditData) {
            if (state.initialEditData[key] !== currentData[key]) {
                hasChanged = true;
                break;
            }
        }

        if (hasChanged) {
            ui.submitSuratBtn.disabled = false;
            ui.submitSuratBtn.removeAttribute('data-tooltip');
        } else {
            ui.submitSuratBtn.disabled = true;
            ui.submitSuratBtn.setAttribute('data-tooltip', 'Tidak ada perubahan data yang terdeteksi');
        }
    });

    ui.suratForm.addEventListener('submit', (e) => {
        e.preventDefault();
        ui.submitSuratBtn.disabled = true;
        ui.submitSuratBtn.textContent = 'Menyimpan...';
        ui.suratForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        ui.suratForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        const formData = new FormData(ui.suratForm);
        const suratId = document.getElementById('surat_id').value;

        let url = PAGE_DATA.urls.serahTerima.store;
        if (suratId) {
            url = `/surat/${suratId}`;
            formData.append('_method', 'PUT');
        }

        fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json().then(data => ({
                status: response.status,
                body: data
            })))
            .then(({
                status,
                body
            }) => {
                if (status === 200 || status === 201) {
                    alert(body.success);
                    closeModal(ui.suratModal);
                    fetchData();
                } else if (status === 422) {
                    Object.keys(body.errors).forEach(key => {
                        const errorDiv = document.getElementById(`${key}_error`);
                        let inputElement = document.getElementById(key);
                        if (key === 'barang_id') {
                            inputElement = document.getElementById('search_barang');
                        }

                        if (errorDiv) {
                            errorDiv.textContent = body.errors[key][0];
                        }
                        if (inputElement) {
                            inputElement.classList.add('is-invalid');
                        }
                    });

                    const firstInvalidElement = ui.suratForm.querySelector('.is-invalid');
                    if (firstInvalidElement) {
                        const scrollTarget = firstInvalidElement.closest('.form-section') || firstInvalidElement;
                        const modalBody = ui.suratModal.querySelector('.modal-body');

                        if (modalBody && scrollTarget) {
                            const elementRect = scrollTarget.getBoundingClientRect();
                            const modalBodyRect = modalBody.getBoundingClientRect();
                            const offsetTop = elementRect.top - modalBodyRect.top + modalBody.scrollTop;

                            modalBody.scrollTo({
                                top: offsetTop - 20,
                                behavior: 'smooth'
                            });
                        }
                    }

                } else {
                    alert(body.error || 'Terjadi kesalahan.');
                }
            })
            .catch(err => alert('Terjadi kesalahan jaringan.'))
            .finally(() => {
                ui.submitSuratBtn.disabled = false;
                ui.submitSuratBtn.textContent = suratId ? 'Update' : 'Simpan';
            });
    });

    document.querySelector('.tab-content').addEventListener('click', (e) => {
        const conf = config[currentTab];
        const editButton = e.target.closest('.edit-btn');
        const deleteButton = e.target.closest('.delete-btn');
        const detailButton = e.target.closest('.detail-btn');

        if (editButton && typeof openModalForEdit === 'function') {
            fetch(`${conf.editUrl}${editButton.dataset.id}`)
                .then(res => res.json())
                .then(data => openModalForEdit(data));
        }

        if (deleteButton) {
            const id = deleteButton.dataset.id;
            if (confirm('Anda yakin ingin menghapus data ini?')) {
                fetch(`${conf.deleteUrl}${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.success);
                            fetchData();
                        } else {
                            alert(data.error || 'Gagal menghapus data.');
                        }
                    });
            }
        }

        if (detailButton) {
            const id = detailButton.dataset.id;
            fetch(`${conf.detailUrl}${id}`)
                .then(res => res.json())
                .then(data => {
                    if (currentTab === 'serahTerima') showDetailModal(data);
                    else if (currentTab === 'mutasi') showDetailMutasiModal(data);
                });
        }
    });

    setupUI();
    fetchData();
});