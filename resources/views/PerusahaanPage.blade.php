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
                width: calc(100% - 2.5rem);
                max-width: 600px;
                max-height: calc(100% - 2.5rem);
                overflow: auto;
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
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem 2rem;
                align-items: start;
            }

            .morph-modal-body::-webkit-scrollbar {
                display: none;
            }

            .morph-modal-body .form-group {
                margin-bottom: 0;
            }

            #icon-form-group {
                grid-column: 1 / -1;
                display: flex;
                flex-direction: column;
                align-items: center;
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

            .btn-secondary {
                color: var(--app-content-main-color);
                background-color: var(--app-content-secondary-color);
                border-color: var(--table-border);
            }

            .nav-tabs .nav-link {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 10px 20px !important;
                cursor: pointer;
                border: none !important;
                background-color: transparent !important;
                color: var(--app-content-main-color) !important;
                opacity: 0.7;
                font-weight: 500;
                font-size: 1.6rem;
                border-bottom: 3px solid transparent !important;
                transition: all 0.2s ease-in-out;
            }

            .nav-tabs .nav-link.active {
                opacity: 1;
                color: var(--action-color) !important;
                border-bottom-color: var(--action-color) !important;
            }

            #icon-picker-button {
                background-color: var(--app-bg);
                border: 1px solid var(--table-border);
                color: var(--app-content-main-color);
                transition: all 0.2s ease;
                width: 8rem;
                height: 8rem;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                border-radius: 6px;
            }

            #icon-picker-button .iconpicker-component {
                display: none;
            }

            #icon-picker-button>i.fas {
                font-size: 3.5rem;
                line-height: 1;
                margin: 0;
            }

            #icon-picker-button:hover {
                border-color: var(--action-color);
                transform: scale(1.05);
            }

            .iconpicker-popover {
                z-index: 9999 !important;
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

            html.light .modal-footer .btn-secondary:hover {
                background-color: #c0392b;
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
                overflow-y: auto;
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .app-content::-webkit-scrollbar {
                display: none;
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

            .app-content-actions .action-button.add-company-btn {
                background-color: var(--action-color) !important;
                color: white;
                border: none;
            }

            .action-button.add-company-btn svg,
            .action-button.add-company-btn i.fas {
                margin-right: 6px !important;
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

            .tableView [id$="TableRowsContainer"] .products-row:nth-child(even) {
                background-color: var(--table-border);
            }

            html.light .tableView [id$="TableRowsContainer"] .products-row:nth-child(even) {
                background-color: #f9f9f9;
            }

            .tableView [id$="TableRowsContainer"] .products-row:hover {
                background-color: var(--sidebar-active-link);
                transition: background-color 0.2s ease-in-out;
            }

            html.light .tableView [id$="TableRowsContainer"] .products-row:hover {
                background-color: #e9ecef;
            }

            .tableView .products-row {
                display: flex;
                align-items: center;
                border-radius: 4px;
                min-width: 1200px;
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

            .tableView .product-cell.cell-nama-perusahaan {
                flex: 2 1 350px;
                min-width: 350px;
            }

            .tableView .product-cell.cell-singkatan {
                flex: 1 1 200px;
                min-width: 200px;
            }

            .tableView .product-cell.cell-dibuat-pada {
                flex: 1 1 200px;
                min-width: 200px;
            }

            .tableView .product-cell.cell-icon {
                flex: 1 1 150px;
                min-width: 150px;
                justify-content: center;
            }

            .tableView .cell-label {
                display: none;
            }

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

            /* Kolom Kiri */
            .table-footer-controls .footer-left {
                justify-content: flex-start;
                flex: 1;
                /* Ambil sisa ruang, tapi bisa menyusut */
                min-width: 220px;
                /* Lebar minimum agar tidak terlalu sempit */
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
                flex-grow: 7;
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

            #mobile-burger-menu {
                display: none;
            }

            .tableView .product-cell.cell-aksi {
                flex: 0 0 120px;
                justify-content: center;
                padding-right: 3rem;
                gap: 8px;
            }

            .action-btn-table {
                padding: 6px 12px;
                border: 1px solid;
                background-color: transparent;
                border-radius: 4px;
                cursor: pointer;
                font-size: 1.3rem;
                font-weight: 500;
                transition: all 0.2s ease-in-out;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            .action-btn-table:hover {
                transform: translateY(-2px);
            }

            .action-btn-table.edit-btn {
                border-color: #ffc107;
                color: #ffc107;
            }

            .action-btn-table.edit-btn:hover {
                background-color: #ffc107;
                color: var(--app-bg);
                box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3);
            }

            html.light .action-btn-table.edit-btn:hover {
                color: #fff;
            }

            .action-btn-table.delete-btn {
                border-color: #e74c3c;
                color: #e74c3c;
            }

            .action-btn-table.delete-btn:hover {
                background-color: #e74c3c;
                color: #fff;
                box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
            }

            .nav-tabs {
                display: flex;
                gap: 4px;
                border-bottom: 2px solid var(--table-border);
                margin-bottom: 20px;
                padding-left: 0;
                list-style: none;
            }

            .nav-link {
                display: flex;
                align-items: center;
                gap: 8px;

                padding: 10px 20px;
                cursor: pointer;
                border: none;
                background-color: transparent;
                color: var(--app-content-main-color);
                opacity: 0.7;
                font-weight: 500;
                font-size: 1.6rem;
                border-bottom: 3px solid transparent;
                transition: all 0.2s ease-in-out;
            }

            .nav-link.active {
                opacity: 1;
                color: var(--action-color);
                border-bottom-color: var(--action-color);
            }

            .tab-content .tab-pane {
                display: none;
            }

            .tab-content .tab-pane.active {
                display: block;
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

                body.modal-open {
                    overflow: hidden;
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
                    display: block;
                    padding: 0.3rem 0;
                    text-align: left;
                    white-space: normal;
                    border-bottom: none;
                }

                .tableView .product-cell:last-child {
                    padding-bottom: 0;
                }

                .tableView .product-cell[data-label]::before {
                    content: '';
                    display: none;
                }

                .tableView .product-cell.cell-username {
                    font-weight: 500;
                    font-size: 1.6rem;
                    color: var(--action-color);
                    margin-bottom: 0.5rem;
                }

                .tableView .product-cell.cell-email,
                .tableView .product-cell.cell-role,
                .tableView .product-cell.cell-tanggal-dibuat {
                    font-size: 1.3rem;
                    opacity: 0.9;
                    padding-left: 2px;
                }

                .tableView .product-cell.cell-no {
                    display: none;
                }

                html.light .tableView .product-cell[data-label]::before {
                    color: #6c757d;
                }

                .tableView .product-cell.cell-no {
                    display: none;
                }

                .pagination-container {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                }

                .modal-content,
                .modal-overlay .modal-content-wrapper {
                    width: calc(100% - 2rem);
                    max-width: 450px;
                    margin: 1rem auto;
                    padding: 1.5rem 1.25rem;
                    max-height: calc(100% - 2rem);
                }

                .modal-title {
                    font-size: 1.4rem;
                }

                .morph-modal-body .form-group label {
                    margin-bottom: 0.5rem;
                }

                .morph-modal-body .form-group input[type="text"],
                .morph-modal-body .form-group input[type="email"],
                .morph-modal-body .form-group input[type="password"],
                .form-group select {
                    font-size: 1.4rem;
                    padding: 10px 12px;
                }

                .modal-footer {
                    padding-top: 1.25rem;
                    margin-top: 1.25rem;
                    gap: 0.8rem;
                    flex-wrap: wrap;
                }

                .modal-footer .btn {
                    font-size: 1.4rem;
                    padding: 10px 16px;
                    flex-grow: 1;
                    text-align: center;
                }

                .tableView .product-cell.cell-aksi {
                    padding-top: 1.5rem;
                    margin-top: 1rem;
                    border-top: 1px solid var(--table-border);
                    justify-content: stretch;
                }

                .tableView .product-cell.cell-aksi .action-buttons-wrapper {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 1rem;
                    width: 100%;
                }

                .tableView .product-cell.cell-aksi .action-btn-table {
                    width: 100%;
                    padding: 12px 10px;
                    justify-content: center;
                }
            }

            @media screen and (min-width: 769px) {
                .action-btn-table span {
                    display: none;
                }
            }
        </style>
    </head>

    <body>
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
                                {{-- Logo Kunci Pas (Wrench) dari Feather Icons --}}
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

                    <div class="app-content-actions">
                        <div class="search-bar-container">
                            <input class="search-bar" id="searchInput" placeholder="Cari nama atau singkatan perusahaan..." type="text" autocomplete="off">
                        </div>
                        <div class="app-content-actions-buttons">
                            <button type="button" id="openAddModalButton" class="action-button add-company-btn" title="Tambah Data Baru">
                                <i class="fas fa-plus"></i>
                                <span id="addButtonText">Tambah Perusahaan</span>
                            </button>
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
                                    {{-- Pagination untuk perusahaan akan dirender oleh JS --}}
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
                                    {{-- Konten tabel jenis barang akan dimuat via AJAX --}}
                                </div>
                                <div class="table-footer-controls" id="itemType-pagination-container">
                                    {{-- Pagination untuk jenis barang akan dirender oleh JS --}}
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

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const isSuperAdmin = {
                    {
                        json_encode(auth() - > user() - > isSuperAdmin())
                    }
                };
                const isAdmin = {
                    {
                        json_encode(auth() - > user() - > isAdmin())
                    }
                };

                $(document).ready(function() {
                    $('#icon-picker-button').iconpicker({
                        iconset: 'fontawesome5',
                        input: '#icon',
                        component: '#icon-picker-button'
                    }).on('change', function(e) {
                        if (e.icon) {
                            $('#icon').val(e.icon).trigger('change');
                        }
                    });
                });

                const config = {
                    company: {
                        fetchUrl: '{{ route("companies.data") }}',
                        storeUrl: '{{ route("companies.store") }}',
                        updateUrl: '/companies/',
                        deleteUrl: '/companies/',
                        editUrl: '/companies/{id}/edit',
                        searchPlaceholder: 'Cari nama atau singkatan perusahaan...',
                        addButtonText: 'Tambah Perusahaan',
                        modalTitleAdd: '<i class="fas fa-building"></i> Tambah Perusahaan Baru',
                        modalTitleEdit: '<i class="fas fa-edit"></i> Edit Perusahaan',
                        modalNamaLabel: 'Nama Lengkap Perusahaan',
                        modalNamaPlaceholder: 'PT NAMA PERUSAHAAN',
                        modalSingkatanMaxlength: 3,
                        formNamaField: 'nama_perusahaan',
                        rowsContainerId: 'companyTableRowsContainer',
                        paginationContainerId: 'company-pagination-container',
                        tableAreaId: 'companyTableArea',
                    },
                    itemType: {
                        fetchUrl: '{{ route("item-types.data") }}',
                        storeUrl: '{{ route("item-types.store") }}',
                        updateUrl: '/item-types/',
                        deleteUrl: '/item-types/',
                        editUrl: '/item-types/{id}/edit',
                        searchPlaceholder: 'Cari nama atau singkatan jenis barang...',
                        addButtonText: 'Tambah Jenis Barang',
                        modalTitleAdd: '<i class="fas fa-tags"></i> Tambah Jenis Barang Baru',
                        modalTitleEdit: '<i class="fas fa-edit"></i> Edit Jenis Barang',
                        modalNamaLabel: 'Nama Jenis Barang',
                        modalNamaPlaceholder: 'Masukan Nama Jenis Barang',
                        modalSingkatanMaxlength: 3,
                        formNamaField: 'nama_jenis',
                        rowsContainerId: 'itemTypeTableRowsContainer',
                        paginationContainerId: 'itemType-pagination-container',
                        tableAreaId: 'itemTypeTableArea'
                    }
                };

                let currentTab = 'company';
                let debounceTimer;

                const modal = document.getElementById('dataModal');
                const dataForm = document.getElementById('dataForm');
                const searchInput = document.getElementById('searchInput');

                const updateUIForTab = () => {
                    const conf = config[currentTab];
                    searchInput.placeholder = conf.searchPlaceholder;
                    document.getElementById('addButtonText').textContent = conf.addButtonText;

                    document.querySelectorAll('.nav-link').forEach(link => {
                        link.classList.toggle('active', link.dataset.tab === currentTab);
                    });
                    document.querySelectorAll('.tab-pane').forEach(pane => {
                        pane.classList.toggle('active', pane.id === `${currentTab}-tab-pane`);
                    });

                    const openAddModalButton = document.getElementById('openAddModalButton');
                    if (openAddModalButton) {
                        // Hanya Super Admin yang bisa melihat tombol Tambah di kedua tab
                        openAddModalButton.style.display = isSuperAdmin ? '' : 'none';
                    }
                };

                const fetchData = (page = 1) => {
                    const conf = config[currentTab];
                    const search = searchInput.value;
                    const perPageSelect = document.querySelector(`#${conf.tableAreaId} #rows-per-page-select`);
                    const perPage = perPageSelect ? perPageSelect.value : 20;
                    const url = `${conf.fetchUrl}?page=${page}&per_page=${perPage}&search=${search}&_=${new Date().getTime()}`;

                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                // Tambahkan detail error dari server jika ada
                                return response.json().then(err => {
                                    throw new Error(`HTTP error! Status: ${response.status}, Message: ${err.message || 'Unknown error'}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.table_html && data.pagination) {
                                document.getElementById(conf.rowsContainerId).innerHTML = data.table_html;
                                document.getElementById(conf.paginationContainerId).innerHTML = renderPaginationControls(data.pagination);
                                console.log('DOM updated successfully for rows and pagination.'); // DEBUG
                            } else {
                                console.error('Server response is missing table_html or pagination data.'); // DEBUG
                            }
                        })
                        .catch(error => {
                            alert(`Gagal memuat data. Pesan Error: ${error.message || 'Lihat console untuk detail.'}`); // User-friendly alert
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
                    const {
                        from,
                        to,
                        total,
                        per_page,
                        links
                    } = pagination;
                    let linksHtml = links.map(link => {
                        let label = link.label.replace('« Previous', '<').replace('Next »', '>');
                        return `<button class="pagination-btn ${link.active ? 'active' : ''}" data-page="${link.url ? new URL(link.url).searchParams.get('page') : ''}" ${!link.url ? 'disabled' : ''}>${label}</button>`;
                    }).join('');
                    return `
            <div class="footer-section footer-left">
                <div class="rows-per-page-wrapper"><label for="rows-per-page-select">Baris:</label><select id="rows-per-page-select">${[10,20,30,50].map(v => `<option value="${v}" ${per_page == v ? 'selected' : ''}>${v}</option>`).join('')}</select></div>
            </div>
            <div class="footer-section footer-center">${linksHtml}</div>
            <div class="footer-section footer-right"><div class="pagination-info"><b>${from}</b>-<b>${to}</b> dari <b>${total}</b></div></div>`;
                };

                const openModal = () => {
                    modal.style.display = 'flex';
                    document.body.classList.add('modal-open');
                };
                const closeModal = () => {
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                };

                const resetFormAndErrors = () => {
                    dataForm.reset();
                    document.getElementById('icon-form-group').style.display = 'none';
                    dataForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    dataForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                };

                const handleValidationErrors = (errors) => {
                    dataForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    dataForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                    Object.keys(errors).forEach(key => {
                        const input = dataForm.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const errorDiv = input.nextElementSibling;
                            if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                                errorDiv.textContent = errors[key][0];
                            }
                        }
                    });
                };

                const setupSmartModalClosure = (modalElement, closeFunction) => {
                    if (!modalElement) return;
                    let isMouseDownOnOverlay = false;

                    modalElement.addEventListener('mousedown', function(event) {
                        if (event.target === modalElement) {
                            isMouseDownOnOverlay = true;
                        }
                    });

                    modalElement.addEventListener('mouseup', function(event) {
                        if (event.target === modalElement && isMouseDownOnOverlay) {
                            closeFunction();
                        }
                        isMouseDownOnOverlay = false;
                    });

                    modalElement.addEventListener('mouseleave', function() {
                        isMouseDownOnOverlay = false;
                    });
                };

                const setupAddModal = () => {
                    const conf = config[currentTab];
                    resetFormAndErrors();

                    document.getElementById('modalTitle').innerHTML = conf.modalTitleAdd;
                    dataForm.setAttribute('action', conf.storeUrl);
                    dataForm.querySelector('#formMethod').value = 'POST';
                    dataForm.querySelector('#dataId').value = '';

                    const namaInput = dataForm.querySelector('#nama');
                    const singkatanInput = dataForm.querySelector('#singkatan');

                    namaInput.name = conf.formNamaField;
                    namaInput.placeholder = conf.modalNamaPlaceholder;
                    dataForm.querySelector('#namaLabel').textContent = conf.modalNamaLabel;

                    singkatanInput.maxLength = conf.modalSingkatanMaxlength;

                    const iconFormGroup = document.getElementById('icon-form-group');
                    if (currentTab === 'itemType') {
                        iconFormGroup.style.display = 'flex';
                        const defaultIcon = 'fas fa-heart';
                        $('#icon').val(defaultIcon);
                        $('#icon-picker-button').find('i').attr('class', 'fas fa-fw ' + defaultIcon.replace('fas ', ''));
                    } else {
                        iconFormGroup.style.display = 'none';
                    }

                    openModal();
                };

                const setupEditModal = (id) => {
                    const conf = config[currentTab];
                    resetFormAndErrors();

                    const iconFormGroup = document.getElementById('icon-form-group');
                    if (currentTab === 'itemType') {
                        iconFormGroup.style.display = 'flex';
                    } else {
                        iconFormGroup.style.display = 'none';
                    }

                    fetch(conf.editUrl.replace('{id}', id))
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('modalTitle').innerHTML = conf.modalTitleEdit;
                            dataForm.setAttribute('action', conf.updateUrl + id);
                            dataForm.querySelector('#formMethod').value = 'PUT';
                            dataForm.querySelector('#dataId').value = id;

                            const namaInput = dataForm.querySelector('#nama');
                            const singkatanInput = dataForm.querySelector('#singkatan');

                            namaInput.name = conf.formNamaField;
                            namaInput.value = data[conf.formNamaField];
                            namaInput.placeholder = conf.modalNamaPlaceholder;
                            dataForm.querySelector('#namaLabel').textContent = conf.modalNamaLabel;

                            singkatanInput.value = data.singkatan;
                            singkatanInput.maxLength = conf.modalSingkatanMaxlength;

                            if (currentTab === 'itemType') {
                                const selectedIcon = data.icon || 'fas fa-question-circle';
                                $('#icon').val(selectedIcon);
                                $('#icon-picker-button').find('i').attr('class', 'fas fa-fw ' + selectedIcon.replace('fas ', ''));
                            }

                            openModal();
                        }).catch(err => alert('Gagal memuat data untuk diedit.'));
                };

                const handleDelete = (id) => {
                    if (!confirm('Anda yakin ingin menghapus data ini?')) return;
                    const conf = config[currentTab];
                    fetch(conf.deleteUrl + id, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: new URLSearchParams({
                                '_method': 'DELETE'
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.success);
                                fetchData(1);
                            } else {
                                alert(data.error || 'Gagal menghapus data.');
                            }
                        }).catch(err => alert('Terjadi kesalahan fatal.'));
                };

                document.getElementById('dataForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.disabled = true;
                    submitBtn.querySelector('span').textContent = 'Menyimpan...';

                    const actionUrl = dataForm.getAttribute('action');
                    const formData = new FormData(dataForm);
                    const method = document.getElementById('formMethod').value;
                    if (method.toUpperCase() !== 'POST') {
                        formData.append('_method', method);
                    }

                    fetch(actionUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw {
                                        status: response.status,
                                        body: err
                                    };
                                });
                            }
                            return response.json().then(data => ({
                                status: response.status,
                                body: data
                            }));
                        })
                        .then(({
                            status,
                            body
                        }) => {
                            alert(body.success || 'Aksi berhasil!');
                            closeModal();
                            const conf = config[currentTab];
                            const paginationContainer = document.getElementById(conf.paginationContainerId);
                            const activeButton = paginationContainer.querySelector('.pagination-btn.active');
                            const currentPage = activeButton ? activeButton.dataset.page : 1;
                            fetchData(currentPage);
                        })
                        .catch(err => {
                            if (err.status === 422) {
                                handleValidationErrors(err.body.errors);
                            } else {
                                alert(err.body.error || err.body.message || 'Terjadi kesalahan server yang tidak diketahui.');
                            }
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            submitBtn.querySelector('span').textContent = 'Simpan';
                        });
                });

                searchInput.addEventListener('keyup', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => fetchData(1), 300);
                });

                document.querySelector('.nav-tabs').addEventListener('click', (e) => {
                    const navButton = e.target.closest('.nav-link');
                    if (navButton) {
                        currentTab = navButton.dataset.tab;
                        updateUIForTab();
                        const rowsContainer = document.getElementById(config[currentTab].rowsContainerId);
                        if (rowsContainer.childElementCount === 0) {
                            fetchData(1);
                        }
                    }
                });

                document.querySelector('.tab-content').addEventListener('click', function(e) {
                    const conf = config[currentTab];
                    const target = e.target;

                    const editBtn = target.closest('.edit-btn');
                    if (editBtn) {
                        e.preventDefault();
                        setupEditModal(editBtn.dataset.id);
                        return;
                    }

                    const deleteBtn = target.closest('.delete-btn');
                    if (deleteBtn) {
                        e.preventDefault();
                        const id = deleteBtn.dataset.id;
                        if (!confirm('Anda yakin ingin menghapus data ini?')) return;

                        fetch(conf.deleteUrl + id, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: new URLSearchParams({
                                    '_method': 'DELETE'
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    alert(data.success);
                                    // INI PERBAIKANNYA. Panggil fetchData() dengan halaman yang sedang aktif.
                                    const currentPage = document.querySelector('.pagination-btn.active')?.dataset.page || 1;
                                    fetchData(currentPage);
                                } else {
                                    alert(data.error || 'Gagal menghapus data.');
                                }
                            })
                            .catch(err => alert('Terjadi kesalahan.'));
                        return;
                    }

                    const paginationBtn = target.closest('.pagination-btn');
                    if (paginationBtn && !paginationBtn.disabled) {
                        fetchData(paginationBtn.dataset.page);
                        return;
                    }
                });

                document.querySelector('.tab-content').addEventListener('change', (e) => {
                    if (e.target.matches('#rows-per-page-select')) {
                        fetchData(1);
                    }
                });

                document.getElementById('openAddModalButton').addEventListener('click', setupAddModal);
                modal.querySelectorAll('[data-dismiss="modal"]').forEach(btn => btn.addEventListener('click', closeModal));
                setupSmartModalClosure(modal, closeModal);
                document.addEventListener('keydown', (e) => {
                    if (e.key === "Escape") {
                        if (modal.style.display === 'flex') {
                            closeModal();
                        }
                    }
                });

                const initialPaginationData = JSON.parse(`{!! json_encode($companies->toArray()) !!}`);
                document.getElementById(config.company.paginationContainerId).innerHTML = renderPaginationControls(initialPaginationData);
                const sidebarElement = document.querySelector('.sidebar');
                const burgerMenuButton = document.getElementById('burger-menu');
                const mobileBurgerButton = document.getElementById('mobile-burger-menu');
                const modeSwitch = document.querySelector('.mode-switch');

                const initializeSidebarState = () => {
                    if (!sidebarElement || window.innerWidth <= 1024) return;
                    const isCollapsed = localStorage.getItem('sidebarCollapsedITventory') === 'true';
                    sidebarElement.classList.toggle('collapsed', isCollapsed);
                    if (burgerMenuButton) burgerMenuButton.classList.toggle('active', !isCollapsed);
                };
                const applyTheme = () => {
                    const currentTheme = localStorage.getItem('theme');
                    document.documentElement.classList.toggle('light', currentTheme === 'light');
                    if (modeSwitch) modeSwitch.classList.toggle('active', currentTheme === 'light');
                };

                if (burgerMenuButton) {
                    burgerMenuButton.addEventListener('click', () => {
                        if (window.innerWidth > 1024) {
                            sidebarElement.classList.toggle('collapsed');
                            localStorage.setItem('sidebarCollapsedITventory', sidebarElement.classList.contains('collapsed'));
                        }
                        burgerMenuButton.classList.toggle('active');
                    });
                }
                if (mobileBurgerButton) {
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
                        if (mobileBurgerButton) mobileBurgerButton.classList.remove('active');
                    }
                });
                if (modeSwitch) {
                    modeSwitch.addEventListener('click', () => {
                        document.documentElement.classList.toggle('light');
                        modeSwitch.classList.toggle('active');
                        const theme = document.documentElement.classList.contains('light') ? 'light' : 'dark';
                        localStorage.setItem('theme', theme);
                    });
                }

                applyTheme();
                initializeSidebarState();
            });
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-iconpicker/1.10.0/js/bootstrap-iconpicker.bundle.min.js"></script>
    </body>

    </html>