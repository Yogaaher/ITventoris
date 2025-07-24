document.addEventListener('DOMContentLoaded', () => {

    const pageDataElement = document.body;
    const PAGE_DATA = JSON.parse(pageDataElement.dataset.pageData);
    const csrfToken = PAGE_DATA.csrfToken;

    const searchInput = document.getElementById('userSearchInput');
    const tableContainer = document.getElementById('productTableRowsContainer');
    const burgerMenuButton = document.getElementById('burger-menu');
    const sidebarElement = document.querySelector('.sidebar');
    const modeSwitch = document.querySelector('.mode-switch');
    let debounceTimer;

    const addUserModal = document.getElementById('addUserModal');
    const openAddUserBtn = document.getElementById('openAddUserModalButton');
    const closeAddUserBtn = document.getElementById('closeAddUserModalBtn');
    const cancelAddUserBtn = document.getElementById('cancelAddUserModalBtn');
    const addUserForm = document.getElementById('addUserForm');
    const submitAddUserBtn = document.getElementById('submitAddUserBtn');

    const editUserModal = document.getElementById('editUserModal');
    const closeEditUserBtn = document.getElementById('closeEditUserModalBtn');
    const cancelEditUserBtn = document.getElementById('cancelEditUserModalBtn');
    const editUserForm = document.getElementById('editUserForm');

    const rowsPerPageSelect = document.getElementById('rows-per-page-select');
    const paginationButtonsContainer = document.getElementById('pagination-buttons-container');
    const paginationInfoText = document.getElementById('pagination-info-text');

    function fetchUsers(page = 1) {
        if (!tableContainer) return;

        const query = searchInput.value;
        const perPage = rowsPerPageSelect.value;

        const params = new URLSearchParams({
            search: query,
            page: page,
            per_page: perPage
        });
        const url = `${PAGE_DATA.urls.fetch}?${params.toString()}`;

        tableContainer.innerHTML = `<div class="products-row"><div class="product-cell" style="text-align:center; flex-basis:100%; padding: 40px;">Memuat... <i class="fas fa-spinner fa-spin"></i></div></div>`;
        if (paginationButtonsContainer) paginationButtonsContainer.innerHTML = '';
        if (paginationInfoText) paginationInfoText.innerHTML = '';

        fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.ok ? response.json() : Promise.reject('Network response was not ok'))
            .then(data => {
                tableContainer.innerHTML = data.table_html || '<div class="products-row"><div class="product-cell" style="text-align:center;flex-basis:100%;">Tidak ada pengguna.</div></div>';
                if (data.pagination) {
                    renderPaginationControls(data.pagination);
                }
            })
            .catch(error => {
                console.error('Error fetching users:', error);
                tableContainer.innerHTML = `<div class="products-row"><div class="product-cell" style="text-align:center;flex-basis:100%;color:red;">Gagal memuat data.</div></div>`;
            });
    }

    function resetAddUserFormState() {
        if (!addUserForm) return;

        addUserForm.reset();
        addUserForm.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
        });
        addUserForm.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
    }

    function initializeSidebarState() {
        if (!sidebarElement) return;
        if (window.innerWidth > 1024) {
            const isCollapsed = localStorage.getItem('sidebarCollapsedITventory') === 'true';
            sidebarElement.classList.toggle('collapsed', isCollapsed);
            if (burgerMenuButton) burgerMenuButton.classList.toggle('active', !isCollapsed);
        }
    }

    function setupSmartModalClosure(modalElement, closeFunction) {
        if (!modalElement) return;
        let isMouseDownOnOverlay = false;
        modalElement.addEventListener('mousedown', (event) => {
            if (event.target === modalElement) isMouseDownOnOverlay = true;
        });
        modalElement.addEventListener('mouseup', (event) => {
            if (event.target === modalElement && isMouseDownOnOverlay) closeFunction();
            isMouseDownOnOverlay = false;
        });
        modalElement.addEventListener('mouseleave', () => isMouseDownOnOverlay = false);
    }

    function renderPaginationControls(paginationData) {
        if (!paginationInfoText || !paginationButtonsContainer) return;

        if (!paginationData || paginationData.total === 0) {
            paginationInfoText.innerHTML = 'Tidak ada hasil';
            paginationButtonsContainer.innerHTML = '';
            return;
        }

        paginationInfoText.innerHTML = `Menampilkan <b>${paginationData.from}</b> - <b>${paginationData.to}</b> dari <b>${paginationData.total}</b> hasil`;
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

    const closeEditModal = () => {
        closeModal(editUserModal);
    };
    if (closeEditUserBtn) closeEditUserBtn.addEventListener('click', closeEditModal);
    if (cancelEditUserBtn) cancelEditUserBtn.addEventListener('click', closeEditModal);

    tableContainer.addEventListener('click', function(event) {
        const editButton = event.target.closest('.edit-btn');
        if (!editButton) return;

        const userRow = editButton.closest('.products-row');
        const userId = userRow.dataset.userId;
        const currentUserRole = PAGE_DATA.isSuperAdmin ? 'super_admin' : 'admin';
        const currentUserId = PAGE_DATA.authUserId;

        fetch(PAGE_DATA.urls.edit.replace('{id}', userId))
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Gagal mengambil data user.');
                    });
                }
                return response.json();
            })
            .then(user => {
                const editForm = document.getElementById('editUserForm');
                editForm.reset();
                document.querySelectorAll('#editUserForm .invalid-feedback').forEach(el => el.textContent = '');
                document.querySelectorAll('#editUserForm .is-invalid').forEach(el => el.classList.remove('is-invalid'));

                editForm.querySelector('#edit_user_id').value = user.id;
                editForm.querySelector('#edit_name').value = user.name;
                editForm.querySelector('#edit_email').value = user.email;

                const roleSelect = editForm.querySelector('#edit_role');
                roleSelect.value = user.role;
                editForm.action = PAGE_DATA.urls.update.replace('{id}', user.id);

                if (currentUserRole === 'super_admin') {
                    roleSelect.disabled = (user.id == currentUserId);
                } else {
                    roleSelect.disabled = true;
                }

                openModal(editUserModal);
            })
            .catch(error => {
                console.error('Gagal mengambil data user:', error);
                alert(error.message);
            });
    });

    editUserForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const submitButton = document.getElementById('submitEditUserBtn');
        submitButton.disabled = true;
        submitButton.innerHTML = '<span>Menyimpan...</span>';

        const formData = new FormData(this);
        const actionUrl = this.action;
        const roleSelect = this.querySelector('#edit_role');

        if (roleSelect.disabled) {
            formData.set('role', roleSelect.value);
        }

        fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
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
                    alert(body.success);
                    closeEditModal();
                    fetchUsers(1);
                } else {
                    if (body.errors) {
                        Object.keys(body.errors).forEach(key => {
                            const errorEl = document.getElementById(`edit_${key}_error`);
                            if (errorEl) errorEl.textContent = body.errors[key][0];
                            const inputEl = document.getElementById(`edit_${key}`);
                            if (inputEl) inputEl.classList.add('is-invalid');
                        });
                    } else if (body.error) {
                        alert(body.error);
                    }
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-save"></i><span>Simpan Perubahan</span>';
            });
    });

    function openModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'flex';
            document.body.classList.add('modal-open');
        }
    }

    function closeModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'none';
            document.body.classList.remove('modal-open');
        }
    }

    function setupSmartModalClosure(modalElement, closeFunction) {
        if (!modalElement) return;
        let isMouseDownOnOverlay = false;
        modalElement.addEventListener('mousedown', (event) => {
            if (event.target === modalElement) isMouseDownOnOverlay = true;
        });
        modalElement.addEventListener('mouseup', (event) => {
            if (event.target === modalElement && isMouseDownOnOverlay) closeFunction();
            isMouseDownOnOverlay = false;
        });
        modalElement.addEventListener('mouseleave', () => {
            isMouseDownOnOverlay = false;
        });
    }

    const closeAddModal = () => {
        if (addUserModal) {
            closeModal(addUserModal);
            resetAddUserFormState();
        }
    };
    if (openAddUserBtn) {
        openAddUserBtn.addEventListener('click', () => {
            resetAddUserFormState();
            openModal(addUserModal);
        });
    }
    if (closeAddUserBtn) closeAddUserBtn.addEventListener('click', closeAddModal);
    if (cancelAddUserBtn) cancelAddUserBtn.addEventListener('click', closeAddModal);
    setupSmartModalClosure(addUserModal, closeAddModal);

    if (closeEditUserBtn) closeEditUserBtn.addEventListener('click', closeEditModal);
    if (cancelEditUserBtn) cancelEditUserBtn.addEventListener('click', closeEditModal);
    setupSmartModalClosure(editUserModal, closeEditModal);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (addUserModal && addUserModal.style.display === 'flex') closeAddModal();
            if (editUserModal && editUserModal.style.display === 'flex') closeEditModal();
        }
    });

    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fetchUsers(1), 300);
        });
    }

    if (rowsPerPageSelect) {
        rowsPerPageSelect.addEventListener('change', () => fetchUsers(1));
    }

    if (paginationButtonsContainer) {
        paginationButtonsContainer.addEventListener('click', (event) => {
            const button = event.target.closest('.pagination-btn');
            if (button && !button.disabled && !button.classList.contains('active')) {
                fetchUsers(button.dataset.page);
            }
        });
    }
    fetchUsers(1);

    if (tableContainer) {
        tableContainer.addEventListener('click', function(event) {
            const deleteButton = event.target.closest('.delete-btn');

            if (!deleteButton) return;
            const userRow = deleteButton.closest('.products-row');
            const userId = userRow.dataset.userId;
            if (!userId) {
                console.error("User ID tidak ditemukan pada baris tabel.");
                return;
            }
            if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                fetch(`/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
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
                            alert(body.success);
                            fetchUsers(1);
                        } else {
                            alert(body.error || 'Terjadi kesalahan.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal menghapus user. Cek konsol untuk detail.');
                    });
            }
        });
    }

    if (openAddUserBtn) {
        openAddUserBtn.addEventListener('click', () => {
            if (addUserForm) addUserForm.reset();
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
            if (addUserModal) addUserModal.style.display = 'flex';
        });
    }

    if (closeAddUserBtn) closeAddUserBtn.addEventListener('click', closeAddModal);
    if (cancelAddUserBtn) cancelAddUserBtn.addEventListener('click', closeAddModal);
    if (addUserForm) {
        addUserForm.addEventListener('submit', function(event) {
            event.preventDefault();
            if (submitAddUserBtn) {
                submitAddUserBtn.disabled = true;
                submitAddUserBtn.innerHTML = '<span>Menyimpan...</span>';
            }

            const formData = new FormData(this);

            fetch(PAGE_DATA.urls.store, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        closeAddModal();
                        fetchUsers(1);
                    } else if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const errorEl = document.getElementById(`${key}_error`);
                            const inputEl = document.getElementById(key);
                            if (errorEl) errorEl.textContent = data.errors[key][0];
                            if (inputEl) inputEl.classList.add('is-invalid');
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                })
                .finally(() => {
                    if (submitAddUserBtn) {
                        submitAddUserBtn.disabled = false;
                        submitAddUserBtn.innerHTML = '<i class="fas fa-save"></i><span>Simpan</span>';
                    }
                });
        });
    }

    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetInputId = this.dataset.target;
            const targetInput = document.getElementById(targetInputId);

            if (targetInput) {

                const type = targetInput.getAttribute('type') === 'password' ? 'text' : 'password';
                targetInput.setAttribute('type', type);

                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                }
            }
        });
    });

    const formInputs = document.querySelectorAll('#addUserForm input');
    let validationDebounceTimer;
    formInputs.forEach(input => {
        input.addEventListener('keyup', (e) => {
            clearTimeout(validationDebounceTimer);
            validationDebounceTimer = setTimeout(() => {
                validateSingleField(e.target);
            }, 500);
        });
    });

    function validateSingleField(inputElement) {
        const fieldName = inputElement.name;
        const form = inputElement.closest('form');
        const formData = new FormData(form);
        const dataToSend = {};
        formData.forEach((value, key) => {
            dataToSend[key] = value;
        });

        dataToSend.field_trigger = fieldName;

        fetch(PAGE_DATA.urls.validateField, {
                method: 'POST',
                body: JSON.stringify(dataToSend),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                formInputs.forEach(input => {
                    input.classList.remove('is-invalid');
                    const errorDiv = document.getElementById(`${input.name}_error`);
                    if (errorDiv) errorDiv.textContent = '';
                });

                if (status !== 200 && body.errors) {
                    Object.keys(body.errors).forEach(key => {
                        const input = document.getElementById(key);
                        const errorDiv = document.getElementById(`${key}_error`);
                        if (input) input.classList.add('is-invalid');
                        if (errorDiv) errorDiv.textContent = body.errors[key][0];
                    });
                }
            })
            .catch(error => console.error('Validation fetch error:', error));
    }

    const mobileBurgerButton = document.getElementById('mobile-burger-menu');

    if (burgerMenuButton && sidebarElement) {
        burgerMenuButton.addEventListener('click', () => {
            if (window.innerWidth <= 1024) {
                sidebarElement.classList.toggle('mobile-open');
                document.body.classList.toggle('sidebar-open-overlay');
            } else {
                sidebarElement.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsedITventory', sidebarElement.classList.contains('collapsed'));
            }
            burgerMenuButton.classList.toggle('active');
        });
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

    renderPaginationControls(PAGE_DATA.initialData); 
    fetchUsers(1); 
    initializeSidebarState(); 
});