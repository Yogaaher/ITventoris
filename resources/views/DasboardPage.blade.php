        <!DOCTYPE html>
        <html lang="id">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>ITventory - Dashboard</title>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
            <link rel="icon" href="{{ asset('img/Scuto-logo.svg') }}" type="image/x-icon">
            <style>
                /* === CSS MODAL TAMBAH ASET (DARI DASHBOARD ANDA) === */
                .modal {
                    display: none;
                    position: fixed;
                    z-index: 1050;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                    background-color: rgba(0, 0, 0, 0.5);
                    justify-content: center;
                    align-items: center;
                }

                .modal.show {
                    display: flex;
                }

                .modal-content {
                    background-color: var(--color-modal-background);
                    color: var(--color-text-light);
                    margin: auto;
                    border: none;
                    border-radius: 0.8rem;
                    width: 90%;
                    max-width: 60rem;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    padding: 2rem;
                }

                html.light .modal-content {
                    background-color: var(--color-modal-background);
                    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
                }

                .modal-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 0;
                    padding-bottom: 1rem;
                    border-bottom: 0.1rem solid var(--color-neutral-medium);
                    margin-bottom: 1.5rem;
                }

                html.light .modal-header {
                    border-bottom-color: #eee;
                }

                .modal-title {
                    font-size: 2.2rem;
                    font-weight: 500;
                    margin: 0;
                    display: flex;
                    align-items: center;
                    gap: 2rem;
                }

                .modal-title::before {
                    content: '\f0fe';
                    font-family: 'Font Awesome 5 Free';
                    font-weight: 900;
                    font-size: 2.0rem;
                }

                #addAssetModal .close-button {
                    background: none;
                    border: none;
                    color: var(--color-neutral-light);
                    font-size: 2.5rem;
                    cursor: pointer;
                    padding: 0.4rem;
                    line-height: 1;
                    transition: color 0.2s ease;
                }

                html.light #addAssetModal .close-button {
                    color: #888;
                }

                #addAssetModal .close-button:hover,
                #addAssetModal .close-button:focus {
                    color: var(--color-text-light);
                    text-decoration: none;
                }

                html.light #addAssetModal .close-button:hover {
                    color: #333;
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
                    background-color: var(--app-bg);
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
                    box-shadow: 0 0 0 0.2rem rgba(40, 105, 255, 0.25);
                }

                .modal-footer {
                    padding-top: 15px;
                    border-top: 1px solid var(--color-neutral-medium);
                    margin-top: 20px;
                    text-align: right;
                    display: flex;
                    justify-content: flex-end;
                    gap: 12px;
                }

                html.light .modal-footer {
                    border-top-color: #eee;
                }

                .modal-footer .btn {
                    padding: 10px 24px;
                    border-radius: 6px;
                    border: none;
                    cursor: pointer;
                    font-weight: 500;
                    font-size: 1.4rem;
                    transition: all 0.25s ease-in-out;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                }

                .modal-footer .btn-primary {
                    background-color: var(--action-color);
                    color: white;
                }

                .modal-footer .btn-primary:hover {
                    background-color: var(--action-color-hover);
                    transform: translateY(-3px);
                    box-shadow: 0 4px 12px rgba(40, 105, 255, 0.3);
                }

                .modal-footer .btn-primary::before {
                    content: '\f0c7';
                    font-family: 'Font Awesome 5 Free';
                    font-weight: 900;
                }

                .modal-footer .btn-secondary {
                    background-color: #4a5568;
                    color: #e2e8f0;
                }

                .modal-footer .btn-secondary:hover {
                    background-color: #dc3545;
                    color: white;
                    transform: translateY(-3px);
                    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
                }

                html.light .modal-footer .btn-secondary {
                    background-color: #4a5568;
                    color: #e2e8f0;
                }

                html.light .modal-footer .btn-secondary:hover {
                    background-color: #dc3545;
                    color: white;
                }

                .invalid-feedback {
                    color: #dc3545;
                    font-size: 0.875em;
                    margin-top: .25rem;
                }

                input.is-invalid,
                select.is-invalid {
                    border-color: #dc3545 !important;
                }

                /* === AKHIR CSS MODAL TAMBAH ASET === */

                /* === CSS UNTUK SUMMARY BOX INVENTARIS === */
                .inventory-summary-container {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 16px;
                    margin-bottom: 20px;
                    padding: 0 4px;
                }

                .summary-box {
                    background-color: var(--app-content-secondary-color);
                    color: var(--app-content-main-color);
                    padding: 20px 15px;
                    border-radius: 8px;
                    text-align: center;
                    flex: 1 1 150px;
                    min-width: 120px;
                    box-shadow: var(--filter-shadow);
                    transition: transform 0.2s ease-in-out;
                }

                .summary-box:hover {
                    transform: translateY(-10px);
                }

                html.light .summary-box {
                    background-color: #f9f9f9;
                    border: 1px solid #eee;
                }

                .summary-box-icon {
                    font-size: 2.8rem;
                    margin-bottom: 10px;
                    color: var(--action-color);
                }

                html.light .summary-box-icon {
                    color: var(--action-color);
                }

                .summary-box-type {
                    font-size: 1.4rem;
                    font-weight: 500;
                    margin-bottom: 8px;
                    text-transform: capitalize;
                }

                .summary-box-count {
                    font-size: 2.4rem;
                    font-weight: bold;
                }

                /* === AKHIR CSS UNTUK SUMMARY BOX INVENTARIS === */

                /* === CSS DASHBOARD (BAGIAN UTAMA) === */
                :root {
                    --app-bg: #101827;
                    --sidebar: rgba(21, 30, 47, 1);
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
                    --sidebar-width-expanded: 200px;
                    --sidebar-width-collapsed: 65px;
                    --color-neutral-light: #b0b5b8;
                    --color-neutral-medium: #545e61;
                    --color-modal-background: #394144;
                    --color-text-light: #fff;
                    --color-accent: #36d3b4;
                    --color-button-secondary: #6c757d;
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
                    --color-modal-background: #ffffff;
                    --color-text-light: #212529;
                    --color-neutral-light: #6c757d;
                    --color-neutral-medium: #495057;
                    --table-border: #e9ecef;
                }

                html {
                    box-sizing: border-box;
                    font-size: 62.5%;
                    scroll-behavior: smooth;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                }

                *,
                *::before,
                *::after {
                    box-sizing: inherit;
                    margin: 0;
                    padding: 0;
                }

                body {
                    margin: 0;
                    padding: 0;
                    overflow-y: auto;
                    overflow-x: hidden;
                    font-family: 'Poppins', sans-serif;
                    font-size: 1.6rem;
                    background-color: var(--app-bg);
                    color: var(--app-content-main-color);
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
                    box-shadow: var(--filter-shadow);
                    max-width: 2000px;
                    margin: 0 auto;
                }

                .sidebar {
                    flex-basis: var(--sidebar-width-expanded);
                    max-width: var(--sidebar-width-expanded);
                    min-width: var(--sidebar-width-expanded);
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
                    opacity: 0;
                    transform: scale(0);
                    width: auto;
                    overflow: hidden;
                    margin-right: 0;
                }

                .sidebar.collapsed .sidebar-header .app-icon svg {
                    opacity: 0;
                    transform: scale(0);
                    width: auto;
                    overflow: hidden;
                    margin-right: 0;
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
                    margin-bottom: 20px;
                    margin-top: 5px;
                }

                .app-icon {
                    color: var(--sidebar-main-color);
                    transition: opacity 0.3s ease, transform 0.3s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    width: auto;
                    overflow: hidden;
                    transition: opacity 0.2s ease-in-out 0.1s,
                        transform 0.2s ease-in-out 0.1s,
                        width 0.2s ease-in-out 0.1s;
                }

                .app-icon .app-logo-svg {
                    width: 30px;
                    height: 30px;
                    color: var(--sidebar-app-icon-color);
                    transition: color 0.3s;
                    flex-shrink: 0;
                }

                .app-icon svg {
                    width: 24px;
                    height: 24px;
                }

                .app-name-text {
                    font-size: 1.5rem;
                    font-weight: 800;
                    white-space: nowrap;
                    overflow: hidden;
                    opacity: 1;
                    transition: opacity 0.2s ease-in-out 0.1s, width 0.2s ease-in-out 0.1s;
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

                .sidebar-list-item svg {
                    margin-right: 8px;
                    flex-shrink: 0;
                    transition: margin-right 0.3s ease-in-out;
                }

                .sidebar-list-item:hover {
                    background-color: var(--sidebar-hover-link);
                }

                .sidebar-list-item.active {
                    background-color: var(--sidebar-active-link);
                }

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

                .app-content {
                    padding: 16px;
                    flex: 1;
                    max-height: 100%;
                    display: flex;
                    flex-direction: column;
                    overflow-x: hidden;
                    min-width: 0;
                }

                .app-content-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0 4px;
                }

                .app-content-headerText {
                    color: var(--app-content-main-color);
                    font-size: 30px;
                    line-height: 32px;
                    margin: 0;
                    padding-bottom: 20px;
                    padding-top: 10px;
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

                .app-content-header-actions-right {
                    display: flex;
                    align-items: center;
                }

                .app-content-header-actions-right .mode-switch.action-button {
                    margin-right: 8px;
                }

                .app-content-header-actions-right .mode-switch.action-button svg {
                    width: 18px;
                    height: 18px;
                }

                .app-content-headerButton:hover {
                    background-color: var(--action-color-hover);
                }

                .app-content-actions {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 16px 4px;
                    gap: 8px;
                }

                .app-content-actions-wrapper {
                    display: flex;
                    align-items: center;
                    margin-left: auto;
                }

                .app-content-actions .search-bar {
                    flex-grow: 1;
                    max-width: none;
                }

                .app-content-actions-buttons {
                    display: flex;
                    align-items: center;
                    flex-shrink: 0;
                    gap: 8px;
                }

                .app-content-actions .action-button.add-asset-btn {
                    background-color: var(--action-color) !important;
                    color: white;
                    border: none;
                }

                .app-content-actions-buttons .action-button {
                    min-width: 120px;
                    justify-content: center;
                }

                .app-content-actions-buttons .action-button i,
                .app-content-actions-buttons .action-button svg {
                    margin-right: 6px;
                }

                .action-button.add-asset-btn svg,
                .action-button.add-asset-btn i.fas {
                    margin-right: 6px !important;
                }

                .action-button.excel-btn {
                    background-color: #1D6F42 !important;
                    color: white;
                    border: none;
                }

                .action-button.excel-btn i.fas {
                    margin-right: 6px !important;
                }

                .action-button.excel-btn:hover {
                    background-color: #165934 !important;
                    border-color: #165934 !important;
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

                .search-bar-container {
                    position: relative;
                    display: flex;
                    align-items: center;
                    flex-grow: 1;
                }

                .search-bar-container .search-bar {
                    padding-right: 35px;
                    flex-grow: 1;
                    width: 100%;
                }

                .clear-search-btn {
                    position: absolute;
                    right: 10px;
                    top: 50%;
                    transform: translateY(-50%);
                    background-color: transparent;
                    border: none;
                    color: var(--app-content-main-color);
                    cursor: pointer;
                    font-size: 2rem;
                    padding: 2px 6px;
                    line-height: 1;
                }

                .clear-search-btn:hover {
                    color: red;
                }

                html.light .clear-search-btn {
                    color: #555;
                }

                html.light .search-bar {
                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%231f1c2e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-search'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E");
                }

                .search-bar::placeholder {
                    color: var(--app-content-main-color);
                }

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

                .action-button span {
                    margin-right: 4px;
                }

                .action-button:hover {
                    border-color: var(--action-color-hover);
                }

                .action-button:focus,
                .action-button.active {
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

                .filter-menu select option {
                    font-size: 14px;
                }

                html.light .filter-menu select {
                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%231f1c2e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-down'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
                }

                .filter-menu select:hover {
                    border-color: var(--action-color-hover);
                }

                .filter-menu select:focus,
                .filter-menu select.active {
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

                .filter-menu .filter-button {
                    border-radius: 2px;
                    font-size: 12px;
                    padding: 4px 8px;
                    cursor: pointer;
                    border: none;
                    color: #fff;
                    width: 100%;
                    box-sizing: border-box;
                }

                .filter-menu .filter-button.reset-filter-in-menu:not(:disabled) {
                    background-color: var(--action-color);
                    color: #ffff;
                }

                html.light .filter-menu .filter-button.reset-filter-in-menu:not(:disabled) {
                    background-color: #adb5bd;
                    color: #fff;
                }

                .filter-menu .filter-button.reset-filter-in-menu:disabled {
                    background-color: var(--filter-reset);
                    opacity: 0.5;
                    color: rgba(255, 255, 255, 0.6);
                    cursor: not-allowed;
                }

                html.light .filter-menu .filter-button.reset-filter-in-menu:disabled {
                    background-color: #adb5bd;
                    color: #6c757d;
                    opacity: 0.7;
                }

                .products-area-wrapper {
                    width: 100%;
                    max-height: 100%;
                    overflow: auto;
                    padding: 0 4px;
                }

                .products-area-wrapper.tableView {
                    display: flex;
                    flex-direction: column;
                }

                .tableView .products-header {
                    background-color: var(--app-content-secondary-color);
                    display: flex;
                    align-items: center;
                    border-radius: 4px;
                    position: sticky;
                    top: 0;
                    min-width: 1200px;
                    z-index: 10;
                    border-bottom: 2px solid var(--action-color);
                }

                html.light .tableView .products-header {
                    background-color: #f1f3f5;
                    border-bottom-color: #dee2e6;
                }

                .tableView #productTableRowsContainer .products-row:nth-child(even) {
                    background-color: var(--table-border);
                }

                html.light .tableView #productTableRowsContainer .products-row:nth-child(even) {
                    background-color: #f9f9f9;
                }

                .tableView #productTableRowsContainer .products-row:hover {
                    background-color: var(--sidebar-active-link);
                    transition: background-color 0.2s ease-in-out;
                }

                html.light .tableView #productTableRowsContainer .products-row:hover {
                    background-color: #e9ecef;
                }

                .products-header .sortable-header {
                    cursor: pointer;
                    user-select: none;
                    transition: color 0.2s ease-in-out;
                }

                .products-header .sortable-header:hover {
                    color: var(--action-color);
                }

                .products-header .sortable-header::after {
                    content: '';
                    display: inline-block;
                    width: 16px;
                    height: 16px;
                    margin-left: 8px;
                    opacity: 0.4;
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-position: center;
                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M8 9l4-4 4 4M16 15l-4 4-4-4'/%3E%3C/svg%3E");
                }

                html.light .products-header .sortable-header::after {
                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%231f1c2e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M8 9l4-4 4 4M16 15l-4 4-4-4'/%3E%3C/svg%3E");
                }

                .products-header .sortable-header.sorted-asc {
                    color: var(--action-color);
                }

                .products-header .sortable-header.sorted-asc::after {
                    opacity: 1;
                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%232869ff' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M12 19V5M5 12l7-7 7 7'/%3E%3C/svg%3E");
                }

                .products-header .sortable-header.sorted-desc {
                    color: var(--action-color);
                }

                .products-header .sortable-header.sorted-desc::after {
                    opacity: 1;
                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%232869ff' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M12 5v14M19 12l-7 7-7-7'/%3E%3C/svg%3E");
                }

                .tableView .products-row {
                    display: flex;
                    align-items: center;
                    border-radius: 4px;
                    min-width: 1210px;
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

                .tableView .product-cell.cell-no {
                    flex: 0 0 80px;
                    min-width: 80px;
                    justify-content: center;
                }

                .tableView .product-cell.cell-perusahaan {
                    flex: 1 1 210px;
                    min-width: 160px;
                }

                .tableView .product-cell.cell-jenis-barang {
                    flex: 1 1 120px;
                    min-width: 120px;
                }

                .tableView .product-cell.cell-no-asset {
                    flex: 1 1 180px;
                    min-width: 180px;
                }

                .tableView .product-cell.cell-merek {
                    flex: 1 1 200px;
                    min-width: 200px;
                }

                .tableView .product-cell.cell-tgl-pengadaan {
                    flex: 1 1 140px;
                    min-width: 140px;
                }

                .tableView .product-cell.cell-serial-number {
                    flex: 1 1 150px;
                    min-width: 150px;
                }

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

                .tableView .sort-button:hover {
                    color: var(--action-color);
                }

                .tableView .sort-button svg {
                    width: 12px;
                }

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

                .product-cell.cell-aksi .action-btn-table.detail-btn {
                    border-color: #5cb85c;
                    color: #5cb85c;
                }

                .product-cell.cell-aksi .action-btn-table.detail-btn:hover {
                    background-color: #5cb85c;
                    color: #fff;
                }

                /* === AKHIR CSS DASHBOARD === */

                /* === CSS SPESIFIK UNTUK MODAL DETAIL === */
                #deviceInfoModal.modal-overlay {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.6);
                    z-index: 1051;
                    justify-content: center;
                    align-items: center;
                    padding: 2rem;
                }

                #deviceInfoModal .modal-content-wrapper.device-entry-info-modal {
                    background-color: var(--color-modal-background);
                    border-radius: 1rem;
                    width: 65rem;
                    max-width: calc(100vw - 4rem);
                    max-height: calc(100vh - 4rem);
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                    color: var(--color-text-light);
                    position: relative;
                    z-index: 1052;
                }

                #deviceInfoModal .morph-modal-container {
                    display: flex;
                    flex-flow: column nowrap;
                    align-items: stretch;
                    padding: 2.5rem;
                    flex-grow: 1;
                    overflow: hidden;
                }

                #deviceInfoModal .morph-modal-title {
                    flex: 0 0 auto;
                    padding-bottom: 1.5rem;
                    border-bottom: 0.1rem solid var(--color-neutral-medium);
                    font-size: 2.2rem;
                    font-weight: 500;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 2rem;
                }

                #deviceInfoModal .morph-modal-title .fa {
                    margin-right: 1rem;
                }

                #deviceInfoModal .btn-close {
                    background: none;
                    border: none;
                    color: var(--color-neutral-light);
                    font-size: 2.8rem;
                    cursor: pointer;
                    padding: 0.5rem;
                    line-height: 1;
                }

                #deviceInfoModal .btn-close:hover {
                    color: var(--color-text-light);
                }

                #deviceInfoModal .morph-modal-body {
                    flex: 1 1 auto;
                    overflow-y: auto;
                    font-size: 1.5rem;
                }

                #deviceInfoModal .device-details-info-header {
                    display: flex;
                    flex-flow: row nowrap;
                    align-items: center;
                    padding-bottom: 1.5rem;
                    margin-bottom: 2.5rem;
                    border-bottom: 0.1rem solid var(--color-neutral-medium);
                }

                #deviceInfoModal .device-image {
                    margin-right: 2rem;
                    color: var(--color-accent);
                    font-size: 5rem;
                }

                #deviceInfoModal .device-label {
                    display: flex;
                    flex-direction: column;
                }

                #deviceInfoModal .device-name,
                #deviceInfoModal .device-online-status {
                    display: block;
                }

                #deviceInfoModal .device-name {
                    font-size: 2rem;
                    font-weight: 500;
                }

                #deviceInfoModal .device-online-status {
                    font-style: italic;
                    font-size: 1.4rem;
                    color: var(--color-neutral-light);
                }

                #deviceInfoModal .asset-detail-content .info-section {
                    margin-bottom: 2rem;
                }

                #deviceInfoModal .asset-detail-content .info-section:last-child {
                    margin-bottom: 0;
                }

                #deviceInfoModal .asset-detail-content .info-item {
                    display: flex;
                    margin-bottom: 1.2rem;
                    line-height: 1.6;
                }

                #deviceInfoModal .asset-detail-content .info-item dt {
                    font-weight: 600;
                    min-width: 160px;
                    padding-right: 1rem;
                    flex-shrink: 0;
                    color: inherit;
                }

                #deviceInfoModal .asset-detail-content .info-item dd {
                    color: var(--color-neutral-light);
                    word-break: break-word;
                    flex-grow: 1;
                }

                #deviceInfoModal .asset-detail-content hr.separator {
                    border: none;
                    border-top: 1px solid var(--color-neutral-medium);
                    margin: 2.5rem 0;
                }

                #deviceInfoModal .asset-detail-content .action-buttons {
                    display: flex;
                    gap: 1.5rem;
                    margin-top: 1.5rem;
                    margin-bottom: 2.5rem;
                }

                #deviceInfoModal .asset-detail-content .btn-action {
                    padding: 1rem 2rem;
                    font-size: 1.5rem;
                    border-radius: 0.5rem;
                    border: none;
                    cursor: pointer;
                    font-weight: 500;
                    transition: all 0.25s ease-in-out;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.8rem;
                }

                #deviceInfoModal .asset-detail-content .btn-action.btn-detail {
                    background-color: var(--color-accent);
                    color: var(--color-modal-background);
                }

                #deviceInfoModal .asset-detail-content .btn-action.btn-detail:hover {
                    background-color: #2cbda2;
                    transform: translateY(-3px);
                    box-shadow: 0 4px 12px rgba(54, 211, 180, 0.4);
                }

                #deviceInfoModal .asset-detail-content .btn-action.btn-update {
                    background-color: var(--color-button-secondary);
                    color: var(--color-text-light);
                }

                #deviceInfoModal .asset-detail-content .btn-action.btn-update:hover {
                    background-color: #5a6268;
                    transform: translateY(-3px);
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                }

                html.light #deviceInfoModal .asset-detail-content .btn-action.btn-update {
                    background-color: #f0f2f5;
                    color: #343a40;
                    border: 1px solid #dee2e6;
                }

                html.light #deviceInfoModal .asset-detail-content .btn-action.btn-update:hover {
                    background-color: #e2e6ea;
                    border-color: #ced4da;
                    color: #212529;
                    transform: translateY(-3px);
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                }

                #deviceInfoModal .asset-detail-content .keterangan-text {
                    white-space: pre-line;
                    line-height: 1.7;
                }

                /* === AKHIR CSS MODAL DETAIL === */

                /* === CSS TAMBAHAN UNTUK MODAL HISTORY USER === */
                #userHistoryModal.modal-overlay {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.7);
                    z-index: 1053;
                    justify-content: center;
                    align-items: center;
                    padding: 1.5rem;
                    overflow: auto;
                }

                #userHistoryModal .modal-content-wrapper.device-entry-info-modal {
                    background-color: var(--color-modal-background);
                    border-radius: 0.8rem;
                    width: 90%;
                    max-width: 60rem;
                    max-height: calc(100vh - 6rem);
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
                    color: var(--color-text-light);
                    position: relative;
                    z-index: 1054;
                }

                #userHistoryModal .morph-modal-container {
                    display: flex;
                    flex-flow: column nowrap;
                    align-items: stretch;
                    padding: 2rem;
                    flex-grow: 1;
                    overflow: hidden;
                }

                #userHistoryModal .morph-modal-title {
                    flex: 0 0 auto;
                    padding-bottom: 1rem;
                    border-bottom: 0.1rem solid var(--color-neutral-medium);
                    font-size: 1.8rem;
                    font-weight: 500;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 1.5rem;
                }

                #userHistoryModal .morph-modal-title .fa {
                    margin-right: 0.8rem;
                    font-size: 1.7rem;
                }

                #userHistoryModal .btn-close {
                    background: none;
                    border: none;
                    color: var(--color-neutral-light);
                    font-size: 2.5rem;
                    cursor: pointer;
                    padding: 0.4rem;
                    line-height: 1;
                }

                #userHistoryModal .btn-close:hover {
                    color: var(--color-text-light);
                }

                #userHistoryModal .morph-modal-body {
                    flex: 1 1 auto;
                    overflow-y: auto;
                    font-size: 1.4rem;
                }

                #userHistoryModal .morph-modal-body p:first-child {
                    margin-bottom: 1rem;
                    font-size: 1.3rem;
                }

                #userHistoryModal .morph-modal-body p:first-child strong {
                    font-weight: 600;
                }

                #userHistoryModal .user-history-table-wrapper {
                    max-height: 350px;
                    overflow-y: auto;
                    overflow-x: auto;
                    border: 1px solid var(--color-neutral-medium);
                    border-radius: 0.5rem;
                }

                #userHistoryModal .user-history-table {
                    width: 100%;
                    border-collapse: collapse;
                }

                #userHistoryModal .user-history-table th,
                #userHistoryModal .user-history-table td {
                    padding: 8px 12px;
                    border-bottom: 1px solid var(--table-border);
                    text-align: left;
                    vertical-align: top;
                    line-height: 1.5;
                }

                #userHistoryModal .user-history-table th {
                    font-weight: 600;
                    background-color: var(--color-modal-background);
                }

                #userHistoryModal .user-history-table .cell-history-user {
                    min-width: 170px;
                }

                #userHistoryModal .user-history-table .cell-history-tgl-awal,
                #userHistoryModal .user-history-table .cell-history-tgl-akhir {
                    min-width: 170px;
                    white-space: nowrap;
                }

                #userHistoryModal .user-history-table .cell-history-status {
                    min-width: 100px;
                    white-space: nowrap;
                }

                #userHistoryModal .user-history-table .cell-history-keterangan {
                    min-width: 200px;
                    white-space: normal;
                    word-break: break-word;
                }

                #userHistoryModal .user-history-table thead th {
                    position: sticky;
                    top: 0;
                    z-index: 1;
                }

                html.light #userHistoryModal .user-history-table thead th {
                    background-color: var(--color-modal-background);
                }

                html.light #userHistoryModal #userHistoryTableHeader {
                    background-color: var(--color-modal-background);
                }

                #userHistoryModal .history-info-list {
                    margin-bottom: 1.5rem;
                    font-size: 1.3rem;
                }

                #userHistoryModal .history-info-item {
                    display: flex;
                    margin-bottom: 0.6rem;
                    line-height: 1.5;
                }

                #userHistoryModal .history-info-item dt {
                    min-width: 120px;
                    flex-shrink: 0;
                    padding-right: 5px;
                    color: var(--color-neutral-light);
                    font-weight: 500;
                    position: relative;
                }

                #userHistoryModal .history-info-item dt::after {
                    content: ":";
                    position: absolute;
                    right: 0;
                }


                #userHistoryModal .history-info-item dd {
                    flex-grow: 1;
                    margin-left: 0;
                    word-break: break-word;
                    padding-left: 8px;
                }

                #userHistoryModal .history-info-item dd strong {
                    color: var(--color-text-light);
                    font-weight: 600;
                }

                /* === AKHIR CSS MODAL HISTORY USER === */


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

                /* === CSS TAMBAHAN UNTUK MODAL SERAH TERIMA ASET === */
                #serahTerimaAsetModal.modal-overlay {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.7);
                    z-index: 1055;
                    justify-content: center;
                    align-items: center;
                    padding: 1.5rem;
                    overflow: auto;
                }

                #serahTerimaAsetModal .modal-content-wrapper.device-entry-info-modal {
                    background-color: var(--color-modal-background);
                    border-radius: 0.8rem;
                    width: 90%;
                    max-width: 60rem;
                    max-height: calc(100vh - 6rem);
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
                    color: var(--color-text-light);
                    position: relative;
                    z-index: 1056;
                }

                #serahTerimaAsetModal .morph-modal-container {
                    display: flex;
                    flex-flow: column nowrap;
                    align-items: stretch;
                    padding: 2rem;
                    flex-grow: 1;
                    overflow: hidden;
                }

                #serahTerimaAsetModal .morph-modal-title {
                    flex: 0 0 auto;
                    padding-bottom: 1rem;
                    border-bottom: 0.1rem solid var(--color-neutral-medium);
                    font-size: 1.8rem;
                    font-weight: 500;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 1.5rem;
                }

                #serahTerimaAsetModal .morph-modal-title .fa {
                    margin-right: 0.8rem;
                    font-size: 1.7rem;
                }

                #serahTerimaAsetModal .btn-close {
                    background: none;
                    border: none;
                    color: var(--color-neutral-light);
                    font-size: 2.5rem;
                    cursor: pointer;
                    padding: 0.4rem;
                    line-height: 1;
                }

                #serahTerimaAsetModal .btn-close:hover {
                    color: var(--color-text-light);
                }

                #serahTerimaAsetModal .morph-modal-body {
                    flex: 1 1 auto;
                    overflow-y: auto;
                    font-size: 1.4rem;
                    padding-right: 1rem;
                }

                #serahTerimaAsetModal .morph-modal-body .form-group {
                    margin-bottom: 1.2rem;
                }

                #serahTerimaAsetModal .morph-modal-body label {
                    display: block;
                    margin-bottom: 0.6rem;
                    font-weight: 500;
                    color: var(--color-text-light);
                }

                #serahTerimaAsetModal .morph-modal-body input[type="date"],
                #serahTerimaAsetModal .morph-modal-body input[type="text"],
                #serahTerimaAsetModal .morph-modal-body select,
                #serahTerimaAsetModal .morph-modal-body textarea {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid var(--table-border);
                    border-radius: 4px;
                    background-color: var(--app-bg);
                    color: var(--app-content-main-color);
                    box-sizing: border-box;
                }

                html.light #serahTerimaAsetModal .morph-modal-body input[type="date"],
                html.light #serahTerimaAsetModal .morph-modal-body input[type="text"],
                html.light #serahTerimaAsetModal .morph-modal-body select,
                html.light #serahTerimaAsetModal .morph-modal-body textarea {
                    background-color: #fff;
                    border-color: #ccc;
                    color: #212529;
                }

                #serahTerimaAsetModal .morph-modal-body input[type="date"]:read-only {
                    background-color: var(--color-neutral-medium);
                    color: var(--color-neutral-light);
                    cursor: not-allowed;
                }

                html.light #serahTerimaAsetModal .morph-modal-body input[type="date"]:read-only {
                    background-color: #e9ecef;
                    color: #495057;
                }

                #serahTerimaAsetModal .morph-modal-body input:focus,
                #serahTerimaAsetModal .morph-modal-body select:focus,
                #serahTerimaAsetModal .morph-modal-body textarea:focus {
                    border-color: var(--action-color);
                    outline: none;
                    box-shadow: 0 0 0 0.2rem rgba(40, 105, 255, 0.25);
                }

                #serahTerimaAsetModal .morph-modal-body .invalid-feedback {
                    color: #dc3545;
                    font-size: 0.875em;
                    margin-top: .25rem;
                    display: block;
                }

                #serahTerimaAsetModal .info-section {
                    margin-bottom: 1.5rem;
                }

                #serahTerimaAsetModal .info-item {
                    display: flex;
                    margin-bottom: 0.8rem;
                    line-height: 1.5;
                }

                #serahTerimaAsetModal .info-item dt {
                    font-weight: 500;
                    min-width: 130px;
                    padding-right: 0.5rem;
                    flex-shrink: 0;
                    color: var(--color-neutral-light);
                    position: relative;
                }

                #serahTerimaAsetModal .info-item dt::after {
                    content: ":";
                    position: absolute;
                    right: 0;
                }

                #serahTerimaAsetModal .info-item dd {
                    color: var(--color-text-light);
                    word-break: break-word;
                    flex-grow: 1;
                    padding-left: 8px;
                }

                .auto-asset-preview {
                    background-color: #4a5568;
                    color: #a0aec0;
                    border: 1px dashed #718096;
                    text-align: center;
                    font-style: italic;
                    cursor: default;
                }

                html.light .auto-asset-preview {
                    background-color: #e9ecef;
                    color: #6c757d;
                    border-color: #ced4da;
                }

                .asset-preview-warning {
                    font-size: 1.3rem;
                    color: #f6e05e;
                    background-color: rgba(246, 224, 94, 0.1);
                    margin-top: 0.8rem;
                    padding: 0.8rem;
                    border-radius: 0.4rem;
                    text-align: center;
                    line-height: 1.5;
                }

                html.light .asset-preview-warning {
                    color: #856404;
                    background-color: #fff3cd;
                    border: 1px solid #ffeeba;
                }

                .date-input-container {
                    position: relative;
                }

                #tgl_pengadaan {
                    padding-right: 35px;
                    cursor: pointer;
                }

                .date-input-container::after {
                    content: '\f073';
                    font-family: 'Font Awesome 5 Free';
                    font-weight: 900;
                    position: absolute;
                    right: 15px;
                    top: 32%;
                    transform: translateY(-50%);
                    color: var(--color-neutral-light);
                    pointer-events: none;
                }

                #tgl_pengadaan::-webkit-calendar-picker-indicator {
                    display: none;
                    -webkit-appearance: none;
                }

                .table-footer-controls {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 16px 8px;
                    margin-top: 10px;
                    font-size: 1.4rem;
                    flex-wrap: wrap;
                    gap: 16px;
                }

                .table-footer-controls .footer-section {
                    display: flex;
                    align-items: center;
                }

                .table-footer-controls .footer-left {
                    justify-content: flex-start;
                    flex: 1;
                    min-width: 220px;
                }

                .table-footer-controls .rows-per-page-wrapper {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                .table-footer-controls .rows-per-page-wrapper label {
                    font-weight: 500;
                    white-space: nowrap;
                }

                .table-footer-controls #rows-per-page-select {
                    padding: 6px 12px;
                    border-radius: 4px;
                    border: 1px solid var(--table-border);
                    background-color: var(--app-bg);
                    color: var(--app-content-main-color);
                    cursor: pointer;
                }

                html.light .table-footer-controls #rows-per-page-select {
                    border-color: #ced4da;
                    background-color: #fff;
                }

                .table-footer-controls .footer-center {
                    justify-content: center;
                    gap: 4px;
                    flex-grow: 2;
                }

                .table-footer-controls .pagination-btn {
                    display: inline-flex;
                    justify-content: center;
                    align-items: center;
                    min-width: 32px;
                    height: 32px;
                    padding: 0 8px;
                    border: 1px solid var(--table-border);
                    background-color: transparent;
                    color: var(--app-content-main-color);
                    border-radius: 4px;
                    cursor: pointer;
                    font-weight: 500;
                    transition: all 0.25s ease-in-out;
                }

                html.light .table-footer-controls .pagination-btn {
                    border-color: #dee2e6;
                }

                .table-footer-controls .pagination-btn:hover:not(.active):not(:disabled) {
                    background-color: var(--sidebar-active-link);
                    border-color: var(--action-color);
                    transform: translateY(-3px);
                    box-shadow: 0 4px 12px rgba(40, 105, 255, 0.2);
                }

                html.light .table-footer-controls .pagination-btn:hover:not(.active):not(:disabled) {
                    background-color: #e9ecef;
                    border-color: #007bff;
                    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.25);
                }

                .table-footer-controls .pagination-btn.active {
                    background-color: var(--action-color);
                    border-color: var(--action-color);
                    color: #fff;
                    cursor: default;
                    transform: translateY(1px);
                    box-shadow: none;
                }

                .table-footer-controls .pagination-btn.active:hover {
                    background-color: var(--action-color);
                    border-color: var(--action-color);
                    color: #fff;
                    transform: translateY(-3px);
                    box-shadow: 0 4px 12px rgba(40, 105, 255, 0.2);
                }

                html.light .table-footer-controls .pagination-btn.active:hover {
                    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.25);
                }

                .table-footer-controls .pagination-btn:disabled {
                    opacity: 0.5;
                    cursor: not-allowed;
                    transform: none;
                    box-shadow: none;
                }

                .table-footer-controls .pagination-ellipsis {
                    display: inline-flex;
                    align-items: center;
                    padding: 0 8px;
                    color: var(--color-neutral-light);
                }

                /* ======================================================= */
                /* ===  KODE CSS RESPONSIVE UNTUK DASHBOARD ITVENTORY  === */
                /* ======================================================= */
                #mobile-burger-menu {
                    display: none;
                }

                @media screen and (max-width: 1024px) {
                    .sidebar {
                        position: fixed;
                        left: 0;
                        top: 0;
                        height: 100%;
                        z-index: 2000;
                        transform: translateX(-100%);
                        transition: transform 0.3s ease-in-out;
                    }

                    .sidebar.mobile-open {
                        transform: translateX(0);
                        box-shadow: 5px 0px 25px -5px rgba(0, 0, 0, 0.2);
                    }

                    .sidebar.collapsed {
                        min-width: var(--sidebar-width-expanded);
                        max-width: var(--sidebar-width-expanded);
                    }

                    .sidebar.collapsed .sidebar-list-item a span,
                    .sidebar.collapsed .app-icon {
                        opacity: 1;
                        width: auto;
                        margin-left: 0;
                        transform: none;
                    }

                    .sidebar.collapsed .sidebar-header .app-icon,
                    .sidebar.collapsed .sidebar-list-item a svg,
                    .sidebar.collapsed .account-info-name,
                    .sidebar.collapsed .account-info-more {
                        display: flex;
                    }

                    .app-content {
                        margin-left: 0;
                    }

                    .app-content-header {
                        display: flex;
                        align-items: center;
                        gap: 16px;
                    }

                    #mobile-burger-menu {
                        display: flex;
                    }

                    #burger-menu {
                        display: none;
                    }

                    .app-content-headerText {
                        flex-grow: 1;
                        margin: 0;
                    }

                    body.sidebar-open-overlay::after {
                        content: '';
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.5);
                        z-index: 1999;
                    }
                }

                @media screen and (max-width: 768px),
                screen and (orientation: landscape) and (max-height: 500px) {
                    .app-content {
                        padding: 0 16px 16px 16px;
                    }

                    .app-content-header {
                        padding-top: 16px;
                    }

                    .app-content-headerText {
                        font-size: 1.6rem;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        min-width: 0;
                        padding-top: 20px;
                        padding-bottom: none;
                    }

                    .inventory-summary-container,
                    .products-area-wrapper {
                        padding-left: 0;
                        padding-right: 0;
                    }

                    .app-content-actions {
                        flex-direction: column;
                        align-items: stretch;
                        gap: 12px;
                        padding-left: 0;
                        padding-right: 0;
                    }

                    .app-content-actions .search-bar-container {
                        order: 1;
                        max-width: none;
                        margin-right: 0;
                    }

                    .app-content-actions .app-content-actions-buttons {
                        order: 2;
                        display: flex;
                        justify-content: stretch;
                        gap: 10px;
                        padding-right: 1rem;
                    }

                    .app-content-actions-buttons .action-button,
                    .app-content-actions-buttons .filter-button-wrapper {
                        flex-grow: 1;
                        flex-basis: 0;
                        justify-content: center;
                        margin-left: 0;
                    }

                    .app-content-actions-buttons .action-button-text {
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        max-width: 100%;
                    }

                    .app-content-actions-buttons .filter-button-wrapper .action-button.filter {
                        width: 100%;
                    }

                    .app-content-actions .search-bar {
                        max-width: none;
                    }

                    .summary-box {
                        display: grid;
                        grid-template-columns: min-content 1fr;
                        grid-template-rows: auto auto;
                        gap: 0 16px;
                        align-items: center;
                        text-align: left;
                        padding: 12px 16px;
                        flex-basis: calc(50% - 8px);
                    }

                    .summary-box-icon {
                        grid-row: 1 / 3;
                        font-size: 3rem;
                        margin-bottom: 0;
                    }

                    .summary-box-type {
                        font-size: 1.4rem;
                        margin-bottom: 2px;
                        align-self: end;
                    }

                    .summary-box-count {
                        font-size: 1.8rem;
                        align-self: start;
                    }

                    .products-area-wrapper.tableView {
                        overflow: visible;
                    }

                    .tableView .products-header {
                        display: none;
                    }

                    .tableView .products-row {
                        display: block;
                        min-width: 0;
                        margin-bottom: 2rem;
                        padding: 1.5rem;
                        border-radius: 8px;
                        background-color: var(--app-content-secondary-color);
                        box-shadow: var(--filter-shadow);
                        border-left: 4px solid var(--action-color);
                    }

                    html.light .tableView .products-row {
                        background-color: #f9f9f9;
                        border: 1px solid #eee;
                        border-left: 4px solid var(--action-color);
                    }

                    .tableView .product-cell {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 1rem 0;
                        text-align: right;
                        white-space: normal;
                        border-bottom: 1px dashed var(--table-border);
                    }

                    .tableView .product-cell:last-child {
                        border-bottom: none;
                    }

                    .tableView .product-cell[data-label]::before {
                        content: attr(data-label);
                        font-weight: 500;
                        text-align: left;
                        padding-right: 1rem;
                        color: var(--app-content-main-color);
                    }

                    html.light .tableView .product-cell[data-label]::before {
                        color: #6c757d;
                    }

                    .tableView .product-cell.cell-no {
                        display: none;
                    }

                    .tableView .product-cell.cell-aksi {
                        justify-content: center;
                        padding-top: 1.5rem;
                        margin-top: 1rem;
                    }

                    .tableView .product-cell.cell-aksi .action-btn-table {
                        width: 100%;
                        padding: 12px 10px;
                    }

                    .app-content-actions .search-bar-container {
                        order: 1;
                        max-width: none;
                    }

                    .app-content-actions .app-content-actions-buttons {
                        order: 2;
                        display: flex;
                        justify-content: stretch;
                        gap: 10px;
                    }

                    .app-content-actions-buttons .add-asset-btn,
                    .app-content-actions-buttons .filter-button-wrapper {
                        flex-grow: 1;
                        flex-basis: 0;
                        margin-left: 0;
                    }

                    .app-content-actions-buttons .filter-button-wrapper .action-button.filter {
                        width: 100%;
                    }

                    .table-footer-controls {
                        flex-direction: column;
                        align-items: center;
                        gap: 20px;
                    }

                    .table-footer-controls .footer-section {
                        width: 100%;
                        justify-content: center;
                    }

                    .modal-content,
                    .modal-overlay .modal-content-wrapper {
                        width: calc(100% - 2.5rem);
                        max-width: 450px;
                        margin: 1.25rem;
                        padding: 1.5rem;
                        max-height: calc(100% - 2.5rem);
                    }

                    #deviceInfoModal .asset-detail-content .info-item,
                    #userHistoryModal .history-info-item,
                    #serahTerimaAsetModal .info-item {
                        flex-direction: column;
                        align-items: flex-start;
                        gap: 4px;
                        margin-bottom: 1.2rem;
                    }

                    #deviceInfoModal .asset-detail-content .info-item dt,
                    #userHistoryModal .history-info-item dt,
                    #serahTerimaAsetModal .info-item dt {
                        min-width: auto;
                        font-weight: 500;
                        font-size: 1.3rem;
                        color: var(--color-neutral-light);
                    }

                    #deviceInfoModal .asset-detail-content .info-item dd,
                    #userHistoryModal .history-info-item dd,
                    #serahTerimaAsetModal .info-item dd {
                        margin-left: 0;
                        font-weight: 600;
                        font-size: 1.4rem;
                        color: var(--color-text-light);
                        word-break: break-word;
                    }

                    #userHistoryModal .history-info-item dt::after,
                    #serahTerimaAsetModal .info-item dt::after {
                        display: none;
                    }

                    #deviceInfoModal .action-buttons {
                        flex-direction: column;
                        align-items: stretch;
                        gap: 12px;
                    }

                    #deviceInfoModal .action-buttons .btn-action {
                        width: 100%;
                        justify-content: center;
                    }

                    .modal-title {
                        font-size: 1.8rem;
                    }

                    .modal-title::before {
                        font-size: 1.7rem;
                    }

                    #deviceInfoModal .device-details-info-header {
                        margin-bottom: 1.5rem;
                    }

                    #deviceInfoModal .device-image {
                        font-size: 4rem;
                        margin-right: 1.5rem;
                    }

                    #deviceInfoModal .device-name {
                        font-size: 1.6rem;
                    }

                    .morph-modal-body {
                        -ms-overflow-style: none;
                        scrollbar-width: none;
                    }

                    .morph-modal-body::-webkit-scrollbar {
                        display: none;
                    }
                }
            </style>
        </head>

        <body>

            {{-- ====================================================================== --}}
            {{-- MODAL DETAIL ASET (DARI FILE MODAL ANDA, DENGAN ID UNTUK DATA DINAMIS) --}}
            {{-- ====================================================================== --}}
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
                                        <dt>Tgl. Pengadaan:</dt>
                                        <dd id="modalTglPengadaan">_</dd>
                                    </dl>
                                    <dl class="info-item">
                                        <dt>Serial Number:</dt>
                                        <dd id="modalSerialNumber">_</dd>
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
            {{-- AKHIR MODAL DETAIL ASET --}}

            {{-- ================================================================ --}}
            {{-- MODAL BARU: HISTORY PENGGUNA BERDASARKAN SERIAL NUMBER           --}}
            {{-- ================================================================ --}}
            <div id="userHistoryModal" class="modal-overlay" style="display: none;"> {{-- Awalnya display: none --}}
                <div class="modal-content-wrapper device-entry-info-modal"> {{-- Menggunakan class yang sama untuk styling --}}
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
                                <table class="user-history-table" id="userHistoryTableContainer"> <!-- ID ini bisa berguna untuk styling tabelnya sendiri -->
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
            {{-- AKHIR MODAL HISTORY PENGGUNA --}}

            {{-- ================================================================ --}}
            {{-- MODAL BARU: SERAH TERIMA ASET / UPDATE PENGGUNA                --}}
            {{-- ================================================================ --}}
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
            {{-- AKHIR MODAL SERAH TERIMA ASET --}}

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
                            @csrf

                            <div class="form-group">
                                <label for="perusahaan_id">Perusahaan</label>
                                <select name="perusahaan_id" id="perusahaan_id" class="form-control" required>
                                    <option value="">Pilih Perusahaan</option>
                                    {{-- Loop dari data yang dikirim DashboardController --}}
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
                                    {{-- Loop dari data yang dikirim DashboardController --}}
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

            <!-- =================================== -->
            <!--       HALAMAN DASHBOARD SECTION       -->
            <!-- =================================== -->
            <div id="dashboard-page">
                <div class="app-container">
                    <div class="sidebar">
                        <div class="sidebar-header">
                            <div class="app-icon">
                                <img src="/img/Scuto-logo.svg" alt="Scuto Logo" class="app-logo-svg">
                                <span class="app-name-text">ITventory</span>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                    <span>Perusahaan</span>
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
                            <h1 class="app-content-headerText">Data Aset Inventaris</h1>
                            <div class="app-content-header-actions-right"> {{-- Wrapper baru untuk tombol kanan atas --}}
                                <button class="mode-switch action-button" title="Switch Theme"> {{-- Tambahkan class action-button --}}
                                    <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="20" height="20" viewBox="0 0 24 24"> {{-- Ukuran ikon bisa disesuaikan --}}
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

                        {{-- +++ AWAL BAGIAN BARU UNTUK SUMMARY BOX +++ --}}
                        <div class="inventory-summary-container">
                            @php
                            $typeIcons = [
                            'Laptop' => 'fas fa-laptop',
                            'Handphone' => 'fas fa-mobile-alt',
                            'PC / AIO' => 'fas fa-desktop',
                            'Printer' => 'fas fa-print',
                            'Proyektor' => 'fas fa-video',
                            'Others' => 'fas fa-boxes'
                            ];
                            @endphp
                            @foreach($inventorySummary as $type => $count)
                            <div class="summary-box">
                                <div class="summary-box-icon">
                                    <i class="{{ $typeIcons[$type] ?? 'fas fa-question-circle' }}"></i>
                                </div>
                                <div class="summary-box-type">{{ $type }}</div>
                                @php
                                $slug = Str::slug($type);
                                @endphp
                                <div class="summary-box-count" id="summary-count-{{ $slug }}">{{ $count }}</div>
                            </div>
                            @endforeach
                        </div>
                        {{-- +++ AKHIR BAGIAN BARU UNTUK SUMMARY BOX +++ --}}

                        {{-- FORM FILTER DAN TOMBOL AKSI --}}
                        <form action="{{ route('dashboard.index') }}" method="GET" id="filterForm">
                            <div class="app-content-actions">

                                <div class="search-bar-container">
                                    <input class="search-bar"
                                        placeholder="Cari No Asset, Serial, atau Merek..."
                                        type="text"
                                        name="search_no_asset"
                                        value="{{ $searchKeyword ?? old('search_no_asset') }}"
                                        id="mainSearchInput"
                                        autocomplete="off">
                                    <button type="button" class="clear-search-btn" id="clearMainSearchBtn" style="display: none;" title="Hapus pencarian">
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
                                            {{-- Isi filter menu Anda tetap di sini --}}
                                            <label for="filter_perusahaan">Perusahaan</label>
                                            <select name="filter_perusahaan" id="filter_perusahaan">
                                                <option value="">Semua Perusahaan</option>
                                                @foreach($perusahaanOptions as $perusahaan)
                                                <option value="{{ $perusahaan->id }}" {{ (isset($filterPerusahaan) && $filterPerusahaan == $perusahaan->id) ? 'selected' : '' }}>
                                                    {{ $perusahaan->nama_perusahaan }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <label for="filter_jenis_barang">Jenis Barang</label>
                                            <select name="filter_jenis_barang" id="filter_jenis_barang">
                                                <option value="">Semua Jenis Barang</option>
                                                @foreach($jenisBarangOptions as $jenis)
                                                <option value="{{ $jenis->id }}" {{ (isset($filterJenisBarang) && $filterJenisBarang == $jenis->id) ? 'selected' : '' }}>
                                                    {{ $jenis->nama_jenis }}
                                                </option>
                                                @endforeach
                                            </select>
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
                        {{-- FORM FILTER SELESAI DI SINI --}}

                        {{-- Area tabel produk --}}
                        <div class="products-area-wrapper tableView" id="productTableArea">
                            {{-- Header Tabel (tetap ada atau bisa juga digenerate JS jika mau) --}}
                            <div class="products-header">
                                <div class="product-cell cell-no sortable-header" data-sort-by="no">No</div>
                                <div class="product-cell cell-perusahaan sortable-header" data-sort-by="perusahaan">Perusahaan</div>
                                <div class="product-cell cell-jenis-barang sortable-header" data-sort-by="jenis_barang">Jenis Barang</div>
                                <div class="product-cell cell-no-asset sortable-header" data-sort-by="no_asset">No Asset</div>
                                <div class="product-cell cell-merek sortable-header" data-sort-by="merek">Merek</div>
                                <div class="product-cell cell-tgl-pengadaan sortable-header" data-sort-by="tgl_pengadaan" data-sort-type="date">Tgl. Pengadaan</div>
                                <div class="product-cell cell-serial-number sortable-header" data-sort-by="serial_number">Serial Number</div>
                                <div class="product-cell cell-aksi">Aksi</div>
                            </div>

                            {{-- Container untuk baris-baris produk yang akan diisi oleh JS --}}
                            <div id="productTableRowsContainer">
                                {{-- Load Awal dari Controller index() --}}
                                @if(isset($barangs) && $barangs->count() > 0)
                                @foreach($barangs as $index => $barang)
                                <div class="products-row">
                                    <div class="product-cell cell-no">{{ $barangs->firstItem() + $index }}</div>
                                    <div class="product-cell cell-perusahaan" data-label="Perusahaan">{{ $barang->perusahaan->nama_perusahaan ?? 'N/A' }}</div>
                                    <div class="product-cell cell-jenis-barang" data-label="Jenis Barang">{{ $barang->jenisBarang->nama_jenis ?? 'N/A' }}</div>
                                    <div class="product-cell cell-no-asset" data-label="No Asset">{{ $barang->no_asset }}</div>
                                    <div class="product-cell cell-merek" data-label="Merek">{{ $barang->merek }}</div>
                                    <div class="product-cell cell-tgl-pengadaan" data-label="Tgl. Pengadaan">{{ \Carbon\Carbon::parse($barang->tgl_pengadaan)->format('d-m-Y') }}</div>
                                    <div class="product-cell cell-serial-number" data-label="Serial Number">{{ $barang->serial_number }}</div>
                                    <div class="product-cell cell-aksi">
                                        <button class="action-btn-table detail-btn-table-js" data-id="{{ $barang->id }}" title="Detail Aset">
                                            <i class="fas fa-info-circle"></i>
                                            <span>Detail</span>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="products-row">
                                    <div class="product-cell" style="text-align:center; flex-basis:100%; padding: 20px;">Tidak ada data aset ditemukan.</div>
                                </div>
                                @endif
                            </div>

                            {{-- Container untuk Paginasi --}}
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
                                    {{-- Tombol-tombol paginasi akan dirender di sini oleh JS --}}
                                </div>

                                <div class="footer-section footer-right">
                                    <div class="pagination-info" id="pagination-info-text">
                                        {{-- Teks "Menampilkan..." akan diisi oleh JS --}}
                                    </div>
                                </div>

                            </div>
                            {{-- AKHIR KONTEN UTAMA APLIKASI --}}

                        </div>
                    </div>

                    {{-- ... (Bagian HTML Anda di atas) ... --}}

                    <script>
                        // ==============================================================================
                        // VARIABEL GLOBAL & FUNGSI HELPER YANG BISA DIAKSES DARI MANA SAJA
                        // ==============================================================================
                        let currentAssetIdForSerahTerima = null;
                        let debounceTimer;
                        let lastKnownUserFromDetail = '-';
                        const DEBOUNCE_DELAY = 500;

                        function openDetailModal(barangId) {
                            const detailModalOverlay = document.getElementById('deviceInfoModal');
                            if (!detailModalOverlay) return;

                            // Ambil semua elemen UI yang PASTI ada
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

                            if (!modalDeviceName || !deviceImage) {
                                console.error('Elemen wajib pada modal detail (seperti nama atau gambar) tidak ditemukan.');
                                return;
                            }

                            modalDeviceName.textContent = 'Memuat...';
                            modalDeviceType.textContent = '_';
                            modalPerusahaan.textContent = '_';
                            modalNoAsset.textContent = '_';
                            modalTglPengadaan.textContent = '_';
                            modalSerialNumber.textContent = '_';
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
                                        modalTglPengadaan.textContent = formatDate(barang.tgl_pengadaan);
                                        modalSerialNumber.textContent = barang.serial_number || 'N/A';

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

                                        let iconClass = 'fas fa-desktop';
                                        const jenisNama = barang.jenis_barang ? barang.jenis_barang.nama_jenis.trim() : '';
                                        if (jenisNama === 'Laptop') iconClass = 'fas fa-laptop';
                                        else if (jenisNama === 'Handphone') iconClass = 'fas fa-mobile-alt';
                                        else if (jenisNama === 'Printer') iconClass = 'fas fa-print';
                                        else if (jenisNama === 'Proyektor') iconClass = 'fas fa-video';
                                        else if (jenisNama === 'Others') iconClass = 'fas fa-box-open';
                                        else if (jenisNama === 'PC / AIO') iconClass = 'fas fa-desktop';
                                        deviceImage.className = `device-image ${iconClass}`;
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
                                serahTerimaUserInput.readOnly = true;
                            } else if (selectedStatus === 'diperbaiki') {
                                serahTerimaUserInput.value = `Team IT - ${previousUser}`;
                            } else if (selectedStatus === 'non aktif') {
                                serahTerimaUserInput.value = 'Team IT';
                                serahTerimaUserInput.readOnly = true;
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

                                serahForm.reset();

                                document.getElementById('serahTerimaAssetId').value = assetId;
                                document.getElementById('serahTerimaSerialNumber').value = barang.serial_number;
                                document.getElementById('serahTerimaInfoNamaAset').textContent = barang.merek;
                                document.getElementById('serahTerimaInfoSN').textContent = barang.serial_number;
                                document.getElementById('serahTerimaInfoPerusahaan').textContent = barang.perusahaan ? barang.perusahaan.nama_perusahaan : 'N/A';
                                document.getElementById('serahTerimaTanggalAwal').value = new Date().toISOString().slice(0, 10);
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

                        function openUserHistoryModal(serialNumber, deviceName, company) {
                            const historyModal = document.getElementById('userHistoryModal');
                            const historyModalSerialNumberEl = document.getElementById('historyModalSerialNumber');
                            const historyModalDeviceNameEl = document.getElementById('historyModalDeviceName');
                            const historyModalCompanyEl = document.getElementById('historyModalCompany');
                            const historyTableBodyEl = document.getElementById('userHistoryTableBody');
                            const historyTableHeaderEl = document.getElementById('userHistoryTableHeader');

                            if (!historyModal || !historyModalSerialNumberEl || !historyModalDeviceNameEl || !historyModalCompanyEl || !historyTableBodyEl || !historyTableHeaderEl) {
                                console.error("Satu atau lebih elemen modal history tidak ditemukan. Periksa ID.");
                                alert("Kesalahan: Komponen modal history tidak lengkap.");
                                return;
                            }

                            historyModalDeviceNameEl.textContent = deviceName || 'Tidak Diketahui';
                            historyModalSerialNumberEl.textContent = serialNumber || 'N/A';
                            historyModalCompanyEl.textContent = company || 'N/A';
                            historyTableBodyEl.innerHTML = '<tr><td colspan="5" style="padding:15px; text-align:center;">Memuat riwayat... <i class="fas fa-spinner fa-spin"></i></td></tr>';
                            historyTableHeaderEl.innerHTML = '';
                            historyModal.style.display = 'flex';

                            fetch(`/history/user/${encodeURIComponent(serialNumber)}`)
                                .then(response => {
                                    if (!response.ok) {
                                        return response.text().then(text => {
                                            console.error('Server returned an error (history):', response.status, text);
                                            let errorData = {
                                                message: `HTTP error ${response.status}.`
                                            };
                                            try {
                                                errorData = JSON.parse(text);
                                            } catch (e) {}
                                            throw errorData;
                                        });
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    historyTableHeaderEl.innerHTML = `
                                <tr>
                                    <th class="cell-history-user">Pengguna</th>
                                    <th class="cell-history-tgl-awal">Penyerahan</th>
                                    <th class="cell-history-tgl-akhir">Pengembalian</th>
                                    <th class="cell-history-status">Status</th>
                                    <th class="cell-history-keterangan">Keterangan</th>
                                </tr>
                            `;
                                    if (data && data.success && Array.isArray(data.history) && data.history.length > 0) {
                                        let tableRowsHTML = '';
                                        data.history.forEach(item => {
                                            const usernameAman = escapeHtml(item.username || '-');
                                            const statusAman = escapeHtml(item.status || '-');
                                            const keteranganAman = escapeHtml(item.keterangan || '-');
                                            const tglAwal = (item && item.tanggal_awal) ? formatDate(item.tanggal_awal) : '-';
                                            const tglAkhir = (item && item.tanggal_ahir) ? formatDate(item.tanggal_ahir) : '-';
                                            tableRowsHTML += `
                                            <tr>
                                                <td class="cell-history-user" title="${usernameAman}">${usernameAman}</td>
                                                <td class="cell-history-tgl-awal">${tglAwal}</td>
                                                <td class="cell-history-tgl-akhir">${tglAkhir}</td>
                                                <td class="cell-history-status" title="${statusAman}">${statusAman}</td>
                                                <td class="cell-history-keterangan" title="${keteranganAman}">${keteranganAman}</td>
                                            </tr>`;
                                        });
                                        historyTableBodyEl.innerHTML = tableRowsHTML;
                                    } else {
                                        historyTableBodyEl.innerHTML = '<tr><td colspan="5" style="padding:15px; text-align:center;">Tidak ada riwayat pengguna.</td></tr>';
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching atau memproses user history:', error);
                                    let displayError = 'Gagal memuat riwayat.';
                                    if (error && typeof error === 'object' && error.message) displayError = error.message;
                                    else if (typeof error === 'string') displayError = error;

                                    historyTableBodyEl.innerHTML = `<tr><td colspan="5" style="padding:15px; text-align:center; color:red;">${displayError}</td></tr>`;
                                    historyTableHeaderEl.innerHTML = `
                                <tr>
                                    <th class="cell-history-user">Pengguna</th>
                                    <th class="cell-history-tgl-awal">Penyerahan</th>
                                    <th class="cell-history-tgl-akhir">Pengembalian</th>
                                    <th class="cell-history-status">Status</th>
                                    <th class="cell-history-keterangan">Keterangan</th>
                                </tr>
                            `;
                                });
                        }

                        // ==============================================================================
                        // DOMContentLoaded: INSIALISASI EVENT LISTENERS DAN FUNGSI YANG BERGANTUNG DOM
                        // ==============================================================================
                        document.addEventListener('DOMContentLoaded', () => {
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

                            // Variabel Modal Tambah Aset
                            const addAssetModal = document.getElementById('addAssetModal');
                            const openAddAssetModalButton = document.getElementById('openAddAssetModalButton');
                            const closeAddAssetModalBtn = document.getElementById('closeAddAssetModalBtn');
                            const cancelAddAssetModalBtn = document.getElementById('cancelAddAssetModalBtn');
                            const addAssetForm = document.getElementById('addAssetForm');
                            const submitAddAssetBtn = document.getElementById('submitAddAssetBtn');

                            // Variabel Modal Detail Aset
                            const detailModalOverlayElement = document.getElementById('deviceInfoModal');
                            const closeDetailModalButtonElement = document.getElementById('closeDetailModalButton');

                            // Variabel Modal Serah Terima Aset
                            const serahTerimaAsetModalElement = document.getElementById('serahTerimaAsetModal');
                            const closeSerahTerimaBtn = document.getElementById('closeSerahTerimaAsetModalButton');
                            const cancelSerahTerimaBtn = document.getElementById('cancelSerahTerimaAsetModalBtn');
                            const serahTerimaForm = document.getElementById('serahTerimaAsetForm');
                            const submitSerahTerimaBtn = document.getElementById('submitSerahTerimaAsetBtn');
                            const serahTerimaStatusSelect = document.getElementById('serahTerimaStatus');

                            // Variabel Modal History User
                            const userHistoryModalElement = document.getElementById('userHistoryModal');
                            const closeUserHistoryBtn = document.getElementById('closeUserHistoryModalButton');

                            // Variabel Pagination
                            const rowsPerPageSelect = document.getElementById('rows-per-page-select');
                            const paginationInfoText = document.getElementById('pagination-info-text');
                            const paginationButtonsContainer = document.getElementById('pagination-buttons-container');

                            // Variabel UI Lainnya
                            const modeSwitch = document.querySelector('.mode-switch');
                            const burgerMenuButton = document.getElementById('burger-menu');
                            const sidebarElement = document.querySelector('.sidebar');
                            const mobileBurgerButton = document.getElementById('mobile-burger-menu');

                            const exportExcelButton = document.getElementById('exportExcelBtn');


                            // Variabel State
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
                                            const response = await fetch(`{{ url('/aset/nomor-seri-berikutnya') }}/${perusahaanId}`);
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
                                        const response = await fetch(`{{ url('/aset/nomor-seri-berikutnya') }}/${perusahaanId}`);
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

                                    const exportUrl = `{{ route('dashboard.export') }}?${params.toString()}`;
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

                            function parseDate(dateStr) {
                                const parts = dateStr.split('-');
                                return new Date(parts[2], parts[1] - 1, parts[0]);
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
                                if (!productTableRowsContainer) return;

                                const perPage = rowsPerPageSelect ? rowsPerPageSelect.value : 10;
                                const keyword = mainSearchInput ? mainSearchInput.value : '';
                                const perusahaan = filterPerusahaanSelect ? filterPerusahaanSelect.value : '';
                                const jenisBarang = filterJenisBarangSelect ? filterJenisBarangSelect.value : '';

                                productTableRowsContainer.innerHTML = '<div class="products-row"><div class="product-cell" style="text-align:center; flex-basis:100%; padding: 40px;">Memuat data... <i class="fas fa-spinner fa-spin"></i></div></div>';
                                if (paginationInfoText) paginationInfoText.innerHTML = '';
                                if (paginationButtonsContainer) paginationButtonsContainer.innerHTML = '';

                                const params = new URLSearchParams({
                                    search_no_asset: keyword,
                                    filter_perusahaan: perusahaan,
                                    filter_jenis_barang: jenisBarang,
                                    page: page,
                                    per_page: perPage,
                                });

                                if (activeSortKey && activeSortDirection !== 'none') {
                                    params.append('sort_by', activeSortKey);
                                    params.append('sort_direction', activeSortDirection);
                                }

                                fetch(`{{ route('dashboard.search.realtime') }}?${params.toString()}`, {
                                        method: 'GET',
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(responseData => {
                                        let tableRowsHtml = '';
                                        if (responseData.data && responseData.data.length > 0) {
                                            responseData.data.forEach((barang) => {
                                                const rowNumber = barang.row_number;

                                                tableRowsHtml += `
                                            <div class="products-row">
                                                <div class="product-cell cell-no">${rowNumber}</div>
                                                <div class="product-cell cell-perusahaan" title="${escapeHtml(barang.perusahaan_nama || 'N/A')}">${escapeHtml(barang.perusahaan_nama || 'N/A')}</div>
                                                <div class="product-cell cell-jenis-barang" title="${escapeHtml(barang.jenis_barang || 'N/A')}">${escapeHtml(barang.jenis_barang || 'N/A')}</div>
                                                <div class="product-cell cell-no-asset" title="${escapeHtml(barang.no_asset || '')}">${escapeHtml(barang.no_asset || '')}</div>
                                                <div class="product-cell cell-merek" title="${escapeHtml(barang.merek || '')}">${escapeHtml(barang.merek || '')}</div>
                                                <div class="product-cell cell-tgl-pengadaan">${formatDate(barang.tgl_pengadaan)}</div>
                                                <div class="product-cell cell-serial-number" title="${escapeHtml(barang.serial_number || '')}">${escapeHtml(barang.serial_number || '')}</div>
                                                <div class="product-cell cell-aksi">
                                                    <button class="action-btn-table detail-btn-table-js" data-id="${barang.id}" title="Detail Aset">
                                                        <i class="fas fa-info-circle"></i>
                                                        <span>Detail</span>
                                                    </button>
                                                </div>
                                            </div>`;
                                            });
                                        } else {
                                            tableRowsHtml = `<div class="products-row"><div class="product-cell" style="text-align:center; flex-basis:100%; padding: 20px;">Tidak ada data aset ditemukan.</div></div>`;
                                        }
                                        productTableRowsContainer.innerHTML = tableRowsHtml;
                                        if (responseData.pagination) renderPaginationControls(responseData.pagination);
                                        if (responseData.inventorySummary) {
                                            updateInventorySummary(responseData.inventorySummary);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error performing real-time search:', error);
                                        productTableRowsContainer.innerHTML = `<div class="products-row"><div class="product-cell" style="text-align:center; flex-basis:100%; padding: 20px; color:red;">Gagal memuat data.</div></div>`;
                                    });
                            }

                            function setupAjaxPagination() {
                                if (!paginationContainer) return;
                                paginationContainer.querySelectorAll('.pagination a').forEach(link => {
                                    const newLink = link.cloneNode(true);
                                    link.parentNode.replaceChild(newLink, link);
                                    newLink.addEventListener('click', function(event) {
                                        event.preventDefault();
                                        const url = new URL(this.href);
                                        const page = url.searchParams.get('page') || 1;
                                        performRealtimeSearch(page);
                                    });
                                });
                            }

                            function updateInventorySummary(summaryData) {
                                if (!summaryData) return;

                                document.querySelectorAll('.summary-box-count').forEach(el => {
                                    el.textContent = '0';
                                });

                                for (const type in summaryData) {
                                    const slug = type.toLowerCase()
                                        .replace(/ \/ /g, '-')
                                        .replace(/ /g, '-');
                                    const elementId = `summary-count-${slug}`;
                                    const countElement = document.getElementById(elementId);

                                    if (countElement) {
                                        countElement.textContent = summaryData[type];
                                    } else {
                                        console.warn(`Elemen summary dengan ID '${elementId}' tidak ditemukan.`);
                                    }
                                }
                            }

                            function checkAndUpdateFilterStates() {
                                if (!filterPerusahaanSelect || !filterJenisBarangSelect || !resetFilterInMenuBtn) return;
                                const perusahaanAktif = filterPerusahaanSelect.value !== '';
                                const jenisBarangAktif = filterJenisBarangSelect.value !== '';
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
                                    if (filterJenisBarangSelect) filterJenisBarangSelect.value = '';
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

                                    fetch("{{ route('barang.store') }}", {
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

                            if (serahTerimaStatusSelect) {
                                serahTerimaStatusSelect.addEventListener('change', handleSerahTerimaStatusChange);
                            }
                            if (closeSerahTerimaBtn) closeSerahTerimaBtn.addEventListener('click', () => {
                                if (serahTerimaAsetModalElement) serahTerimaAsetModalElement.style.display = 'none';
                            });
                            if (cancelSerahTerimaBtn) cancelSerahTerimaBtn.addEventListener('click', () => {
                                if (serahTerimaAsetModalElement) serahTerimaAsetModalElement.style.display = 'none';
                            });
                            if (serahTerimaStatusSelect) {
                                serahTerimaStatusSelect.addEventListener('change', handleSerahTerimaStatusChange);
                            }
                            if (closeSerahTerimaBtn) closeSerahTerimaBtn.addEventListener('click', () => {
                                if (serahTerimaAsetModalElement) serahTerimaAsetModalElement.style.display = 'none';
                            });
                            if (cancelSerahTerimaBtn) cancelSerahTerimaBtn.addEventListener('click', () => {
                                if (serahTerimaAsetModalElement) serahTerimaAsetModalElement.style.display = 'none';
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
                                    submitSerahTerimaBtn.textContent = 'Menyimpan...';
                                    submitSerahTerimaBtn.disabled = true;

                                    fetch("{{ route('aset.serahterima.store') }}", {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': formData.get('_token'),
                                                'Accept': 'application/json'
                                            },
                                            body: formData
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                alert(data.message || 'Data serah terima berhasil disimpan.');
                                                const serahTerimaAsetModalElement = document.getElementById('serahTerimaAsetModal');
                                                if (serahTerimaAsetModalElement) serahTerimaAsetModalElement.style.display = 'none';
                                                const detailModalOverlayElement = document.getElementById('deviceInfoModal');
                                                if (detailModalOverlayElement) detailModalOverlayElement.style.display = 'none';
                                                performRealtimeSearch();
                                            } else if (data.errors) {
                                                displayValidationErrorsOnForm(data.errors, serahTerimaForm);
                                            } else {
                                                alert(data.message || 'Gagal menyimpan data serah terima.');
                                            }
                                        })
                                        .catch(err => {
                                            console.error("Error serah terima:", err);
                                            alert('Terjadi kesalahan. Cek konsol.');
                                            if (err.body && err.body.errors) {
                                                displayValidationErrorsOnForm(err.body.errors, serahTerimaForm);
                                            }
                                        })
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
                            if (resetFilterInMenuBtn) {
                                resetFilterInMenuBtn.addEventListener('click', () => {
                                    if (filterPerusahaanSelect) filterPerusahaanSelect.value = '';
                                    if (filterJenisBarangSelect) filterJenisBarangSelect.value = '';


                                    performRealtimeSearch(1);
                                    checkAndUpdateFilterStates();

                                    if (filterMenu && filterMenu.classList.contains('active')) {
                                        filterMenu.classList.remove('active');
                                    }
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
                            const SIDEBAR_COLLAPSED_KEY = 'sidebarCollapsedITventory'; // Gunakan nama unik
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
                                    const dashboardLinkEl = document.querySelector(`.sidebar-list-item a[href="{{ route('dashboard.index')}}"]`);
                                    if (dashboardLinkEl && (window.location.pathname === '/' || window.location.pathname === new URL(dashboardLinkEl.href).pathname)) {
                                        dashboardLinkEl.parentElement.classList.add('active');
                                    }
                                }
                            }
                            initializeSidebarState();
                            setActiveSidebarLink();
                        });
                    </script>
        </body>

        </html>