    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>ITventory - User Manage</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
        <link rel="icon" href="{{ asset('img/Scuto-logo.svg') }}" type="image/x-icon">
        <style>
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

            .modal-overlay {
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

            .modal.show {
                display: flex;
            }

            .modal-content {
                background-color: var(--app-content-secondary-color);
                color: var(--app-content-main-color);
                margin: auto;
                padding: 25px;
                border: 1px solid var(--table-border);
                border-radius: 8px;
                width: 90%;
                max-width: 600px;
                box-shadow: var(--filter-shadow);
                position: relative;
            }

            html.light .modal-content {
                background-color: #fff;
                border-color: #ddd;
            }

            .modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-bottom: 15px;
                border-bottom: 1px solid var(--table-border);
                margin-bottom: 20px;
            }

            html.light .modal-header {
                border-bottom-color: #eee;
            }

            .modal-content-wrapper.device-entry-info-modal {
                background-color: var(--color-modal-background, var(--app-content-secondary-color));
                border-radius: 1rem;
                width: 100%;
                max-width: 55rem;
                max-height: calc(100vh - 4rem);
                overflow: hidden;
                display: flex;
                flex-direction: column;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                color: var(--color-text-light, var(--app-content-main-color));
            }

            .morph-modal-container {
                display: flex;
                flex-direction: column;
                padding: 2.5rem;
                flex-grow: 1;
            }

            .morph-modal-title {
                padding-bottom: 1.5rem;
                border-bottom: 0.1rem solid var(--color-neutral-medium, var(--table-border));
                font-size: 2.2rem;
                font-weight: 500;
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2rem;
            }

            .morph-modal-title .fas {
                margin-right: 1rem;
                color: var(--action-color);
            }

            .btn-close {
                background: none;
                border: none;
                color: var(--color-neutral-light, #94a3b8);
                font-size: 2.8rem;
                cursor: pointer;
                padding: 0.5rem;
                line-height: 1;
            }

            .btn-close:hover {
                color: var(--color-text-light, #fff);
            }

            .morph-modal-body {
                flex: 1 1 auto;
                overflow-y: auto;
            }

            .morph-modal-body .form-group label {
                display: block;
                margin-bottom: 0.8rem;
                font-weight: 500;
            }

            .morph-modal-body .form-group input[type="text"],
            .morph-modal-body .form-group input[type="email"],
            .morph-modal-body .form-group input[type="password"] {
                width: 100%;
                padding: 12px 14px;
                border: 1px solid var(--table-border);
                border-radius: 6px;
                background-color: var(--app-bg);
                color: var(--app-content-main-color);
                font-size: 1.5rem;
            }

            .morph-modal-body .form-group input:focus {
                border-color: var(--action-color);
                outline: none;
                box-shadow: 0 0 0 0.2rem rgba(40, 105, 255, 0.25);
            }

            .morph-modal-body .form-group input[readonly] {
                background-color: #4a5568;
                cursor: not-allowed;
                opacity: 0.7;
            }

            html.light .morph-modal-body .form-group input[readonly] {
                background-color: #e9ecef;
                color: #6c757d;
                border-color: #ced4da;
            }

            .modal-footer {
                padding-top: 2rem;
                border-top: 1px solid var(--color-neutral-medium, var(--table-border));
                margin-top: 2rem;
                text-align: right;
                display: flex;
                justify-content: flex-end;
                gap: 1rem;
            }

            .modal-footer .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;

                padding: 12px 24px;
                border-radius: 6px;
                border: none;
                cursor: pointer;
                font-weight: 500;
                font-size: 1.5rem;
                transition: all 0.2s ease;
            }

            .modal-footer .btn-primary {
                background-color: var(--action-color);
                color: white;
            }

            .modal-footer .btn-primary:hover {
                background-color: var(--action-color-hover);
                transform: translateY(-2px);
            }

            .modal-footer .btn-secondary {
                background-color: #e74c3c;
                color: white;
            }

            .modal-footer .btn-secondary:hover {
                background-color: #c0392b;
                transform: translateY(-2px);
            }

            .modal-title {
                font-size: 1.5rem;
                font-weight: 500;
                margin: 0;
            }

            #addUserModal .close-button {
                color: var(--app-content-main-color);
                font-size: 1.8rem;
                font-weight: bold;
                background: none;
                border: none;
                cursor: pointer;
                padding: 0 5px;
            }

            html.light #addUserModal .close-button {
                color: #555;
            }

            #addUserModal .close-button:hover,
            #addUserModal .close-button:focus {
                text-decoration: none;
            }

            .modal-body form label {
                display: block;
                margin-bottom: 8px;
                font-weight: 500;
            }

            .modal-body form input[type="text"],
            .modal-body form input[type="email"],
            .modal-body form input[type="password"],
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
            html.light .modal-body form input[type="email"],
            html.light .modal-body form input[type="password"],
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
                border-top: 1px solid var(--table-border);
                margin-top: 20px;
                text-align: right;
            }

            html.light .modal-footer {
                border-top-color: #eee;
            }

            .modal-footer .btn {
                padding: 10px 20px;
                border-radius: 4px;
                border: none;
                cursor: pointer;
                font-weight: 500;
                margin-left: 10px;
            }

            .modal-footer .btn-primary {
                background-color: var(--action-color);
                color: white;
            }

            .modal-footer .btn-primary:hover {
                background-color: var(--action-color-hover);
            }

            .modal-footer .btn-secondary {
                background-color: var(--filter-reset);
                color: white;
            }

            html.light .modal-footer .btn-secondary {
                background-color: #6c757d;
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

            .app-content-actions .action-button.add-user-btn {
                background-color: var(--action-color) !important;
                color: white;
                border: none;
            }

            .action-button.add-user-btn svg,
            .action-button.add-user-btn i.fas {
                margin-right: 6px !important;
            }

            @media screen and (max-width: 768px) {
                .app-content-actions {
                    flex-direction: column;
                    align-items: stretch;
                }

                .app-content-actions .search-bar {
                    order: 1;
                }

                .app-content-actions-buttons {
                    order: 2;
                    justify-content: flex-start;
                }

                .app-content-actions .search-bar-container {
                    flex-grow: 1;
                    max-width: 320px;
                    margin-right: 0pxx;
                }
            }

            @media screen and (max-width: 520px) {
                .app-content-actions {
                    flex-direction: column;
                }

                .app-content-actions .search-bar {
                    max-width: 100%;
                    order: 2;
                }

                .app-content-actions .app-content-actions-wrapper {
                    padding-bottom: 16px;
                    order: 1;
                }
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

            .password-wrapper {
                position: relative;
                display: flex;
                align-items: center;
            }

            .password-wrapper input[type="password"],
            .password-wrapper input[type="text"] {
                padding-right: 45px !important;
            }

            .toggle-password {
                position: absolute;
                right: 15px;
                cursor: pointer;
                color: var(--color-neutral-light, #94a3b8);
                transition: color 0.2s ease;
            }

            .toggle-password:hover {
                color: var(--color-text-light, #fff);
            }

            .products-area-wrapper {
                width: 100%;
                max-height: 100%;
                overflow: auto;
                padding: 0 4px;
                margin-top: 16px;
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

            .tableView .products-row {
                display: flex;
                align-items: center;
                border-radius: 4px;
                min-width: 900px;
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
                flex: 0 0 60px;
                min-width: 60px;
                justify-content: center;
            }

            .tableView .product-cell.cell-username {
                flex: 1 1 200px;
                min-width: 200px;
            }

            .tableView .product-cell.cell-email {
                flex: 1 1 250px;
                min-width: 250px;
            }

            .tableView .product-cell.cell-role {
                flex: 1 1 120px;
                min-width: 120px;
            }

            .tableView .product-cell.cell-tanggal-dibuat {
                flex: 1 1 180px;
                min-width: 180px;
            }

            .tableView .product-cell.cell-aksi {
                flex: 0 0 220px;
                min-width: 220px;
                justify-content: center;
                gap: 8px;
                overflow: visible;
            }

            /* === AKHIR PERUBAHAN CSS KOLOM === */

            .tableView .product-cell img {
                width: 32px;
                height: 32px;
                border-radius: 6px;
                margin-right: 6px;
            }

            .tableView .cell-label {
                display: none;
            }

            .product-cell.cell-aksi .action-btn-table {
                padding: 6px 14px;
                margin: 0 4px;
                border: 1px solid transparent;
                background-color: transparent;
                color: #fff;
                border-radius: 4px;
                cursor: pointer;
                font-size: 13px;
                font-weight: 500;
                transition: all 0.2s ease-in-out;
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            .product-cell.cell-aksi .action-btn-table.edit-btn {
                border-color: var(--action-color);
                color: var(--action-color);
            }

            .product-cell.cell-aksi .action-btn-table.edit-btn:hover {
                background-color: var(--action-color);
                color: #fff;
            }

            .product-cell.cell-aksi .action-btn-table.delete-btn {
                border-color: #e74c3c;
                color: #e74c3c;
            }

            .product-cell.cell-aksi .action-btn-table.delete-btn:hover {
                background-color: #e74c3c;
                color: #fff;
            }

            /* === AKHIR PERUBAHAN CSS TOMBOL AKSI === */


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
            /* ===      KODE CSS RESPONSIVE UNTUK User Manage      === */
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

                .table-footer-controls {
                    flex-direction: column;
                    align-items: center;
                    gap: 20px;
                }

                .table-footer-controls .footer-section {
                    width: 100%;
                    justify-content: center;
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

                .products-area-wrapper {
                    padding-left: 0;
                    padding-right: 0;
                }

                .app-content-actions {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 12px;
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
                    gap: 0;
                }

                .app-content-actions-buttons .action-button {
                    flex-grow: 1;
                    justify-content: center;
                    margin-left: 0;
                    margin-right: 0;
                }

                .app-content-actions .search-bar {
                    max-width: none;
                    padding-right: 0px;
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
                    gap: 12px;
                }

                .tableView .product-cell.cell-aksi .action-btn-table {
                    width: 100%;
                    padding: 12px 10px;
                    flex-grow: 1;
                    justify-content: center;
                }

                .pagination-container {
                    display: flex;
                    flex-wrap: wrap;
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

                .modal-title {
                    font-size: 1.8rem;
                }
            }
        </style>
    </head>

    <body>

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
                        {{-- Link ke Dashboard --}}
                        <li class="sidebar-list-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                    <polyline points="9 22 9 12 15 12 15 22" />
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        {{-- Link ke Manajemen User --}}
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
                                        <option value="10" selected>10</option>
                                        <option value="20">20</option>
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
                                    <option value="user" selected>User</option>
                                    <option value="admin">Admin</option>
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
                            @method('PUT') {{-- Beritahu Laravel ini adalah request UPDATE --}}
                            <input type="hidden" id="edit_user_id" name="user_id">

                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label for="edit_email">Alamat Email (Tidak dapat diubah)</label>
                                <input type="email" name="email" id="edit_email" readonly>
                            </div>

                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label for="edit_name">Username</label>
                                <input type="text" name="name" id="edit_name" required>
                                <div class="invalid-feedback" id="edit_name_error"></div>
                            </div>

                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label for="edit_role">Role</label>
                                <select name="role" id="edit_role" class="form-control" required style="width: 100%; padding: 12px 14px; border: 1px solid var(--table-border); border-radius: 6px; background-color: var(--app-bg); color: var(--app-content-main-color); font-size: 1.5rem;">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
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


        <script>
            document.addEventListener('DOMContentLoaded', () => {

                // ==========================================================
                // DEKLARASI VARIABEL GLOBAL UNTUK SCRIPT INI
                // ==========================================================
                const searchInput = document.getElementById('userSearchInput');
                const tableContainer = document.getElementById('productTableRowsContainer');
                const burgerMenuButton = document.getElementById('burger-menu');
                const sidebarElement = document.querySelector('.sidebar');
                const modeSwitch = document.querySelector('.mode-switch');
                let debounceTimer;

                // Variabel Modal Tambah User
                const addUserModal = document.getElementById('addUserModal');
                const openAddUserBtn = document.getElementById('openAddUserModalButton');
                const closeAddUserBtn = document.getElementById('closeAddUserModalBtn');
                const cancelAddUserBtn = document.getElementById('cancelAddUserModalBtn');
                const addUserForm = document.getElementById('addUserForm');
                const submitAddUserBtn = document.getElementById('submitAddUserBtn');

                // Variabel Modal Edit User
                const editUserModal = document.getElementById('editUserModal');
                const closeEditUserBtn = document.getElementById('closeEditUserModalBtn');
                const cancelEditUserBtn = document.getElementById('cancelEditUserModalBtn');
                const editUserForm = document.getElementById('editUserForm');

                const rowsPerPageSelect = document.getElementById('rows-per-page-select');
                const paginationButtonsContainer = document.getElementById('pagination-buttons-container');
                const paginationInfoText = document.getElementById('pagination-info-text');

                // ==========================================================
                // FUNGSI-FUNGSI UTAMA
                // ==========================================================

                // Fungsi untuk mengambil data user via AJAX
                function fetchUsers(page = 1) {
                    if (!tableContainer) return;

                    const query = searchInput.value;
                    const perPage = rowsPerPageSelect.value;

                    const params = new URLSearchParams({
                        search: query,
                        page: page,
                        per_page: perPage
                    });
                    const url = `{{ route('users.index') }}?${params.toString()}`;

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

                // Fungsi untuk reset form
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

                // Fungsi untuk inisialisasi status sidebar
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

                // Fungsi untuk modal edit user
                const closeEditModal = () => editUserModal.style.display = 'none';
                closeEditUserBtn.addEventListener('click', closeEditModal);
                cancelEditUserBtn.addEventListener('click', closeEditModal);
                tableContainer.addEventListener('click', function(event) {
                    const editButton = event.target.closest('.edit-btn');
                    if (!editButton || editButton.disabled) return;
                    const userRow = editButton.closest('.products-row');
                    const userId = userRow.dataset.userId;

                    fetch(`/users/${userId}/edit`)
                        .then(response => response.json())
                        .then(user => {
                            editUserForm.reset();
                            editUserForm.querySelector('#edit_user_id').value = user.id;
                            editUserForm.querySelector('#edit_name').value = user.name;
                            editUserForm.querySelector('#edit_email').value = user.email;
                            editUserForm.querySelector('#edit_role').value = user.role;
                            editUserForm.action = `/users/${user.id}`;

                            const roleSelect = editUserForm.querySelector('#edit_role');
                            if (user.role.toLowerCase() === 'admin') {
                                roleSelect.disabled = true;
                            } else {
                                roleSelect.disabled = false;
                            }

                            editUserModal.style.display = 'flex';
                        })
                        .catch(error => console.error('Gagal mengambil data user:', error));
                });

                editUserForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const submitButton = document.getElementById('submitEditUserBtn');
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span>Menyimpan...</span>';

                    const formData = new FormData(this);
                    const actionUrl = this.action;

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
                                fetchUsers(`{{ route('users.index') }}`);
                            } else {
                                if (body.errors) {
                                    Object.keys(body.errors).forEach(key => {
                                        const errorEl = document.getElementById(`edit_${key}_error`);
                                        if (errorEl) errorEl.textContent = body.errors[key][0];
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
                        addUserModal.style.display = 'none';
                        resetAddUserFormState();
                    }
                };
                if (openAddUserBtn) {
                    openAddUserBtn.addEventListener('click', () => {
                        resetAddUserFormState();
                        if (addUserModal) addUserModal.style.display = 'flex';
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


                // ==========================================================
                // EVENT LISTENERS
                // ==========================================================

                // Pencarian Real-time
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

                // Tombol Hapus (Event Delegation)
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
                                        fetchUsers(`{{ route('users.index') }}`);
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

                        fetch("{{ route('users.store') }}", {
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
                                    fetchUsers(`{{ route('users.index') }}`);
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

                    fetch("{{ route('users.validate.field') }}", {
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

                initializeSidebarState();
            });
        </script>
    </body>

    </html>