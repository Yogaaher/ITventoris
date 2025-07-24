document.addEventListener('DOMContentLoaded', () => {
    const pageDataElement = document.body;
    window.PAGE_DATA = JSON.parse(pageDataElement.dataset.pageData);
    const IS_SUPER_ADMIN = window.PAGE_DATA.isSuperAdmin;
    let currentAssetIdForSerahTerima = null;
    let debounceTimer;
    let lastKnownUserFromDetail = '-';
    let currentDetailModalAssetId = null;
    const DEBOUNCE_DELAY = 500;

    function openDetailModal(barangId) {
        currentDetailModalAssetId = barangId;
        const detailModalOverlay = document.getElementById('deviceInfoModal');
        if (!detailModalOverlay) return;

        const modalDeviceName = document.getElementById('modalDeviceName');
        const modalDeviceType = document.getElementById('modalDeviceType');
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
        const modalKuantitas = document.getElementById('modalKuantitas');
        const modalLokasi = document.getElementById('modalLokasi');

        if (!modalDeviceName || !deviceImage) {
            console.error('Elemen wajib pada modal detail (seperti nama atau gambar) tidak ditemukan.');
            return;
        }

        modalDeviceName.textContent = 'Memuat...';
        modalDeviceType.textContent = '_';
        modalPerusahaan.textContent = '_';
        modalNoAsset.textContent = '_';
        modalKuantitas.textContent = '_';
        modalTglPengadaan.textContent = '_';
        modalSerialNumber.textContent = '_';
        modalLokasi.textContent = '_';
        modalUser.textContent = '_';
        modalTglPenyerahan.textContent = '_';
        modalTglPengembalian.textContent = '_';
        modalStatus.innerHTML = '_';
        modalKeterangan.textContent = '_';
        deviceImage.className = 'device-image fas fa-spinner fa-spin';

        fetch(`/barang/detail/${barangId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.barang) {
                    const barang = data.barang;
                    const track = data.track;

                    modalDeviceName.textContent = barang.merek || 'N/A';
                    modalDeviceType.textContent = barang.jenis_barang ? barang.jenis_barang.nama_jenis : 'N/A';
                    modalPerusahaan.textContent = barang.perusahaan ? barang.perusahaan.nama_perusahaan : 'N/A';
                    modalNoAsset.textContent = barang.no_asset || 'N/A';
                    modalKuantitas.textContent = barang.kuantitas || 'N/A';
                    modalTglPengadaan.textContent = formatDate(barang.tgl_pengadaan);
                    modalSerialNumber.textContent = barang.serial_number || 'N/A';
                    modalLokasi.textContent = barang.lokasi || 'N/A';

                    if (track) {
                        modalUser.textContent = track.username || 'N/A';
                        lastKnownUserFromDetail = track.username || '-';
                        modalTglPenyerahan.textContent = formatDate(track.tanggal_awal);
                        modalTglPengembalian.textContent = track.tanggal_ahir ? formatDate(track.tanggal_ahir) : '-';
                        modalStatus.innerHTML = `<span style="font-weight: bold; color: var(--color-accent);">${track.status || 'N/A'}</span>`;
                        modalKeterangan.textContent = track.keterangan || 'Tidak ada keterangan.';
                    } else {
                        modalUser.textContent = '-';
                        lastKnownUserFromDetail = '-';
                        modalTglPenyerahan.textContent = '-';
                        modalTglPengembalian.textContent = '-';
                        modalStatus.innerHTML = `<span style="font-weight: bold; color: var(--color-accent);">${barang.status || 'N/A'}</span>`;
                        modalKeterangan.textContent = 'Belum ada riwayat serah terima.';
                    }


                    const btnUpdateAset = document.querySelector('#deviceInfoModal .btn-action.btn-update');
                    if (btnUpdateAset) {
                        const currentStatus = track ? track.status : barang.status;

                        if (currentStatus === 'non aktif') {
                            btnUpdateAset.style.display = 'none';
                            btnUpdateAset.disabled = true;
                        } else {
                            btnUpdateAset.style.display = 'inline-flex';
                            btnUpdateAset.disabled = false;
                            btnUpdateAset.dataset.assetId = barang.id;
                            btnUpdateAset.dataset.serialNumber = barang.serial_number;
                            btnUpdateAset.onclick = handleOpenSerahTerimaModal;
                        }
                    }

                    const triggerHistoryButton = document.getElementById('triggerUserHistoryModalButton');
                    if (triggerHistoryButton && barang.serial_number) {
                        const newTriggerHistoryButton = triggerHistoryButton.cloneNode(true);
                        triggerHistoryButton.parentNode.replaceChild(newTriggerHistoryButton, triggerHistoryButton);
                        const deviceFullName = `${barang.merek || 'N/A'} (${barang.jenis_barang ? barang.jenis_barang.nama_jenis : 'N/A'})`;
                        const currentCompany = barang.perusahaan ? barang.perusahaan.nama_perusahaan : 'N/A';
                        newTriggerHistoryButton.addEventListener('click', () => openUserHistoryModal(barang.serial_number, deviceFullName, currentCompany));
                    }

                    let iconFromDb = 'fas fa-question-circle';
                    if (barang.jenis_barang && barang.jenis_barang.icon) {
                        iconFromDb = barang.jenis_barang.icon;
                    }
                    deviceImage.className = `device-image ${iconFromDb}`;
                    detailModalOverlay.style.display = 'flex';

                } else {
                    alert(data.error || 'Data tidak ditemukan.');
                    modalDeviceName.textContent = 'Error';
                    deviceImage.className = 'device-image fas fa-exclamation-triangle';
                }
            })
            .catch(error => {
                console.error('Error selama proses fetch detail barang:', error);
                alert('Gagal mengambil detail barang. Lihat konsol untuk detail.');
                modalDeviceName.textContent = 'Error';
                deviceImage.className = 'device-image fas fa-exclamation-triangle';
            });
    }

    function formatDate(dateString) {
        if (!dateString) return '-';
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                const parts = dateString.split('-');
                if (parts.length === 3) {
                    const year = parseInt(parts[0], 10);
                    const month = parseInt(parts[1], 10) - 1;
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

        const usernameErrorEl = document.getElementById('username_serah_error');
        const perusahaanTujuanErrorEl = document.getElementById('perusahaan_tujuan_serah_error');
        if (usernameErrorEl) usernameErrorEl.textContent = '';
        if (perusahaanTujuanErrorEl) perusahaanTujuanErrorEl.textContent = '';
        serahTerimaUserInput.classList.remove('is-invalid');
        serahTerimaPerusahaanTujuanSelect.classList.remove('is-invalid');

        const previousUser = (lastKnownUserFromDetail && lastKnownUserFromDetail !== '-' && lastKnownUserFromDetail.trim() !== '') ? lastKnownUserFromDetail : 'User Sebelumnya Tidak Diketahui';

        if (selectedStatus === 'digunakan') {} else if (selectedStatus === 'tersedia') {
            serahTerimaUserInput.value = 'Team IT';
        } else if (selectedStatus === 'diperbaiki') {
            serahTerimaUserInput.value = `Team IT - ${previousUser}`;
        } else if (selectedStatus === 'non aktif') {
            serahTerimaUserInput.value = 'Team IT';
        } else if (selectedStatus === 'dipindah') {
            perusahaanTujuanGroup.style.display = 'block';
            serahTerimaPerusahaanTujuanSelect.setAttribute('required', 'required');
        }
    }

    async function handleOpenSerahTerimaModal(event) {
        const assetId = event.currentTarget.dataset.assetId;

        try {
            const response = await fetch(`/barang/detail/${assetId}`);
            const result = await response.json();

            if (!result.success || !result.barang) {
                alert('Gagal mengambil detail aset untuk serah terima.');
                return;
            }

            const barang = result.barang;
            window.setCurrentBarangForSerahTerima(barang);

            const serahModal = document.getElementById('serahTerimaAsetModal');
            const serahForm = document.getElementById('serahTerimaAsetForm');
            const modalTitle = serahModal.querySelector('.morph-modal-title span');

            modalTitle.innerHTML = '<i class="fas fa-exchange-alt"></i> Serah Terima Aset';

            serahForm.reset();

            document.getElementById('serahTerimaTrackId').value = '';
            document.getElementById('serahTerimaAssetId').value = assetId;
            document.getElementById('serahTerimaSerialNumber').value = barang.serial_number;
            document.getElementById('serahTerimaInfoNamaAset').textContent = barang.merek;
            document.getElementById('serahTerimaInfoSN').textContent = barang.serial_number;
            document.getElementById('serahTerimaInfoPerusahaan').textContent = barang.perusahaan ? barang.perusahaan.nama_perusahaan : 'N/A';
            document.getElementById('serahTerimaTanggalAwal').value = new Date().toISOString().slice(0, 10);
            document.getElementById('serahTerimaTanggalAwal').readOnly = true;

            const perusahaanTujuanSelect = document.getElementById('serahTerimaPerusahaanTujuan');
            const currentPerusahaanId = barang.perusahaan_id.toString();

            Array.from(perusahaanTujuanSelect.options).forEach(option => {
                if (option.value === currentPerusahaanId) {
                    option.disabled = true;
                    option.style.display = 'none';
                } else {
                    option.disabled = false;
                    option.style.display = 'block';
                }
            });
            document.getElementById('perusahaanTujuanGroup').style.display = 'none';
            document.getElementById('noAssetBaruPreviewGroup').style.display = 'none';

            serahModal.style.display = 'flex';

        } catch (error) {
            console.error("Gagal membuka modal serah terima:", error);
            alert("Terjadi kesalahan saat membuka form serah terima.");
        }
    }

    function escapeHtml(unsafe) {
        if (typeof unsafe !== 'string') {
            return unsafe;
        }
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function openEditHistoryModal(trackId) {
        fetch(`/track/${trackId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const track = data.track;
                    const barang = data.barang;

                    const serahModal = document.getElementById('serahTerimaAsetModal');
                    const serahForm = document.getElementById('serahTerimaAsetForm');
                    const modalTitle = serahModal.querySelector('.morph-modal-title span');

                    modalTitle.innerHTML = '<i class="fas fa-edit"></i> Edit Riwayat Aset';

                    serahForm.reset();
                    document.getElementById('serahTerimaTrackId').value = track.id;
                    document.getElementById('serahTerimaAssetId').value = barang.id;
                    document.getElementById('serahTerimaSerialNumber').value = barang.serial_number;

                    document.getElementById('serahTerimaInfoNamaAset').textContent = barang.merek;
                    document.getElementById('serahTerimaInfoSN').textContent = barang.serial_number;
                    document.getElementById('serahTerimaInfoPerusahaan').textContent = barang.perusahaan.nama_perusahaan;

                    document.getElementById('serahTerimaTanggalAwal').value = track.tanggal_awal;
                    document.getElementById('serahTerimaTanggalAwal').readOnly = false;

                    document.getElementById('serahTerimaUser').value = track.username;
                    document.getElementById('serahTerimaStatus').value = track.status;
                    document.getElementById('serahTerimaKeterangan').value = track.keterangan;

                    document.getElementById('perusahaanTujuanGroup').style.display = 'none';

                    serahModal.style.display = 'flex';

                } else {
                    alert(data.message || 'Gagal mengambil data riwayat.');
                }
            });
    }

    function openUserHistoryModal(serialNumber, deviceName, company) {
        const historyModal = document.getElementById('userHistoryModal');
        const historyModalSerialNumberEl = document.getElementById('historyModalSerialNumber');
        const historyModalDeviceNameEl = document.getElementById('historyModalDeviceName');
        const historyModalCompanyEl = document.getElementById('historyModalCompany');
        const historyTableBodyEl = document.getElementById('userHistoryTableBody');
        const historyTableHeaderEl = document.getElementById('userHistoryTableHeader');

        const refreshHistory = () => openUserHistoryModal(serialNumber, deviceName, company);
        currentHistoryRefreshFunction = refreshHistory;

        historyModalDeviceNameEl.textContent = deviceName || 'Tidak Diketahui';
        historyModalSerialNumberEl.textContent = serialNumber || 'N/A';
        historyModalCompanyEl.textContent = company || 'N/A';
        historyTableBodyEl.innerHTML = `<tr><td colspan="6" style="padding:15px; text-align:center;">Memuat riwayat... <i class="fas fa-spinner fa-spin"></i></td></tr>`;

        let headerRow = `
                <tr>
                    <th class="cell-history-user">Pengguna</th>
                    <th class="cell-history-tgl-awal">Penyerahan</th>
                    <th class="cell-history-tgl-akhir">Pengembalian</th>
                    <th class="cell-history-status">Status</th>
                    <th class="cell-history-keterangan">Keterangan</th>`;

        if (IS_SUPER_ADMIN) {
            headerRow += `<th class="cell-history-aksi">Aksi</th>`;
        }
        headerRow += `</tr>`;
        historyTableHeaderEl.innerHTML = headerRow;

        historyModal.style.display = 'flex';

        fetch(`/history/user/${encodeURIComponent(serialNumber)}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.success && Array.isArray(data.history)) {
                    if (data.history.length > 0) {
                        let tableRowsHTML = '';
                        data.history.forEach(item => {
                            let actionButtonsCell = '';
                            if (IS_SUPER_ADMIN) {
                                actionButtonsCell = `
                                    <td class="cell-history-aksi">
                                        <div class="action-buttons-container">
                                            <button class="action-btn-table-history edit-btn-history" data-track-id="${item.id}" title="Edit Riwayat"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn-table-history remove-btn-history" data-track-id="${item.id}" title="Hapus Riwayat"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </td>`;
                            }

                            tableRowsHTML += `
                                <tr data-track-id="${item.id}">
                                    <td class="cell-history-user" title="${escapeHtml(item.username || '-')}">${escapeHtml(item.username || '-')}</td>
                                    <td class="cell-history-tgl-awal">${formatDate(item.tanggal_awal)}</td>
                                    <td class="cell-history-tgl-akhir">${formatDate(item.tanggal_ahir)}</td>
                                    <td class="cell-history-status" title="${escapeHtml(item.status || '-')}">${escapeHtml(item.status || '-')}</td>
                                    <td class="cell-history-keterangan" title="${escapeHtml(item.keterangan || '-')}">${escapeHtml(item.keterangan || '-')}</td>
                                    ${actionButtonsCell}
                                </tr>`;
                        });
                        historyTableBodyEl.innerHTML = tableRowsHTML;
                    } else {
                        historyTableBodyEl.innerHTML = `<tr><td colspan="6" style="padding:15px; text-align:center;">Tidak ada riwayat pengguna.</td></tr>`;
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching user history:', error);
                historyTableBodyEl.innerHTML = `<tr><td colspan="6" style="padding:15px; text-align:center; color:red;">Gagal memuat riwayat.</td></tr>`;
            });
    }


    let currentHistoryRefreshFunction = null;
    const productTableRowsContainer = document.getElementById('productTableRowsContainer');
    const paginationContainer = document.getElementById('realtimePaginationContainer');
    const filterPerusahaanSelect = document.getElementById('filter_perusahaan');
    const filterJenisBarangSelect = document.getElementById('filter_jenis_barang');
    const mainSearchInput = document.getElementById('mainSearchInput');
    const clearMainSearchBtn = document.getElementById('clearMainSearchBtn');
    const filterToggleButton = document.querySelector(".jsFilter");
    const filterMenu = document.querySelector(".filter-button-wrapper .filter-menu");
    const tableHeader = document.querySelector('.products-header');
    const tableBody = document.getElementById('productTableRowsContainer');
    const resetFilterInMenuBtn = document.getElementById('resetFilterInMenuBtn');

    const addAssetModal = document.getElementById('addAssetModal');
    const openAddAssetModalButton = document.getElementById('openAddAssetModalButton');
    const closeAddAssetModalBtn = document.getElementById('closeAddAssetModalBtn');
    const cancelAddAssetModalBtn = document.getElementById('cancelAddAssetModalBtn');
    const addAssetForm = document.getElementById('addAssetForm');
    const submitAddAssetBtn = document.getElementById('submitAddAssetBtn');

    const detailModalOverlayElement = document.getElementById('deviceInfoModal');
    const closeDetailModalButtonElement = document.getElementById('closeDetailModalButton');

    const serahTerimaAsetModalElement = document.getElementById('serahTerimaAsetModal');
    const closeSerahTerimaBtn = document.getElementById('closeSerahTerimaAsetModalButton');
    const cancelSerahTerimaBtn = document.getElementById('cancelSerahTerimaAsetModalBtn');
    const serahTerimaForm = document.getElementById('serahTerimaAsetForm');
    const submitSerahTerimaBtn = document.getElementById('submitSerahTerimaAsetBtn');
    const serahTerimaStatusSelect = document.getElementById('serahTerimaStatus');

    const userHistoryModalElement = document.getElementById('userHistoryModal');
    const closeUserHistoryBtn = document.getElementById('closeUserHistoryModalButton');

    const rowsPerPageSelect = document.getElementById('rows-per-page-select');
    const paginationInfoText = document.getElementById('pagination-info-text');
    const paginationButtonsContainer = document.getElementById('pagination-buttons-container');

    const modeSwitch = document.querySelector('.mode-switch');
    const burgerMenuButton = document.getElementById('burger-menu');
    const sidebarElement = document.querySelector('.sidebar');
    const mobileBurgerButton = document.getElementById('mobile-burger-menu');

    const exportExcelButton = document.getElementById('exportExcelBtn');

    const editAssetModal = document.getElementById('editAssetModal');
    const editAssetForm = document.getElementById('editAssetForm');
    const closeEditAssetModalBtn = document.getElementById('closeEditAssetModalBtn');
    const cancelEditAssetModalBtn = document.getElementById('cancelEditAssetModalBtn');
    const submitEditAssetBtn = document.getElementById('submitEditAssetBtn');

    let animationFrameId = null;
    let isFilterActive = false;
    let isPausedByUser = false;
    let scrollPosition = 0;
    const conveyorContainer = document.querySelector('.inventory-summary-container');
    const MOBILE_BREAKPOINT = 768;


    const closeEditAssetModal = () => {
        if (editAssetModal) editAssetModal.classList.remove('show');
    };

    if (closeEditAssetModalBtn) closeEditAssetModalBtn.addEventListener('click', closeEditAssetModal);
    if (cancelEditAssetModalBtn) cancelEditAssetModalBtn.addEventListener('click', closeEditAssetModal);
    setupSmartModalClosure(editAssetModal, closeEditAssetModal);
    setupDropdownCheckbox();

    if (productTableRowsContainer) {
        productTableRowsContainer.addEventListener('click', function(event) {
            const detailButton = event.target.closest('.detail-btn-table-js');
            const editButton = event.target.closest('.edit-btn-asset');
            const removeButton = event.target.closest('.remove-btn-asset');

            if (detailButton) {
                const barangId = detailButton.dataset.id;
                if (barangId) {
                    openDetailModal(barangId);
                }
                return;
            }

            if (editButton) {
                const assetId = editButton.dataset.id;
                fetch(`/barang/${assetId}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const barang = data.barang;
                            document.getElementById('edit_asset_id').value = barang.id;
                            document.getElementById('edit_perusahaan_id').value = barang.perusahaan_id;
                            document.getElementById('edit_jenis_barang_id').value = barang.jenis_barang_id;
                            document.getElementById('edit_no_asset').value = barang.no_asset;
                            document.getElementById('edit_merek').value = barang.merek;
                            document.getElementById('edit_kuantitas').value = barang.kuantitas;
                            document.getElementById('edit_tgl_pengadaan').value = barang.tgl_pengadaan;
                            document.getElementById('edit_serial_number').value = barang.serial_number;
                            document.getElementById('edit_lokasi').value = barang.lokasi;

                            editAssetForm.action = `/barang/${barang.id}`;
                            if (editAssetModal) editAssetModal.classList.add('show');
                        } else {
                            alert(data.message || 'Gagal mengambil data aset.');
                        }
                    }).catch(error => console.error('Error:', error));
                return;
            }

            if (removeButton) {
                const assetId = removeButton.dataset.id;
                if (confirm('Apakah Anda yakin ingin menghapus aset ini? Riwayat serah terima yang terkait juga akan dihapus.')) {
                    fetch(`/barang/${assetId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                performRealtimeSearch();
                            } else {
                                alert(data.message || 'Gagal menghapus aset.');
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghapus aset.');
                        });
                }
                return;
            }
        });
    }

    if (editAssetForm) {
        editAssetForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const actionUrl = this.action;

            submitEditAssetBtn.textContent = 'Menyimpan...';
            submitEditAssetBtn.disabled = true;

            fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json().then(data => ({
                    status: response.status,
                    body: data
                })))
                .then(({
                    status,
                    body
                }) => {
                    if (status === 200 && body.success) {
                        alert(body.message);
                        closeEditAssetModal();
                        performRealtimeSearch();
                    } else if (status === 422 && body.errors) {
                        displayValidationErrorsOnForm(body.errors, editAssetForm);
                    } else {
                        alert(body.message || 'Terjadi kesalahan.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan yang tidak terduga.');
                })
                .finally(() => {
                    submitEditAssetBtn.textContent = 'Simpan Perubahan';
                    submitEditAssetBtn.disabled = false;
                });
        });
    }

    function addDragAndWheelScroll() {
        const slider = document.querySelector('.inventory-summary-container');
        if (!slider) return;

        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            if (e.target.closest('a, button')) return;
            isDown = true;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
            pauseConveyor();
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });

        slider.addEventListener('wheel', (e) => {
            if (e.deltaX !== 0) {
                return;
            }
            if (slider.scrollWidth > slider.clientWidth) {
                e.preventDefault();
                slider.scrollLeft += e.deltaY;
            }
        }, {
            passive: false
        });
    }

    const updateFilterState = () => {
        const keyword = mainSearchInput ? mainSearchInput.value.trim() : '';
        const perusahaan = filterPerusahaanSelect ? filterPerusahaanSelect.value : '';
        const jenisBarangAktif = document.querySelectorAll('#filter_jenis_barang_container input[type="checkbox"]:checked').length > 0;
        isFilterActive = !!(keyword || perusahaan || jenisBarangAktif);
    };


    function manageConveyor() {
        if (animationFrameId) {
            cancelAnimationFrame(animationFrameId);
            animationFrameId = null;
        }

        const clones = conveyorContainer.querySelectorAll('.summary-box-clone');
        clones.forEach(clone => clone.remove());

        scrollPosition = conveyorContainer.scrollLeft;

        if (window.innerWidth <= MOBILE_BREAKPOINT || conveyorContainer.scrollWidth <= conveyorContainer.clientWidth) {
            return;
        }

        const originalItems = conveyorContainer.querySelectorAll('.summary-box:not(.summary-box-clone)');
        originalItems.forEach(item => {
            const clone = item.cloneNode(true);
            clone.classList.add('summary-box-clone');
            clone.setAttribute('aria-hidden', 'true');
            conveyorContainer.appendChild(clone);
        });

        const originalSetWidth = Array.from(originalItems).reduce((acc, item) => acc + item.offsetWidth + 16, 0) - 16;
        const scrollSpeed = 0.5;

        function animateScroll() {
            if (!isPausedByUser && !isFilterActive) {
                scrollPosition += scrollSpeed;
                if (scrollPosition >= originalSetWidth) {
                    scrollPosition -= originalSetWidth;
                }
                conveyorContainer.scrollLeft = scrollPosition;
            }
            animationFrameId = requestAnimationFrame(animateScroll);
        }
        animateScroll();
    }

    function pauseConveyor() {
        isPausedByUser = true;
    }

    function resumeConveyor() {
        if (conveyorContainer) {
            scrollPosition = conveyorContainer.scrollLeft;
        }
        isPausedByUser = false;
    }

    if (conveyorContainer) {
        conveyorContainer.addEventListener('mouseenter', pauseConveyor);
        conveyorContainer.addEventListener('mouseleave', resumeConveyor);
        conveyorContainer.addEventListener('touchstart', pauseConveyor, {
            passive: true
        });
        conveyorContainer.addEventListener('touchend', resumeConveyor);
    }

    function filterSummaryBoxes(visibleAssetTypes = []) {
        const conveyorContainer = document.querySelector('.inventory-summary-container');
        if (!conveyorContainer) return;

        const allOriginalBoxes = conveyorContainer.querySelectorAll('.summary-box:not(.summary-box-clone)');
        updateFilterState();
        if (!isFilterActive) {
            allOriginalBoxes.forEach(box => {
                box.classList.remove('is-hidden');
            });
            return;
        }

        const visibleTypesSet = new Set(visibleAssetTypes.map(type =>
            type.toLowerCase().replace(/ \/ /g, '-').replace(/ /g, '-')
        ));

        allOriginalBoxes.forEach(box => {
            if (visibleTypesSet.has(box.dataset.type)) {
                box.classList.remove('is-hidden');
            } else {
                box.classList.add('is-hidden');
            }
        });
    }

    let originalRowsHTML = '';
    let activeSortKey = null;
    let activeSortDirection = 'none';

    let currentSortBy = 'no';
    let currentSortDirection = 'asc';

    if (productTableRowsContainer) {
        productTableRowsContainer.addEventListener('click', function(event) {
            const detailButton = event.target.closest('.detail-btn-table-js');
            if (detailButton) {
                const barangId = detailButton.dataset.id;
                if (barangId) {
                    openDetailModal(barangId);
                }
            }
        });
    }

    const userHistoryTableContainer = document.getElementById('userHistoryTableContainer');
    if (userHistoryTableContainer) {
        userHistoryTableContainer.addEventListener('click', function(event) {
            const editButton = event.target.closest('.edit-btn-history');
            const removeButton = event.target.closest('.remove-btn-history');

            if (editButton) {
                const trackId = editButton.dataset.trackId;
                openEditHistoryModal(trackId);
            }

            if (removeButton) {
                const trackId = removeButton.dataset.trackId;
                if (confirm('Anda yakin ingin menghapus riwayat ini? Tindakan ini tidak dapat diurungkan.')) {
                    fetch(`/track/${trackId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                if (currentHistoryRefreshFunction) {
                                    currentHistoryRefreshFunction();
                                }
                            } else {
                                alert(data.message || 'Gagal menghapus riwayat.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            }
        });
    }

    function setupAssetPreview() {
        const perusahaanSelect = document.getElementById('perusahaan_id');
        const jenisBarangSelect = document.getElementById('jenis_barang_id');
        const tglPengadaanInput = document.getElementById('tgl_pengadaan');
        const noAssetPreviewInput = document.getElementById('no_asset_preview');
        const noAssetWarning = document.getElementById('noAssetWarning');
        const addAssetForm = document.getElementById('addAssetForm');
        if (!perusahaanSelect || !jenisBarangSelect || !tglPengadaanInput || !noAssetPreviewInput || !noAssetWarning) {
            console.error("Satu atau lebih elemen form untuk preview tidak ditemukan. Periksa ID HTML.");
            return;
        }

        const updateNoAssetPreview = async () => {
            const perusahaanOption = perusahaanSelect.options[perusahaanSelect.selectedIndex];
            const jenisBarangOption = jenisBarangSelect.options[jenisBarangSelect.selectedIndex];
            const tglPengadaan = tglPengadaanInput.value;

            if (perusahaanOption.value && jenisBarangOption.value && tglPengadaan) {

                noAssetPreviewInput.value = 'Menghitung nomor seri...';
                if (noAssetWarning) noAssetWarning.style.display = 'none';

                try {
                    const perusahaanId = perusahaanOption.value;
                    const response = await fetch(`${window.PAGE_DATA.urls.nextSerial}/${perusahaanId}`);
                    const data = await response.json();

                    if (data.success) {
                        const nomorSeri = data.nomor_seri;
                        const singkatanPT = perusahaanOption.dataset.singkatan;
                        const singkatanJenis = jenisBarangOption.dataset.singkatan;

                        if (singkatanPT && singkatanJenis) {
                            const tahun = new Date(tglPengadaan).getFullYear();
                            const bulan = ('0' + (new Date(tglPengadaan).getMonth() + 1)).slice(-2);

                            const previewText = `${singkatanPT}/${singkatanJenis}/${tahun}/${bulan}/${nomorSeri}`;
                            noAssetPreviewInput.value = previewText;
                            if (noAssetWarning) noAssetWarning.style.display = 'block';
                        } else {
                            noAssetPreviewInput.value = 'Error: data singkatan tidak ditemukan.';
                        }

                    } else {
                        throw new Error(data.error || 'Gagal mengambil nomor seri dari API.');
                    }
                } catch (error) {
                    console.error("Error saat fetch nomor seri:", error);
                    noAssetPreviewInput.value = 'Gagal memuat nomor.';
                }
            } else {
                noAssetPreviewInput.value = '-- Pilih Perusahaan, Jenis, dan Tanggal --';
                if (noAssetWarning) noAssetWarning.style.display = 'none';
            }
        };

        perusahaanSelect.addEventListener('change', updateNoAssetPreview);
        jenisBarangSelect.addEventListener('change', updateNoAssetPreview);
        tglPengadaanInput.addEventListener('change', updateNoAssetPreview);
        if (openAddAssetModalButton) {
            openAddAssetModalButton.addEventListener('click', () => {
                if (addAssetForm) addAssetForm.reset();
                updateNoAssetPreview();
            });
        }
    }

    if (mobileBurgerButton && sidebarElement) {
        mobileBurgerButton.addEventListener('click', () => {
            sidebarElement.classList.toggle('mobile-open');
            document.body.classList.toggle('sidebar-open-overlay');
            mobileBurgerButton.classList.toggle('active');
        });
    }

    document.body.addEventListener('click', function(event) {
        if (event.target === document.body && document.body.classList.contains('sidebar-open-overlay')) {
            sidebarElement.classList.remove('mobile-open');
            document.body.classList.remove('sidebar-open-overlay');
            if (mobileBurgerButton) {
                mobileBurgerButton.classList.remove('active');
            }
        }
    });

    function setupDropdownCheckbox() {
        const container = document.getElementById('filter_jenis_barang_container');
        if (!container) return;

        const display = container.querySelector('.dropdown-checkbox-display');
        const optionsContainer = container.querySelector('.dropdown-checkbox-options');
        const checkboxes = container.querySelectorAll('input[type="checkbox"]');
        const displaySpan = display.querySelector('span');

        const updateDisplay = () => {
            const selected = Array.from(checkboxes).filter(cb => cb.checked);
            if (selected.length === 0) {
                displaySpan.textContent = 'Semua Jenis Barang';
            } else if (selected.length === 1) {
                displaySpan.textContent = selected[0].parentElement.querySelector('span').textContent;
            } else {
                displaySpan.textContent = `${selected.length} jenis dipilih`;
            }
        };

        display.addEventListener('click', (event) => {
            event.stopPropagation();
            container.classList.toggle('active');
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                updateDisplay();
                performRealtimeSearch(1);
                checkAndUpdateFilterStates();
            });
        });

        document.addEventListener('click', (event) => {
            if (!container.contains(event.target)) {
                container.classList.remove('active');
            }
        });

        window.resetJenisBarangFilter = () => {
            checkboxes.forEach(cb => cb.checked = false);
            updateDisplay();
        };

        updateDisplay();
    }

    function setupSmartModalClosure(modalElement, closeFunction) {
        if (!modalElement) return;

        let isMouseDownOnOverlay = false;

        modalElement.addEventListener('mousedown', function(event) {
            if (event.target === modalElement) {
                isMouseDownOnOverlay = true;
            }
        });

        modalElement.addEventListener('mouseup', function(event) {
            if (event.target === modalElement && isMouseDownOnOverlay) {
                closeFunction(modalElement);
            }
            isMouseDownOnOverlay = false;
        });

        modalElement.addEventListener('mouseleave', function() {
            isMouseDownOnOverlay = false;
        });
    }

    const tglPengadaanInput = document.getElementById('tgl_pengadaan');
    if (tglPengadaanInput) {
        tglPengadaanInput.addEventListener('click', function(event) {
            try {
                this.showPicker();
            } catch (error) {
                console.error("Browser tidak mendukung showPicker():", error);
            }
        });
    }

    function renderPaginationControls(paginationData) {
        if (!paginationData || paginationData.total === 0) {
            paginationInfoText.innerHTML = 'Tidak ada hasil';
            paginationButtonsContainer.innerHTML = '';
            return;
        }

        paginationInfoText.innerHTML = `Menampilkan <b>${paginationData.first_item}</b> - <b>${paginationData.last_item}</b> dari <b>${paginationData.total}</b> hasil`;

        paginationButtonsContainer.innerHTML = '';

        const currentPage = paginationData.current_page;
        const lastPage = paginationData.last_page;

        let buttonsHTML = '';

        buttonsHTML += `<button class="pagination-btn" data-page="${currentPage - 1}" ${currentPage === 1 ? 'disabled' : ''}><</button>`;

        const pagesToShow = [];
        const pageRange = 2;

        for (let i = 1; i <= lastPage; i++) {
            if (i === 1 || i === lastPage || (i >= currentPage - pageRange && i <= currentPage + pageRange)) {
                pagesToShow.push(i);
            }
        }

        let lastShownPage = 0;
        pagesToShow.forEach(pageNumber => {
            if (lastShownPage > 0 && pageNumber > lastShownPage + 1) {
                buttonsHTML += `<span class="pagination-ellipsis">...</span>`;
            }
            buttonsHTML += `<button class="pagination-btn ${pageNumber === currentPage ? 'active' : ''}" data-page="${pageNumber}">${pageNumber}</button>`;
            lastShownPage = pageNumber;
        });

        buttonsHTML += `<button class="pagination-btn" data-page="${currentPage + 1}" ${currentPage === lastPage ? 'disabled' : ''}>></button>`;

        paginationButtonsContainer.innerHTML = buttonsHTML;
    }

    function setupSerahTerimaPreview() {
        const serahTerimaStatusSelect = document.getElementById('serahTerimaStatus');
        const perusahaanTujuanGroup = document.getElementById('perusahaanTujuanGroup');
        const perusahaanTujuanSelect = document.getElementById('serahTerimaPerusahaanTujuan');
        const noAssetBaruPreviewGroup = document.getElementById('noAssetBaruPreviewGroup');
        const noAssetBaruPreviewInput = document.getElementById('no_asset_baru_preview');
        const noAssetBaruWarning = document.getElementById('noAssetBaruWarning');
        let currentBarangData = null;

        const updateNoAssetBaruPreview = async () => {
            const perusahaanTujuanOption = perusahaanTujuanSelect.options[perusahaanTujuanSelect.selectedIndex];

            if (!currentBarangData || !perusahaanTujuanOption.value) {
                noAssetBaruPreviewInput.value = '-- Pilih Perusahaan Tujuan --';
                noAssetBaruWarning.style.display = 'none';
                return;
            }

            noAssetBaruPreviewInput.value = 'Menghitung nomor seri...';
            try {
                const perusahaanId = perusahaanTujuanOption.value;
                const response = await fetch(`${window.PAGE_DATA.urls.nextSerial}/${perusahaanId}`);
                const data = await response.json();

                if (data.success) {
                    const nomorSeri = data.nomor_seri;
                    const singkatanPT = perusahaanTujuanOption.dataset.singkatan;
                    const singkatanJenis = currentBarangData.jenis_barang.singkatan;
                    const tahun = new Date().getFullYear();
                    const bulan = ('0' + (new Date().getMonth() + 1)).slice(-2);

                    const previewText = `${singkatanPT}/${singkatanJenis}/${tahun}/${bulan}/${nomorSeri}`;
                    noAssetBaruPreviewInput.value = previewText;
                    noAssetBaruWarning.style.display = 'block';
                } else {
                    throw new Error('Gagal mendapatkan nomor seri.');
                }
            } catch (error) {
                noAssetBaruPreviewInput.value = 'Gagal memuat nomor.';
            }
        };

        serahTerimaStatusSelect.addEventListener('change', () => {
            if (serahTerimaStatusSelect.value === 'dipindah') {
                perusahaanTujuanGroup.style.display = 'block';
                noAssetBaruPreviewGroup.style.display = 'block';
            } else {
                perusahaanTujuanGroup.style.display = 'none';
                noAssetBaruPreviewGroup.style.display = 'none';
                perusahaanTujuanSelect.value = '';
            }
        });

        perusahaanTujuanSelect.addEventListener('change', updateNoAssetBaruPreview);
        window.setCurrentBarangForSerahTerima = (barang) => {
            currentBarangData = barang;
        };
    }

    function updateHeaderArrows() {
        if (!tableHeader) return;
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
    }

    if (tableHeader) {
        tableHeader.addEventListener('click', (e) => {
            const headerCell = e.target.closest('.sortable-header');
            if (!headerCell) return;

            const sortKey = headerCell.dataset.sortBy;

            if (sortKey === 'no') {
                if (activeSortKey === 'no' && activeSortDirection === 'desc') {
                    activeSortKey = null;
                    activeSortDirection = 'none';
                } else {
                    activeSortKey = 'no';
                    activeSortDirection = 'desc';
                }
            } else {
                if (activeSortKey !== sortKey) {
                    activeSortKey = sortKey;
                    activeSortDirection = 'asc';
                } else {
                    if (activeSortDirection === 'asc') {
                        activeSortDirection = 'desc';
                    } else {
                        activeSortKey = null;
                        activeSortDirection = 'none';
                    }
                }
            }
            performRealtimeSearch(1);
            updateHeaderArrows();
        });
    }

    if (exportExcelButton) {
        exportExcelButton.addEventListener('click', function() {
            const keyword = mainSearchInput ? mainSearchInput.value : '';
            const perusahaan = filterPerusahaanSelect ? filterPerusahaanSelect.value : '';
            const jenisBarang = filterJenisBarangSelect ? filterJenisBarangSelect.value : '';

            const params = new URLSearchParams();
            if (keyword) params.append('search_no_asset', keyword);
            if (perusahaan) params.append('filter_perusahaan', perusahaan);
            if (jenisBarang) params.append('filter_jenis_barang', jenisBarang);

            params.append('sort_by', activeSortKey);
            params.append('sort_direction', activeSortDirection);

            const exportUrl = `${window.PAGE_DATA.urls.export}?${params.toString()}`;
            window.location.href = exportUrl;
        });
    }

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

    if (rowsPerPageSelect) {
        rowsPerPageSelect.addEventListener('change', () => performRealtimeSearch(1));
    }
    if (paginationButtonsContainer) {
        paginationButtonsContainer.addEventListener('click', (event) => {
            const button = event.target.closest('.pagination-btn');
            if (button && !button.disabled && !button.classList.contains('active')) {
                performRealtimeSearch(button.dataset.page);
            }
        });
    }


    function closeModalElement(modalElement) {
        if (modalElement) {
            modalElement.classList.remove('show');
        }
    }

    function displayValidationErrorsOnForm(errors, formElement) {
        if (!formElement) return;
        formElement.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        formElement.querySelectorAll('.form-control.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        for (const field in errors) {
            let errorElement = formElement.querySelector(`#${field}_error`);
            let inputElement = formElement.querySelector(`#${field}`);

            if (!inputElement) {
                inputElement = formElement.querySelector(`[name="${field}"]`);
            }

            if (errorElement && inputElement) {
                errorElement.textContent = errors[field][0];
                inputElement.classList.add('is-invalid');
            } else {
                console.warn(`Elemen error untuk field '${field}' tidak ditemukan.`);
            }
        }
    }

    function performRealtimeSearch(page = 1) {
        updateFilterState();
        pauseConveyor();

        if (!productTableRowsContainer) return;
        const perPage = rowsPerPageSelect ? rowsPerPageSelect.value : 10;
        const keyword = mainSearchInput ? mainSearchInput.value : '';
        const perusahaan = filterPerusahaanSelect ? filterPerusahaanSelect.value : '';
        const jenisBarangCheckboxes = document.querySelectorAll('#filter_jenis_barang_container input[type="checkbox"]:checked');
        const jenisBarang = Array.from(jenisBarangCheckboxes).map(cb => cb.value);

        productTableRowsContainer.innerHTML = '<div class="products-row"><div class="product-cell" style="text-align:center; flex-basis:100%; padding: 40px;">Memuat data... <i class="fas fa-spinner fa-spin"></i></div></div>';
        if (paginationInfoText) paginationInfoText.innerHTML = '';
        if (paginationButtonsContainer) paginationButtonsContainer.innerHTML = '';

        const params = new URLSearchParams({
            search_no_asset: keyword,
            filter_perusahaan: perusahaan,
            page: page,
            per_page: perPage,
        });

        jenisBarang.forEach(id => params.append('filter_jenis_barang[]', id));

        if (activeSortKey && activeSortDirection !== 'none') {
            params.append('sort_by', activeSortKey);
            params.append('sort_direction', activeSortDirection);
        }

        fetch(`${window.PAGE_DATA.urls.searchRealtime}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(responseData => {
                let tableRowsHtml = '';
                let visibleAssetTypes = new Set();

                if (responseData.data && responseData.data.length > 0) {
                    responseData.data.forEach((barang) => {
                        if (barang.jenis_barang) {
                            visibleAssetTypes.add(barang.jenis_barang);
                        }
                        const rowNumber = barang.row_number;
                        let superAdminButtons = IS_SUPER_ADMIN ?
                            `<button class="action-btn-table edit-btn-asset" data-id="${barang.id}" title="Edit Aset"><i class="fas fa-edit"></i><span>Edit</span></button>
                                <button class="action-btn-table remove-btn-asset" data-id="${barang.id}" title="Hapus Aset"><i class="fas fa-trash-alt"></i><span>Hapus</span></button>` : '';

                        tableRowsHtml += `
                            <div class="products-row" data-id="${barang.id}">
                                <div class="product-cell cell-no">${rowNumber}</div>
                                <div class="product-cell cell-perusahaan" data-label="Perusahaan" title="${escapeHtml(barang.perusahaan_nama || 'N/A')}">${escapeHtml(barang.perusahaan_nama || 'N/A')}</div>
                                <div class="product-cell cell-jenis-barang" data-label="Jenis Barang" title="${escapeHtml(barang.jenis_barang || 'N/A')}">${escapeHtml(barang.jenis_barang || 'N/A')}</div>
                                <div class="product-cell cell-no-asset" data-label="No Asset" title="${escapeHtml(barang.no_asset || '')}">${escapeHtml(barang.no_asset || '')}</div>
                                <div class="product-cell cell-merek" data-label="Merek" title="${escapeHtml(barang.merek || '')}">${escapeHtml(barang.merek || '')}</div>
                                <div class="product-cell cell-tgl-pengadaan" data-label="Tgl. Pengadaan">${formatDate(barang.tgl_pengadaan)}</div>
                                <div class="product-cell cell-serial-number" data-label="Serial Number" title="${escapeHtml(barang.serial_number || '')}">${escapeHtml(barang.serial_number || '')}</div>
                                <div class="product-cell cell-lokasi" data-label="Lokasi">${escapeHtml(barang.lokasi || 'N/A')}</div>
                                <div class="product-cell cell-aksi">
                                    <button class="action-btn-table detail-btn-table-js" data-id="${barang.id}" title="Detail Aset"><i class="fas fa-info-circle"></i><span>Detail</span></button>
                                    ${superAdminButtons}
                                </div>
                            </div>`;
                    });
                } else {
                    tableRowsHtml = `<div class="products-row"><div class="product-cell" style="text-align:center; flex-basis:100%; padding: 20px;">Tidak ada data aset ditemukan.</div></div>`;
                }
                productTableRowsContainer.innerHTML = tableRowsHtml;

                filterSummaryBoxes(Array.from(visibleAssetTypes));

                if (responseData.pagination) renderPaginationControls(responseData.pagination);
                if (responseData.inventorySummary) updateInventorySummary(responseData.inventorySummary);
            })
            .catch(error => {
                console.error('Error performing real-time search:', error);
                productTableRowsContainer.innerHTML = `<div class="products-row"><div class="product-cell" style="text-align:center; flex-basis:100%; padding: 20px; color:red;">Gagal memuat data.</div></div>`;
            })
            .finally(() => {
                updateFilterState();
                manageConveyor();
                if (!isFilterActive) {
                    resumeConveyor();
                } else {
                    pauseConveyor();
                }
            });
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function updateInventorySummary(summaryData) {
        if (!summaryData) return;

        document.querySelectorAll('.summary-box .summary-box-count').forEach(el => {
            el.textContent = '0';
        });

        for (const namaJenis in summaryData) {
            const data = summaryData[namaJenis];
            const slug = namaJenis.toLowerCase()
                .replace(/ \/ /g, '-')
                .replace(/ /g, '-');

            const matchingBoxes = document.querySelectorAll(`.summary-box[data-type="${slug}"]`);

            if (matchingBoxes.length > 0) {
                matchingBoxes.forEach(box => {
                    const countElement = box.querySelector('.summary-box-count');
                    if (countElement) {
                        countElement.textContent = data.count;
                    }
                });
            }
        }
    }

    function checkAndUpdateFilterStates() {
        if (!filterPerusahaanSelect || !resetFilterInMenuBtn) return;
        const perusahaanAktif = filterPerusahaanSelect.value !== '';
        const jenisBarangAktif = document.querySelectorAll('#filter_jenis_barang_container input[type="checkbox"]:checked').length > 0;
        const isAnyFilterActive = perusahaanAktif || jenisBarangAktif;
        resetFilterInMenuBtn.disabled = !isAnyFilterActive;
    }

    performRealtimeSearch(1);
    checkAndUpdateFilterStates();
    setupAssetPreview();
    setupSerahTerimaPreview();

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
            checkAndUpdateFilterStates();
        });
    }
    if (filterJenisBarangSelect) {
        filterJenisBarangSelect.addEventListener('change', () => {
            performRealtimeSearch(1);
            checkAndUpdateFilterStates();
        });
    }

    if (resetFilterInMenuBtn) {
        resetFilterInMenuBtn.addEventListener('click', () => {
            if (filterPerusahaanSelect) filterPerusahaanSelect.value = '';
            if (window.resetJenisBarangFilter) {
                window.resetJenisBarangFilter();
            }
            if (mainSearchInput) mainSearchInput.value = '';

            performRealtimeSearch(1);
            checkAndUpdateFilterStates();

            if (filterMenu && filterMenu.classList.contains('active')) {
                filterMenu.classList.remove('active');
            }
        });
    }

    if (openAddAssetModalButton && addAssetModal) {
        openAddAssetModalButton.addEventListener('click', () => openModalElement(addAssetModal, addAssetForm));
    }

    if (addAssetForm && submitAddAssetBtn) {
        addAssetForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(addAssetForm);
            submitAddAssetBtn.textContent = 'Menyimpan...';
            submitAddAssetBtn.disabled = true;

            fetch(window.PAGE_DATA.urls.storeBarang, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success || (data.status && data.status === 201)) {
                        alert(data.message || data.success || 'Aset berhasil ditambahkan.');
                        closeModalElement(addAssetModal);
                        performRealtimeSearch();
                    } else if (data.errors) {
                        displayValidationErrorsOnForm(data.errors, addAssetForm);
                    } else {
                        alert(data.error || data.message || 'Gagal menambahkan aset.');
                    }
                })
                .catch(err => {
                    console.error("Error add asset:", err);
                    alert('Terjadi kesalahan. Cek konsol.');
                    if (err.body && err.body.errors) {
                        displayValidationErrorsOnForm(err.body.errors, addAssetForm);
                    }
                })
                .finally(() => {
                    submitAddAssetBtn.textContent = 'Simpan Aset';
                    submitAddAssetBtn.disabled = false;
                });
        });
    }

    const closeModalAddAsset = () => closeModalElement(addAssetModal);
    if (openAddAssetModalButton) openAddAssetModalButton.addEventListener('click', () => openModalElement(addAssetModal, addAssetForm));
    if (closeAddAssetModalBtn) closeAddAssetModalBtn.addEventListener('click', () => closeModalElement(addAssetModal));
    if (cancelAddAssetModalBtn) cancelAddAssetModalBtn.addEventListener('click', () => closeModalElement(addAssetModal));
    setupSmartModalClosure(addAssetModal, () => closeModalElement(addAssetModal));

    const closeDetailModal = () => {
        if (detailModalOverlayElement) detailModalOverlayElement.style.display = 'none';
        currentDetailModalAssetId = null;
    };
    if (closeDetailModalButtonElement) closeDetailModalButtonElement.addEventListener('click', closeDetailModal);
    setupSmartModalClosure(detailModalOverlayElement, closeDetailModal);

    const closeSerahTerimaModal = () => {
        if (serahTerimaAsetModalElement) serahTerimaAsetModalElement.style.display = 'none';
    };
    if (cancelSerahTerimaBtn) cancelSerahTerimaBtn.addEventListener('click', closeSerahTerimaModal);
    setupSmartModalClosure(serahTerimaAsetModalElement, closeSerahTerimaModal);

    const closeUserHistoryModal = () => {
        if (userHistoryModalElement) userHistoryModalElement.style.display = 'none';
    };
    if (closeUserHistoryBtn) closeUserHistoryBtn.addEventListener('click', closeUserHistoryModal);
    setupSmartModalClosure(userHistoryModalElement, closeUserHistoryModal);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (addAssetModal.classList.contains('show')) closeModalElement(addAssetModal);
            if (detailModalOverlayElement.style.display === 'flex') closeDetailModal();
            if (serahTerimaAsetModalElement.style.display === 'flex') closeSerahTerimaModal();
            if (userHistoryModalElement.style.display === 'flex') closeUserHistoryModal();
        }
    });

    setupSmartModalClosure(serahTerimaAsetModalElement, (el) => el.style.display = 'none');
    if (serahTerimaStatusSelect) {
        serahTerimaStatusSelect.addEventListener('change', handleSerahTerimaStatusChange);
    }
    if (closeSerahTerimaBtn) closeSerahTerimaBtn.addEventListener('click', () => {
        if (serahTerimaAsetModalElement) serahTerimaAsetModalElement.style.display = 'none';
    });
    if (cancelSerahTerimaBtn) cancelSerahTerimaBtn.addEventListener('click', () => {
        if (serahTerimaAsetModalElement) serahTerimaAsetModalElement.style.display = 'none';
    });

    if (serahTerimaForm && submitSerahTerimaBtn) {
        serahTerimaForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(serahTerimaForm);
            const trackId = formData.get('track_id');

            let url = window.PAGE_DATA.urls.serahTerimaStore;
            let method = 'POST';

            if (trackId) {
                url = `/track/${trackId}`;
                formData.append('_method', 'PUT');
            }

            submitSerahTerimaBtn.textContent = 'Menyimpan...';
            submitSerahTerimaBtn.disabled = true;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        closeSerahTerimaModal();

                        if (currentDetailModalAssetId) {
                            openDetailModal(currentDetailModalAssetId);
                        }

                        if (currentHistoryRefreshFunction) {
                            currentHistoryRefreshFunction();
                        }
                        performRealtimeSearch();
                    } else if (data.errors) {
                        displayValidationErrorsOnForm(data.errors, serahTerimaForm);
                    } else {
                        alert(data.message || 'Gagal menyimpan data.');
                    }
                })
                .catch(err => console.error("Error serah terima:", err))
                .finally(() => {
                    submitSerahTerimaBtn.textContent = 'Simpan Perubahan';
                    submitSerahTerimaBtn.disabled = false;
                });
        });
    }

    if (closeUserHistoryBtn) closeUserHistoryBtn.addEventListener('click', () => {
        if (userHistoryModalElement) userHistoryModalElement.style.display = 'none';
    });

    if (filterToggleButton && filterMenu) {
        filterToggleButton.addEventListener("click", function(event) {
            event.stopPropagation();
            filterMenu.classList.toggle("active");
        });
    }

    if (filterMenu && filterToggleButton) {
        document.addEventListener('click', function(event) {
            const isClickInsideFilterMenu = filterMenu.contains(event.target);
            const isClickOnFilterToggleButton = filterToggleButton.contains(event.target);

            if (filterMenu.classList.contains('active') && !isClickInsideFilterMenu && !isClickOnFilterToggleButton) {
                filterMenu.classList.remove('active');
            }
        });
    }

    if (modeSwitch) {
        const applyTheme = () => {
            const currentTheme = localStorage.getItem('theme');
            if (currentTheme === 'light') {
                document.documentElement.classList.add('light');
                modeSwitch.classList.add('active');
            } else {
                document.documentElement.classList.remove('light');
                modeSwitch.classList.remove('active');
            }
        };

        modeSwitch.addEventListener('click', () => {
            document.documentElement.classList.toggle('light');
            modeSwitch.classList.toggle('active');
            const theme = document.documentElement.classList.contains('light') ? 'light' : 'dark';
            localStorage.setItem('theme', theme);
        });

        applyTheme();
    }
    const SIDEBAR_COLLAPSED_KEY = 'sidebarCollapsedITventory';
    function initializeSidebarState() {
        if (!burgerMenuButton || !sidebarElement) return;

        if (window.innerWidth > 1024) {
            const isCollapsed = localStorage.getItem(SIDEBAR_COLLAPSED_KEY) === 'true';
            if (isCollapsed) {
                sidebarElement.classList.add('collapsed');
                burgerMenuButton.classList.remove('active');
            } else {
                sidebarElement.classList.remove('collapsed');
                burgerMenuButton.classList.add('active');
            }
        } else {
            sidebarElement.classList.remove('mobile-open', 'collapsed');
            document.body.classList.remove('sidebar-open-overlay');
            burgerMenuButton.classList.remove('active');
        }
    }
    if (burgerMenuButton && sidebarElement) {
        burgerMenuButton.addEventListener('click', () => {
            if (window.innerWidth <= 1024) {
                sidebarElement.classList.toggle('mobile-open');
                document.body.classList.toggle('sidebar-open-overlay');
            } else {
                sidebarElement.classList.toggle('collapsed');
                localStorage.setItem(SIDEBAR_COLLAPSED_KEY, sidebarElement.classList.contains('collapsed'));
            }
            burgerMenuButton.classList.toggle('active');
        });

        document.body.addEventListener('click', function(event) {
            if (event.target === document.body && document.body.classList.contains('sidebar-open-overlay')) {
                sidebarElement.classList.remove('mobile-open');
                document.body.classList.remove('sidebar-open-overlay');
                burgerMenuButton.classList.remove('active');
            }
        });
    }

    function setActiveSidebarLink() {
        const currentPathname = window.location.pathname;
        const sidebarNavItems = document.querySelectorAll('.sidebar .sidebar-list-item');
        let exactMatchFound = false;

        sidebarNavItems.forEach(item => {
            item.classList.remove('active');
        });
        if (!document.querySelector('.sidebar .sidebar-list-item.active')) {
            const dashboardLinkEl = document.querySelector(`.sidebar-list-item a[href="${window.PAGE_DATA.urls.dashboardIndex}"]`);
            if (dashboardLinkEl && (window.location.pathname === '/' || window.location.pathname === new URL(dashboardLinkEl.href).pathname)) {
                dashboardLinkEl.parentElement.classList.add('active');
            }
        }
    }

    manageConveyor();
    addDragAndWheelScroll();
    updateFilterState();
    initializeSidebarState();
    setActiveSidebarLink();

    window.addEventListener('resize', debounce(manageConveyor, 250));
});