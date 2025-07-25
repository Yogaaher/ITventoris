document.addEventListener("DOMContentLoaded", () => {
    const pageDataElement = document.body;
    const PAGE_DATA = JSON.parse(pageDataElement.dataset.pageData);
    const csrfToken = PAGE_DATA.csrfToken;
    const isSuperAdmin = PAGE_DATA.isSuperAdmin;
    const isAdmin = PAGE_DATA.isAdmin;

    $(document).ready(function () {
        $("#icon-picker-button")
            .iconpicker({
                iconset: "fontawesome5",
                input: "#icon",
                component: "#icon-picker-button",
            })
            .on("change", function (e) {
                if (e.icon) {
                    $("#icon").val(e.icon).trigger("change");
                }
            });
    });

    const config = {
        company: {
            fetchUrl: PAGE_DATA.urls.company.fetch,
            storeUrl: PAGE_DATA.urls.company.store,
            updateUrl: PAGE_DATA.urls.company.update,
            deleteUrl: PAGE_DATA.urls.company.delete,
            editUrl: PAGE_DATA.urls.company.edit,
            searchPlaceholder: "Cari nama atau singkatan perusahaan...",
            addButtonText: "Tambah Perusahaan",
            modalTitleAdd:
                '<i class="fas fa-building"></i> Tambah Perusahaan Baru',
            modalTitleEdit: '<i class="fas fa-edit"></i> Edit Perusahaan',
            modalNamaLabel: "Nama Lengkap Perusahaan",
            modalNamaPlaceholder: "PT NAMA PERUSAHAAN",
            modalSingkatanMaxlength: 3,
            formNamaField: "nama_perusahaan",
            rowsContainerId: "companyTableRowsContainer",
            paginationContainerId: "company-pagination-container",
            tableAreaId: "companyTableArea",
        },
        itemType: {
            fetchUrl: PAGE_DATA.urls.itemType.fetch,
            storeUrl: PAGE_DATA.urls.itemType.store,
            updateUrl: PAGE_DATA.urls.itemType.update,
            deleteUrl: PAGE_DATA.urls.itemType.delete,
            editUrl: PAGE_DATA.urls.itemType.edit,
            searchPlaceholder: "Cari nama atau singkatan jenis barang...",
            addButtonText: "Tambah Jenis Barang",
            modalTitleAdd:
                '<i class="fas fa-tags"></i> Tambah Jenis Barang Baru',
            modalTitleEdit: '<i class="fas fa-edit"></i> Edit Jenis Barang',
            modalNamaLabel: "Nama Jenis Barang",
            modalNamaPlaceholder: "Masukan Nama Jenis Barang",
            modalSingkatanMaxlength: 3,
            formNamaField: "nama_jenis",
            rowsContainerId: "itemTypeTableRowsContainer",
            paginationContainerId: "itemType-pagination-container",
            tableAreaId: "itemTypeTableArea",
        },
    };

    let currentTab = "company";
    let debounceTimer;

    const modal = document.getElementById("dataModal");
    const dataForm = document.getElementById("dataForm");
    const searchInput = document.getElementById("searchInput");
    const clearSearchBtn = document.getElementById("clearSearchBtn"); 

    const updateUIForTab = () => {
        const conf = config[currentTab];
        searchInput.placeholder = conf.searchPlaceholder;
        document.getElementById("addButtonText").textContent =
            conf.addButtonText;

        document.querySelectorAll(".nav-link").forEach((link) => {
            link.classList.toggle("active", link.dataset.tab === currentTab);
        });
        document.querySelectorAll(".tab-pane").forEach((pane) => {
            pane.classList.toggle(
                "active",
                pane.id === `${currentTab}-tab-pane`
            );
        });

        const openAddModalButton =
            document.getElementById("openAddModalButton");
        if (openAddModalButton) {
            openAddModalButton.style.display = isSuperAdmin ? "" : "none";
        }
    };

    const handleClearButtonVisibility = () => {
        if (searchInput.value.length > 0) {
            clearSearchBtn.style.display = 'block';
        } else {
            clearSearchBtn.style.display = 'none';
        }
    };

    const fetchData = (page = 1) => {
        const conf = config[currentTab];
        const search = searchInput.value;
        const perPageSelect = document.querySelector(
            `#${conf.tableAreaId} #rows-per-page-select`
        );
        const perPage = perPageSelect ? perPageSelect.value : 20;
        const url = `${
            conf.fetchUrl
        }?page=${page}&per_page=${perPage}&search=${search}&_=${new Date().getTime()}`;

        fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            },
        })
        .then(response => {
                if (!response.ok) {
                    return response.json().then((err) => {
                        throw new Error(
                            `HTTP error! Status: ${response.status}, Message: ${
                                err.message || "Unknown error"
                            }`
                        );
                    });
                }
                return response.json();
            })
            .then((data) => {
                if (data.table_html && data.pagination) {
                    document.getElementById(conf.rowsContainerId).innerHTML =
                        data.table_html;
                    document.getElementById(
                        conf.paginationContainerId
                    ).innerHTML = renderPaginationControls(data.pagination);
                } else {
                    console.error(
                        "Data table_html atau pagination tidak ditemukan dari server."
                    );
                }
            })
            .catch((error) => {
                alert(
                    `Gagal memuat data. Pesan Error: ${
                        error.message || "Lihat console untuk detail."
                    }`
                );
            });
    };

    const renderPaginationControls = (pagination) => {
        if (!pagination || pagination.total === 0) {
            return `
            <div class="footer-section footer-left">
                <div class="rows-per-page-wrapper"><label for="rows-per-page-select">Baris:</label><select id="rows-per-page-select"><option value="10">10</option><option value="20" selected>20</option><option value="30">30</option><option value="50">50</option></select></div>
            </div>
            <div class="footer-section footer-center"></div>
            <div class="footer-section footer-right"><div class="pagination-info"><b>0</b>-<b>0</b> dari <b>0</b></div></div>`;
        }
        const { from, to, total, per_page, links } = pagination;
        let linksHtml = links
            .map((link) => {
                let label = link.label
                    .replace("« Previous", "<")
                    .replace("Next »", ">");
                return `<button class="pagination-btn ${
                    link.active ? "active" : ""
                }" data-page="${
                    link.url ? new URL(link.url).searchParams.get("page") : ""
                }" ${!link.url ? "disabled" : ""}>${label}</button>`;
            })
            .join("");
        return `
        <div class="footer-section footer-left">
            <div class="rows-per-page-wrapper"><label for="rows-per-page-select">Baris:</label><select id="rows-per-page-select">${[
                10, 20, 30, 50,
            ]
                .map(
                    (v) =>
                        `<option value="${v}" ${
                            per_page == v ? "selected" : ""
                        }>${v}</option>`
                )
                .join("")}</select></div>
        </div>
        <div class="footer-section footer-center">${linksHtml}</div>
        <div class="footer-section footer-right"><div class="pagination-info"><b>${from}</b>-<b>${to}</b> dari <b>${total}</b></div></div>`;
    };

    const openModal = () => {
        modal.style.display = "flex";
        document.body.classList.add("modal-open");
    };
    const closeModal = () => {
        modal.style.display = "none";
        document.body.classList.remove("modal-open");
    };

    const resetFormAndErrors = () => {
        dataForm.reset();
        document.getElementById("icon-form-group").style.display = "none";
        dataForm
            .querySelectorAll(".is-invalid")
            .forEach((el) => el.classList.remove("is-invalid"));
        dataForm
            .querySelectorAll(".invalid-feedback")
            .forEach((el) => (el.textContent = ""));
    };

    const handleValidationErrors = (errors) => {
        dataForm
            .querySelectorAll(".is-invalid")
            .forEach((el) => el.classList.remove("is-invalid"));
        dataForm
            .querySelectorAll(".invalid-feedback")
            .forEach((el) => (el.textContent = ""));
        Object.keys(errors).forEach((key) => {
            const input = dataForm.querySelector(`[name="${key}"]`);
            if (input) {
                input.classList.add("is-invalid");
                const errorDiv = input.nextElementSibling;
                if (
                    errorDiv &&
                    errorDiv.classList.contains("invalid-feedback")
                ) {
                    errorDiv.textContent = errors[key][0];
                }
            }
        });
    };

    const setupSmartModalClosure = (modalElement, closeFunction) => {
        if (!modalElement) return;
        let isMouseDownOnOverlay = false;

        modalElement.addEventListener("mousedown", function (event) {
            if (event.target === modalElement) {
                isMouseDownOnOverlay = true;
            }
        });

        modalElement.addEventListener("mouseup", function (event) {
            if (event.target === modalElement && isMouseDownOnOverlay) {
                closeFunction();
            }
            isMouseDownOnOverlay = false;
        });

        modalElement.addEventListener("mouseleave", function () {
            isMouseDownOnOverlay = false;
        });
    };

    const setupModal = (id = null) => {
        const isEditMode = id !== null;
        const conf = config[currentTab];
        resetFormAndErrors();

        document.getElementById("modalTitle").innerHTML = isEditMode
            ? conf.modalTitleEdit
            : conf.modalTitleAdd;
        dataForm.action = isEditMode ? `${conf.updateUrl}/${id}` : conf.storeUrl;
        document.getElementById("formMethod").value = isEditMode
            ? "PUT"
            : "POST";
        document.getElementById("dataId").value = id || "";

        const namaInput = document.getElementById("nama");
        namaInput.name = conf.formNamaField;
        namaInput.placeholder = conf.modalNamaPlaceholder;
        document.getElementById("namaLabel").textContent = conf.modalNamaLabel;
        document.getElementById("singkatan").maxLength =
            conf.modalSingkatanMaxlength;

        const iconFormGroup = document.getElementById("icon-form-group");
        const isItemTypeTab = currentTab === "itemType";
        iconFormGroup.style.display = isItemTypeTab ? "flex" : "none";

        if (isEditMode) {
            fetch(conf.editUrl.replace("{id}", id))
                .then((res) => res.json())
                .then((data) => {
                    namaInput.value = data[conf.formNamaField];
                    document.getElementById("singkatan").value = data.singkatan;

                    if (isItemTypeTab) {
                        const selectedIcon =
                            data.icon || "fas fa-question-circle";
                        $("#icon").val(selectedIcon);
                        $("#icon-picker-button")
                            .find("i")
                            .attr(
                                "class",
                                "fas fa-fw " + selectedIcon.replace("fas ", "")
                            );
                    }

                    openModal();
                })
                .catch((err) => alert("Gagal memuat data untuk diedit."));
        } else {
            if (isItemTypeTab) {
                const defaultIcon = "fas fa-heart";
                $("#icon").val(defaultIcon);
                $("#icon-picker-button")
                    .find("i")
                    .attr(
                        "class",
                        "fas fa-fw " + defaultIcon.replace("fas ", "")
                    );
            }
            openModal();
        }
    };

    const handleDelete = (id) => {
        if (!confirm("Anda yakin ingin menghapus data ini?")) return;
        const conf = config[currentTab];
        fetch(conf.deleteUrl + id, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
            body: new URLSearchParams({
                _method: "DELETE",
            }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    alert(data.success);
                    fetchData(1);
                } else {
                    alert(data.error || "Gagal menghapus data.");
                }
            })
            .catch((err) => alert("Terjadi kesalahan fatal."));
    };

    document
        .getElementById("dataForm")
        .addEventListener("submit", function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById("submitBtn");
            submitBtn.disabled = true;
            submitBtn.querySelector("span").textContent = "Menyimpan...";

            const actionUrl = dataForm.getAttribute("action");
            const formData = new FormData(dataForm);
            const method = document.getElementById("formMethod").value;
            if (method.toUpperCase() !== "POST") {
                formData.append("_method", method);
            }

            fetch(actionUrl, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
                body: formData,
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((err) => {
                            throw {
                                status: response.status,
                                body: err,
                            };
                        });
                    }
                    return response.json().then((data) => ({
                        status: response.status,
                        body: data,
                    }));
                })
                .then(({ status, body }) => {
                    alert(body.success || "Aksi berhasil!");
                    closeModal();
                    const conf = config[currentTab];
                    const paginationContainer = document.getElementById(
                        conf.paginationContainerId
                    );
                    const activeButton = paginationContainer.querySelector(
                        ".pagination-btn.active"
                    );
                    const currentPage = activeButton
                        ? activeButton.dataset.page
                        : 1;
                    fetchData(currentPage);
                })
                .catch((err) => {
                    if (err.status === 422) {
                        handleValidationErrors(err.body.errors);
                    } else {
                        alert(
                            err.body.error ||
                                err.body.message ||
                                "Terjadi kesalahan server yang tidak diketahui."
                        );
                    }
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.querySelector("span").textContent = "Simpan";
                });
        });

    searchInput.addEventListener("keyup", () => {
        handleClearButtonVisibility();
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchData(1), 300);
    });

    clearSearchBtn.addEventListener('click', () => {
        searchInput.value = '';
        handleClearButtonVisibility();
        fetchData(1);
        searchInput.focus();
    });

    document.querySelector(".nav-tabs").addEventListener("click", (e) => {
        const navButton = e.target.closest(".nav-link");
        if (navButton) {
            currentTab = navButton.dataset.tab;
            updateUIForTab();
            const rowsContainer = document.getElementById(
                config[currentTab].rowsContainerId
            );
            if (rowsContainer.childElementCount === 0) {
                fetchData(1);
            }
        }
    });

    document
        .querySelector(".tab-content")
        .addEventListener("click", function (e) {
            const conf = config[currentTab];
            const target = e.target;

            const editBtn = target.closest(".edit-btn");
            if (editBtn) {
                e.preventDefault();
                setupModal(editBtn.dataset.id);
                return;
            }

            const deleteBtn = target.closest(".delete-btn");
            if (deleteBtn) {
                e.preventDefault();
                const id = deleteBtn.dataset.id;
                if (!confirm("Anda yakin ingin menghapus data ini?")) return;

                const url = `${conf.deleteUrl}/${id}`;

                fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: new URLSearchParams({ "_method": "DELETE" }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        const currentPage = document.querySelector(".pagination-btn.active")?.dataset.page || 1;
                        fetchData(currentPage);
                    } else {
                        alert(data.error || "Gagal menghapus data.");
                    }
                })
                .catch(err => alert("Terjadi kesalahan."));
                return;
            }
            const paginationBtn = target.closest(".pagination-btn");
            if (paginationBtn && !paginationBtn.disabled) {
                fetchData(paginationBtn.dataset.page);
                return;
            }
        });

    document.querySelector(".tab-content").addEventListener("change", (e) => {
        if (e.target.matches("#rows-per-page-select")) {
            fetchData(1);
        }
    });

    document
        .getElementById("openAddModalButton")
        .addEventListener("click", () => setupModal());
    modal
        .querySelectorAll('[data-dismiss="modal"]')
        .forEach((btn) => btn.addEventListener("click", closeModal));
    setupSmartModalClosure(modal, closeModal);
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            if (modal.style.display === "flex") {
                closeModal();
            }
        }
    });

    fetchData(1);
    const sidebarElement = document.querySelector(".sidebar");
    const burgerMenuButton = document.getElementById("burger-menu");
    const mobileBurgerButton = document.getElementById("mobile-burger-menu");
    const modeSwitch = document.querySelector(".mode-switch");

    const initializeSidebarState = () => {
        if (!sidebarElement || window.innerWidth <= 1024) return;
        const isCollapsed =
            localStorage.getItem("sidebarCollapsedITventory") === "true";
        sidebarElement.classList.toggle("collapsed", isCollapsed);
        if (burgerMenuButton)
            burgerMenuButton.classList.toggle("active", !isCollapsed);
    };
    const applyTheme = () => {
        const currentTheme = localStorage.getItem("theme");
        document.documentElement.classList.toggle(
            "light",
            currentTheme === "light"
        );
        if (modeSwitch)
            modeSwitch.classList.toggle("active", currentTheme === "light");
    };

    if (burgerMenuButton) {
        burgerMenuButton.addEventListener("click", () => {
            if (window.innerWidth > 1024) {
                sidebarElement.classList.toggle("collapsed");
                localStorage.setItem(
                    "sidebarCollapsedITventory",
                    sidebarElement.classList.contains("collapsed")
                );
            }
            burgerMenuButton.classList.toggle("active");
        });
    }
    if (mobileBurgerButton) {
        mobileBurgerButton.addEventListener("click", () => {
            sidebarElement.classList.toggle("mobile-open");
            document.body.classList.toggle("sidebar-open-overlay");
            mobileBurgerButton.classList.toggle("active");
        });
    }
    document.body.addEventListener("click", function (event) {
        if (
            event.target === document.body &&
            document.body.classList.contains("sidebar-open-overlay")
        ) {
            sidebarElement.classList.remove("mobile-open");
            document.body.classList.remove("sidebar-open-overlay");
            if (mobileBurgerButton)
                mobileBurgerButton.classList.remove("active");
        }
    });
    if (modeSwitch) {
        modeSwitch.addEventListener("click", () => {
            document.documentElement.classList.toggle("light");
            modeSwitch.classList.toggle("active");
            const theme = document.documentElement.classList.contains("light")
                ? "light"
                : "dark";
            localStorage.setItem("theme", theme);
        });
    }

    updateUIForTab();
    applyTheme();
    handleClearButtonVisibility();
    initializeSidebarState();
});
