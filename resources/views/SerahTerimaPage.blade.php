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
        <style>
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
                --table-border: #e9ecef;
            }

            html {
                box-sizing: border-box;
                font-size: 62.5%;
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

            .modal {
                display: none;
                position: fixed;
                z-index: 1050;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.6);
                justify-content: center;
                align-items: center;
            }

            .modal.show {
                display: flex;
            }

            body.modal-open {
                overflow: hidden;
            }

            .modal-content {
                background-color: var(--app-content-secondary-color);
                color: var(--app-content-main-color);
                margin: auto;
                padding: 2.5rem;
                border: none;
                border-radius: 8px;
                width: 90%;
                max-width: 70rem;
                box-shadow: var(--filter-shadow);
                position: relative;
                max-height: 90vh;
                display: flex;
                flex-direction: column;
            }

            html.light .modal-content {
                background-color: #fff;
            }

            .modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-bottom: 15px;
                border-bottom: 1px solid var(--table-border);
                margin-bottom: 20px;
            }

            .modal-header-content {
                display: flex;
                align-items: center;
                gap: 1.5rem;
            }

            .modal-header-logo {
                width: 28px;
                height: 28px;
            }

            html.light .modal-header {
                border-bottom-color: #eee;
            }

            .modal-title {
                font-size: 2rem;
                font-weight: 500;
            }

            .close-button {
                font-size: 2.5rem;
                background: none;
                border: none;
                cursor: pointer;
                line-height: 1;
                color: var(--app-content-main-color);
            }

            .modal-body {
                overflow-y: auto;
                scrollbar-width: none;
                flex-grow: 1;
            }

            .modal-header-icon {
                font-size: 2.6rem;
                color: var(--action-color);
            }

            .modal-body form label {
                display: block;
                margin-bottom: 8px;
                font-weight: 500;
                font-size: 1.4rem;
            }

            .modal-body form .form-group {
                margin-bottom: 1.5rem;
            }

            .modal-body form .form-group-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            .modal-body form input[type="text"],
            .modal-body form textarea {
                width: 100%;
                padding: 1rem;
                border: 1px solid var(--table-border);
                border-radius: 4px;
                background-color: var(--app-bg);
                color: var(--app-content-main-color);
                font-size: 1.4rem;
            }

            html.light .modal-body form input[type="text"],
            html.light .modal-body form textarea {
                background-color: #fff;
                border-color: #ccc;
            }

            .modal-body form input:focus,
            .modal-body form textarea:focus {
                border-color: var(--action-color);
                outline: none;
            }

            .modal-body form input:disabled {
                background-color: var(--app-bg);
                opacity: 0.6;
                cursor: not-allowed;
            }

            html.light .modal-body form input:disabled {
                background-color: #e9ecef;
            }

            #suratModal .form-section {
                background-color: var(--app-bg);
                border: 1px solid var(--table-border);
                border-radius: 8px;
                padding: 2rem;
                margin-bottom: 2rem;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                transition: box-shadow 0.3s ease, transform 0.3s ease;
            }

            html.dark #suratModal .form-section {
                box-shadow: var(--filter-shadow);
            }

            #suratModal .form-section:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            }

            html.light #suratModal .form-section {
                background-color: #f8f9fa;
                border-color: #e9ecef;
            }

            .modal-footer {
                padding-top: 1.5rem;
                border-top: 1px solid var(--table-border);
                margin-top: 2rem;
                text-align: right;
                display: flex;
                justify-content: flex-end;
                gap: 1.2rem;
                position: relative;
            }

            .modal-footer .btn {
                padding: 1rem 2.4rem;
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

            .btn-primary:disabled {
                background-color: #5a6268;
                opacity: 0.65;
                cursor: not-allowed;
                transform: none;
                box-shadow: none;
            }

            .btn-primary:disabled:hover::after {
                content: attr(data-tooltip);
                position: absolute;
                bottom: calc(100% + 10px);
                right: 20px;
                background-color: #343a40;
                color: #fff;
                padding: 8px 12px;
                border-radius: 6px;
                font-size: 1.2rem;
                font-weight: 500;
                white-space: nowrap;
                z-index: 100;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            .btn-primary:disabled:hover::before {
                content: '';
                position: absolute;
                bottom: calc(100% + 5px);
                right: 30px;
                border-width: 5px;
                border-style: solid;
                border-color: #343a40 transparent transparent transparent;
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

            #suratModal .form-section {
                background-color: var(--app-content-secondary-color);
                border: 1px solid var(--table-border);
                border-radius: 8px;
                padding: 2rem;
                margin-bottom: 2rem;
            }

            #suratModal .form-section {
                background-color: var(--app-bg);
                border: 1px solid var(--table-border);
                border-radius: 8px;
                padding: 2rem;
                margin-bottom: 2rem;
            }

            #suratModal .form-section:last-of-type {
                margin-bottom: 0;
            }

            #suratModal .form-section h3 {
                font-size: 1.6rem;
                margin-top: 0;
                margin-bottom: 2rem;
                padding-bottom: 1rem;
                color: var(--action-color);
                border-bottom: 1px solid var(--table-border);
            }

            html.light #suratModal .form-section {
                background-color: #f8f9fa;
                border-color: #e9ecef;
            }

            html.dark #suratModal .form-section {
                box-shadow: var(--filter-shadow);
            }

            html.light #suratModal .form-section {
                background-color: #f8f9fa;
                border-color: #e9ecef;
            }

            #detailSuratModal .detail-split-layout {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 2rem;
                width: 100%;
            }

            #detailSuratModal .detail-section {
                background-color: var(--app-bg);
                border: 1px solid var(--table-border);
                border-radius: 8px;
                padding: 2rem;
                margin-bottom: 2rem;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                transition: box-shadow 0.3s ease, transform 0.3s ease;
            }

            html.light #detailSuratModal .detail-section {
                background-color: #f8f9fa;
                border-color: #e9ecef;
            }

            html.dark #detailSuratModal .detail-section {
                box-shadow: var(--filter-shadow);
            }

            #detailSuratModal .detail-section:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            }

            #detailSuratModal .detail-section h3 {
                font-size: 1.6rem;
                color: var(--action-color);
                border-bottom: 1px solid var(--table-border);
                padding-bottom: 1rem;
                margin-bottom: 1.5rem;
                font-weight: 500;
            }

            #detailSuratModal .detail-item {
                display: flex;
                margin-bottom: 1rem;
                font-size: 1.5rem;
                line-height: 1.6;
            }

            #detailSuratModal .detail-item dt {
                font-weight: 500;
                color: var(--app-content-main-color);
                opacity: 0.7;
                width: 150px;
                flex-shrink: 0;
            }

            #detailSuratModal .detail-item dd {
                flex-grow: 1;
                font-weight: 400;
            }

            #detailSuratModal .detail-barang-box {
                display: flex;
                align-items: center;
                gap: 2rem;
                padding-top: 1rem;
            }

            #detailSuratModal .detail-barang-icon {
                font-size: 4rem;
                color: var(--action-color);
                flex-shrink: 0;
            }

            #detailSuratModal .detail-barang-info .header {
                font-size: 1.8rem;
                font-weight: 600;
            }

            #detailSuratModal .detail-barang-info .subheader {
                font-size: 1.4rem;
                opacity: 0.7;
            }

            #detailSuratModal .pihak-info {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            #detailSuratModal .pihak-nama {
                font-size: 1.6rem;
                font-weight: 600;
                margin: 0;
                word-break: break-word;
            }

            #detailSuratModal .pihak-subinfo {
                font-size: 1.3rem;
                color: var(--app-content-main-color);
                opacity: 0.7;
                margin: 0;
            }

            .invalid-feedback {
                color: #dc3545;
                font-size: 1.2rem;
                margin-top: .4rem;
            }

            .is-invalid {
                border-color: #dc3545 !important;
            }

            #barang-info-box {
                display: none;
                padding: 1.5rem;
                margin-top: 1.5rem;
                background-color: var(--app-bg);
                border: 1px dashed var(--action-color);
                border-radius: 6px;
            }

            html.light #barang-info-box {
                background-color: #f0f8ff;
            }

            #barang-info-box p {
                margin: 0 0 .5rem 0;
                font-size: 1.3rem;
            }

            #barang-info-box strong {
                font-weight: 600;
            }

            #barang-info-box .error {
                color: #ffc107;
            }

            #barang-info-box .used {
                color: #dc3545;
            }

            #dashboard-page {
                height: 100vh;
                display: flex;
            }

            .app-container {
                width: 100%;
                height: 100%;
                display: flex;
                overflow: hidden;
            }

            .pihak-container {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.8rem;
                font-weight: 500;
                width: 100%;
            }

            .panah-serah-terima {
                color: var(--action-color);
                flex-shrink: 0;
            }

            .pihak-pemberi,
            .pihak-penerima {
                text-align: center;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .no-surat-mobile {
                font-size: 1.2rem;
                opacity: 0.7;
                text-align: right;
                width: 100%;
                margin-bottom: 4px;
            }

            .asset-separator-mobile {
                opacity: 0.5;
                margin: 0 0.5rem;
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
                    flex-basis 0.3s ease-in-out;
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

            .sidebar.collapsed .sidebar-list-item a span {
                opacity: 0;
                width: 0;
                overflow: hidden;
                white-space: nowrap;
                margin-left: -10px;
            }

            .sidebar.collapsed .sidebar-list-item a {
                justify-content: center;
            }

            .sidebar.collapsed .sidebar-list-item a svg {
                margin-right: 0;
            }

            .sidebar.collapsed .account-info {
                justify-content: center;
            }

            .sidebar.collapsed .account-info-name {
                display: none;
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
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: auto;
                overflow: hidden;
                transition: opacity 0.2s ease-in-out 0.1s,
                    transform 0.2s ease-in-out 0.1s;
            }

            .app-icon .app-logo-svg {
                width: 30px;
                height: 30px;
                flex-shrink: 0;
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
                /* Ini yang memberi jarak ikon dan teks */
                flex-shrink: 0;
                transition: margin-right 0.3s ease-in-out;
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

            .sidebar-list-item:hover {
                background-color: var(--sidebar-hover-link);
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

            .app-content-header-actions-right {
                display: flex;
                align-items: center;
            }

            .mode-switch.action-button {
                margin-right: 8px;
            }

            .mode-switch.action-button svg {
                width: 20px;
                height: 20px;
            }

            .mode-switch .moon {
                fill: var(--app-content-main-color);
            }

            .mode-switch.active .moon {
                fill: none;
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

            .app-content-headerButton:hover {
                background-color: var(--action-color-hover);
            }

            .app-content-actions {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1.6rem 0.4rem;
                gap: 1.6rem;
            }

            .search-bar-container {
                position: relative;
                flex-grow: 1;
            }

            .search-bar {
                background-color: var(--app-content-secondary-color);
                border: 1px solid var(--app-content-secondary-color);
                color: var(--app-content-main-color);
                font-size: 1.4rem;
                padding: .4rem 1rem .4rem 3.2rem;
                height: 32px;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-search'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E");
                background-size: 16px;
                background-repeat: no-repeat;
                background-position: left 10px center;
                width: 100%;
            }

            html.light .search-bar {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%231f1c2e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-search'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E");
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
                padding: 0 8px;
                cursor: pointer;
                transition: .2s;
            }

            .action-button:hover {
                border-color: var(--action-color-hover);
            }


            .action-button.add-surat-btn {
                background-color: var(--action-color);
                color: white;
                border: none;
                padding: 0 1.6rem;
                height: 32px;
                border-radius: 4px;
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 1.4rem;
                cursor: pointer;
            }

            .products-area-wrapper {
                width: 100%;
                flex-grow: 1;
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
                min-width: 1400px;
                z-index: 10;
                border-bottom: 2px solid var(--action-color);
                font-weight: 500;
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
                min-width: 1400px;
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

            .tableView .product-cell.cell-no-surat {
                flex: 1 1 180px;
                min-width: 140px;
            }

            .tableView .product-cell.cell-penerima {
                flex: 1 1 200px;
                min-width: 160px;
            }

            .tableView .product-cell.cell-pemberi {
                flex: 1 1 200px;
                min-width: 160px;
            }

            .tableView .product-cell.cell-merek-jenis {
                flex: 1 1 200px;
                min-width: 160px;
            }

            .tableView .product-cell.cell-asset {
                flex: 1 1 200px;
                min-width: 160px;
            }

            .tableView .product-cell.cell-serial-number {
                flex: 1 1 180px;
                min-width: 140px;
            }

            .tableView .product-cell.cell-aksi {
                flex: 0 0 180px;
                min-width: 160px;
                justify-content: center;
                gap: 8px;
                overflow: visible;
            }


            .action-btn-table {
                padding: 6px 12px;
                margin: 0 4px;
                border: 1px solid;
                background-color: transparent;
                border-radius: 4px;
                cursor: pointer;
                font-size: 1.3rem;
                font-weight: 500;
                transition: all 0.2s ease-in-out;
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            .action-btn-table:hover {
                transform: translateY(-2px);
            }

            .action-btn-table.download-btn {
                border-color: #3498db;
                color: #3498db;
                text-decoration: none;
            }

            .action-btn-table.download-btn:hover {
                background-color: #3498db;
                color: #fff;
                box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
            }

            .action-btn-table.detail-btn {
                border-color: #5cb85c;
                color: #5cb85c;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
            }

            .action-btn-table.detail-btn:hover {
                background-color: #5cb85c;
                color: #fff;
                box-shadow: 0 4px 10px rgba(92, 184, 92, 0.3);
            }

            .action-btn-table.delete-btn {
                border-color: #e74c3c;
                color: #e74c3c;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
            }

            .action-btn-table.delete-btn:hover {
                background-color: #e74c3c;
                color: #fff;
                box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
            }

            .action-btn-table.edit-btn {
                border-color: #27ae60;
                color: #27ae60;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
            }

            .action-btn-table.edit-btn:hover {
                background-color: #27ae60;
                color: #fff;
            }

            .action-btn-table.delete-btn {
                border-color: #e74c3c;
                color: #e74c3c;
            }

            .action-btn-table.delete-btn:hover {
                background-color: #e74c3c;
                color: #fff;
            }

            .action-btn-table.download-btn {
                border-color: #3498db;
                color: #3498db;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
            }

            .action-btn-table.download-btn:hover {
                background-color: #3498db;
                color: #fff;
            }

            .products-header .sortable-header {
                cursor: pointer;
                user-select: none;
                position: relative;
                padding-right: 20px;
            }

            .products-header .sortable-header::after {
                content: '';
                display: inline-block;
                width: 16px;
                height: 16px;
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
                opacity: 0.4;
                background-size: contain;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2'%3E%3Cpath d='M8 9l4-4 4 4M16 15l-4 4-4-4'/%3E%3C/svg%3E");
            }

            html.light .products-header .sortable-header::after {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231f1c2e' stroke-width='2'%3E%3Cpath d='M8 9l4-4 4 4M16 15l-4 4-4-4'/%3E%3C/svg%3E");
            }

            .products-header .sortable-header.sorted-asc::after {
                opacity: 1;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232869ff' stroke-width='2.5'%3E%3Cpath d='M12 19V5M5 12l7-7 7 7'/%3E%3C/svg%3E");
            }

            .products-header .sortable-header.sorted-desc::after {
                opacity: 1;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232869ff' stroke-width='2.5'%3E%3Cpath d='M12 5v14M19 12l-7 7-7-7'/%3E%3C/svg%3E");
            }

            .table-footer-controls {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 16px 8px;
                font-size: 1.4rem;
                flex-shrink: 0;
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

            #rows-per-page-select {
                padding: 6px 12px;
                border: 1px solid var(--table-border);
                background-color: var(--app-bg);
                color: var(--app-content-main-color);
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
                transition: all 0.25s ease-in-out;
            }

            .table-footer-controls .pagination-btn:hover:not(.active):not(:disabled) {
                background-color: var(--sidebar-active-link);
                border-color: var(--action-color);
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
            }

            .table-footer-controls .footer-right {
                justify-content: flex-end;
                flex: 1;
                min-width: 200px;
            }

            .rows-per-page-wrapper {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            #rows-per-page-select {
                padding: 6px 12px;
                border: 1px solid var(--table-border);
                background-color: var(--app-bg);
                color: var(--app-content-main-color);
            }

            .pagination-buttons {
                display: flex;
                gap: 4px;
            }

            .pagination-btn {
                min-width: 32px;
                height: 32px;
                padding: 0 8px;
                border: 1px solid var(--table-border);
                background-color: transparent;
                color: var(--app-content-main-color);
                border-radius: 4px;
                cursor: pointer;
            }

            .pagination-btn.active {
                background-color: var(--action-color);
                border-color: var(--action-color);
                color: #fff;
            }

            .pagination-btn:disabled {
                opacity: 0.5;
                cursor: not-allowed;
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

            #mobile-burger-menu {
                display: none;
            }

            .form-group {
                position: relative;
            }

            .invalid-feedback {
                display: block;
                position: absolute;
                top: 100%;
                left: 0;
                margin-top: 5px;
                background-color: #e74c3c;
                color: white;
                padding: 8px 12px;
                border-radius: 6px;
                font-size: 1.2rem;
                font-weight: 500;
                z-index: 10;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.2s ease, transform 0.2s ease;
                transform: translateY(5px);
                white-space: nowrap;
            }

            .invalid-feedback::after {
                content: '';
                position: absolute;
                bottom: 100%;
                left: 20px;
                border-width: 5px;
                border-style: solid;
                border-color: transparent transparent #e74c3c transparent;
            }

            .form-group .is-invalid:hover+.invalid-feedback,
            .form-group .is-invalid:focus+.invalid-feedback {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            #barang-info-box.info-box-error {
                background-color: rgba(231, 76, 60, 0.1);
                border: 1px dashed #e74c3c;
                text-align: center;
                padding: 2rem;
            }

            #barang-info-box.info-box-error h4 {
                margin: 0 0 0.8rem 0;
                color: #e74c3c;
                font-size: 1.5rem;
                font-weight: 600;
            }

            #barang-info-box.info-box-error i {
                margin-right: 0.8rem;
            }

            #barang-info-box.info-box-error p {
                font-size: 1.3rem;
                opacity: 0.8;
                line-height: 1.5;
                margin: 0;
            }

            .mobile-card-view {
                display: none;
            }

            @media screen and (max-width: 768px),
            screen and (orientation: landscape) and (max-height: 500px) {

                .tableView .products-header,
                .desktop-view {
                    display: none;
                }

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

                .products-area-wrapper.tableView {
                    overflow: visible;
                }

                .tableView .products-header {
                    display: none;
                }

                .tableView .products-row {
                    display: block;
                    padding: 2.5rem;
                    margin-bottom: 2rem;
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

                .mobile-card-view {
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;
                }

                .mobile-card-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    gap: 1rem;
                }

                .mobile-card-content {
                    font-weight: 400;
                    font-size: 1.5rem;
                    text-align: left;
                    color: var(--app-content-main-color);
                    display: flex;
                    flex-direction: column;
                    gap: 2rem;
                }

                .mobile-card-content .pihak-container {
                    text-align: center;
                }

                .card-actions {
                    margin-top: 0.5rem;
                    padding-top: 1.5rem;
                    border-top: 1px solid var(--table-border);
                }

                .card-actions .action-buttons-wrapper {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 1rem;
                }

                .card-actions .action-buttons-wrapper.is-super-admin-actions {
                    grid-template-columns: 1fr 1fr;
                }

                .tableView .product-cell.cell-no-surat,
                .tableView .product-cell.cell-penerima,
                .tableView .product-cell.cell-pemberi,
                .tableView .product-cell.cell-asset,
                .tableView .product-cell.cell-serial-number {
                    display: none !important;
                }

                .tableView .product-cell.cell-pihak-info,
                .tableView .product-cell.cell-asset-info {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-end;
                }

                .tableView .product-cell.cell-aksi .action-buttons-wrapper {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 1rem;
                    width: 100%;
                }

                .tableView .product-cell[data-label]::before {
                    display: none;
                }

                .tableView .product-cell.cell-merek-jenis {
                    font-weight: 100;
                    margin-top: 1rem;
                }

                .tableView .product-cell.cell-asset-info {
                    align-items: flex-start;
                    font-size: 1.3rem;
                    opacity: 0.8;
                }

                .tableView .product-cell.cell-aksi {
                    padding-top: 1.5rem;
                    margin-top: 1.5rem;
                    border-top: 1px solid var(--table-border);
                }

                .action-btn-table span {
                    margin-left: 8px;
                }

                .tableView .product-cell.cell-aksi .action-buttons-wrapper.is-super-admin-actions {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 1rem;
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

                .tableView .products-header {
                    min-width: 0px;
                }

                .tableView .products-row {
                    min-width: 0px;
                }
            }

            @media screen and (min-width: 769px) {
                .product-cell.cell-pihak-info {
                    display: none !important;
                }

                .product-cell.cell-asset-info {
                    display: none !important;
                }

                .desktop-view {
                    display: contents;
                }
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
                }

                #mobile-burger-menu {
                    display: flex;
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
                    gap: 16px;
                }

                #burger-menu {
                    display: none;
                }

                #mobile-burger-menu {
                    display: flex;
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

            .burger-button {
                display: flex;
                flex-direction: column;
                justify-content: space-around;
                width: 30px;
                height: 28px;
                background: none;
                border: none;
                cursor: pointer;
            }

            .burger-button span {
                display: block;
                width: 100%;
                height: 3px;
                background-color: var(--app-content-main-color);
                border-radius: 3px;
                transition: all 0.3s;
            }
        </style>
    </head>

    <body>
        <div id="dashboard-page">
            <div class="app-container">
                <div class="sidebar">
                    <div class="sidebar-header">
                        <div class="app-icon">
                            <img src="{{ asset('img/Scuto-logo.svg') }}" alt="Scuto Logo" class="app-logo-svg">
                            <span class="app-name-text">Scuto Asset</span>
                        </div>
                        <button id="burger-menu" class="burger-button" title="Toggle Sidebar">
                            <span></span><span></span><span></span>
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
                    {{-- PASTE KODE INFO PROFIL BARU DI SINI --}}
                    <div class="account-info">
                        <div class="account-info-picture">
                            <img src="{{ asset('img/Logo-scuto.png') }}" alt="Account">
                        </div>
                        <div class="account-info-name">{{ Auth::user()->name }}</div>
                    </div>
                </div>

                <div class="app-content">
                    <div class="app-content-header">
                        <button id="mobile-burger-menu" class="burger-button" title="Buka Menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                        <h1 class="app-content-headerText">Surat Serah Terima Aset</h1>
                        <div class="app-content-header-actions-right">
                            <button class="mode-switch action-button" title="Switch Theme">
                                <svg class="moon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                                    <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                                </svg>
                            </button>
                            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Yakin ingin keluar?');">
                                @csrf
                                <button type="submit" class="app-content-headerButton" style="background-color: #e74c3c;">Logout</button>
                            </form>
                        </div>
                    </div>

                    <div class="app-content-actions">
                        <div class="search-bar-container">
                            <input class="search-bar" id="searchInput" placeholder="Cari no surat, nama, no asset..." type="text">
                        </div>
                        <div class="app-content-actions-buttons">
                            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                            <button id="openAddSuratModalButton" class="action-button add-surat-btn">
                                <i class="fas fa-plus-circle"></i><span>Tambah Surat</span>
                            </button>
                            @endif
                        </div>
                    </div>

                    <div class="products-area-wrapper tableView">
                        <div class="products-header">
                            <div class="product-cell cell-no sortable-header" data-sort-by="no">No</div>
                            <div class="product-cell cell-no-surat sortable-header" data-sort-by="no_surat">No. Surat</div>
                            <div class="product-cell cell-penerima sortable-header" data-sort-by="nama_penerima">Nama Penerima</div>
                            <div class="product-cell cell-pemberi sortable-header" data-sort-by="nama_pemberi">Nama Pemberi</div>
                            <div class="product-cell cell-merek-jenis sortable-header" data-sort-by="merek">Merek</div>
                            <div class="product-cell cell-asset sortable-header" data-sort-by="no_asset">No. Asset</div>
                            <div class="product-cell cell-serial-number sortable-header" data-sort-by="serial_number">Serial Number</div>
                            <div class="product-cell cell-aksi">Aksi</div>
                        </div>
                        <div id="productTableRowsContainer"></div>
                    </div>
                    <div class="table-footer-controls">
                        <div class="rows-per-page-wrapper">
                            <label for="rows-per-page-select">Baris per halaman:</label>
                            <select id="rows-per-page-select">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="pagination-buttons" id="paginationButtonsContainer">
                        </div>
                        <div class="pagination-info" id="paginationInfoText">
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


        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

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
                const fetchSurat = () => {
                    const params = new URLSearchParams({
                        page: state.currentPage,
                        per_page: state.rowsPerPage,
                        search: state.searchTerm,
                    });

                    if (state.sort.key && state.sort.direction !== 'none') {
                        params.append('sort_by', state.sort.key);
                        params.append('sort_direction', state.sort.direction);
                    }

                    fetch(`{{ route('surat.search') }}?${params.toString()}`)
                        .then(response => response.json())
                        .then(data => {
                            window.latestPaginationData = data;
                            renderTable(data.data);
                            renderPagination(data);
                        })
                        .catch(error => console.error('Fetch error:', error));
                };

                const renderTable = (suratList) => {
                    const userRole = '{{ auth()->user()->role }}';
                    const isSuperAdmin = userRole === 'super_admin';
                    const isAdmin = userRole === 'admin';

                    if (!suratList || suratList.length === 0) {
                        ui.tableBody.innerHTML = `<div class="products-row"><div class="product-cell" style="flex: 1; text-align: center; padding: 2rem;">Tidak ada data surat ditemukan.</div></div>`;
                        return;
                    }

                    ui.tableBody.innerHTML = '';

                    suratList.forEach((surat) => {
                        const row = document.createElement('div');
                        row.className = 'products-row';
                        const merek = `${surat.merek || 'N/A'}`;
                        const rowNumber = surat.original_row_number;

                        let desktopActionButtons = '';
                        let mobileActionButtons = '';

                        const buttonsConfig = [{
                            name: 'download',
                            icon: 'fa-download',
                            text: 'Download',
                            title: 'Download PDF',
                            condition: isAdmin || isSuperAdmin,
                            type: 'link'
                        }, {
                            name: 'detail',
                            icon: 'fa-info-circle',
                            text: 'Detail',
                            title: 'Lihat Detail',
                            condition: true,
                            type: 'button'
                        }, {
                            name: 'edit',
                            icon: 'fa-edit',
                            text: 'Edit',
                            title: 'Edit Surat',
                            condition: isSuperAdmin,
                            type: 'button'
                        }, {
                            name: 'delete',
                            icon: 'fa-trash-alt',
                            text: 'Hapus',
                            title: 'Hapus Surat',
                            condition: isSuperAdmin,
                            type: 'button'
                        }];

                        buttonsConfig.forEach(btn => {
                            if (btn.condition) {
                                const iconHtml = `<i class="fas ${btn.icon}"></i>`;
                                const textHtml = `<span>${btn.text}</span>`;
                                const commonClass = `class="action-btn-table ${btn.name}-btn"`;

                                if (btn.type === 'link') {
                                    const linkAttrs = `${commonClass} href="/surat/download/${surat.id}" target="_blank" title="${btn.title}"`;
                                    desktopActionButtons += `<a ${linkAttrs}>${iconHtml}</a>`;
                                    mobileActionButtons += `<a ${linkAttrs}>${iconHtml}${textHtml}</a>`;
                                } else {
                                    const buttonAttrs = `${commonClass} data-id="${surat.id}" title="${btn.title}"`;
                                    desktopActionButtons += `<button ${buttonAttrs}>${iconHtml}</button>`;
                                    mobileActionButtons += `<button ${buttonAttrs}>${iconHtml}${textHtml}</a>`;
                                }
                            }
                        });

                        const actionButtonsWrapperClass = isSuperAdmin ? 'action-buttons-wrapper is-super-admin-actions' : 'action-buttons-wrapper';

                        const desktopViewHtml = `
                        <div class="product-cell cell-no">${rowNumber}</div>
                        <div class="product-cell cell-no-surat">${surat.no_surat}</div>
                        <div class="product-cell cell-penerima">${surat.nama_penerima}</div>
                        <div class="product-cell cell-pemberi">${surat.nama_pemberi}</div>
                        <div class="product-cell cell-merek-jenis">${merek}</div>
                        <div class="product-cell cell-asset">${surat.no_asset || ''}</div>
                        <div class="product-cell cell-serial-number">${surat.serial_number || ''}</div>
                        <div class="product-cell cell-aksi">
                            <div class="action-buttons-wrapper">${desktopActionButtons}</div>
                        </div>`;
                        const mobileViewHtml = `
                        <div class="mobile-card-content">
                            <div class="merek-jenis-mobile">${merek}</div>
                            <div class="pihak-container">
                                <span title="Pemberi">${surat.nama_pemberi}</span>
                                <i class="fas fa-long-arrow-alt-right panah-serah-terima"></i>
                                <span title="Penerima">${surat.nama_penerima}</span>
                            </div>
                            <div>${surat.no_surat}</div>
                            <div>${surat.no_asset || 'N/A'}</div>
                            <div>${surat.serial_number || 'N/A'}</div>
                        </div>
                        <div class="card-actions">
                            <div class="${actionButtonsWrapperClass}">${mobileActionButtons}</div>
                        </div>`;

                        row.innerHTML = `
                            <div class="desktop-view">${desktopViewHtml}</div>
                            <div class="mobile-card-view">${mobileViewHtml}</div>
                        `;
                        ui.tableBody.appendChild(row);
                    });
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
                                fetchSurat();
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
                        fetchSurat();
                    });
                });

                ui.searchInput.addEventListener('input', () => {
                    clearTimeout(state.searchDebounce);
                    state.searchDebounce = setTimeout(() => {
                        state.searchTerm = ui.searchInput.value;
                        state.currentPage = 1;
                        fetchSurat();
                    }, 500);
                });

                ui.rowsPerPageSelect.addEventListener('change', () => {
                    state.rowsPerPage = parseInt(ui.rowsPerPageSelect.value, 10);
                    state.currentPage = 1;
                    fetchSurat();
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

                        fetch("{{ route('surat.getProspectiveNomor') }}")
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

                [ui.suratModal, ui.detailSuratModal].forEach(modal => {
                    if (modal) {
                        modal.querySelectorAll('.close-button').forEach(btn => {
                            btn.addEventListener('click', () => closeModal(modal));
                        });
                    }
                });

                setupSmartModalClosure(ui.suratModal, () => closeModal(ui.suratModal));
                setupSmartModalClosure(ui.detailSuratModal, () => closeModal(ui.detailSuratModal));

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        if (ui.suratModal.classList.contains('show')) {
                            closeModal(ui.suratModal);
                        }
                        if (ui.detailSuratModal.classList.contains('show')) {
                            closeModal(ui.detailSuratModal);
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

                        fetch(`{{ route('surat.find-barang') }}?query=${query}`)
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

                    let url = "{{ route('surat.store') }}";
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
                                fetchSurat();
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

                ui.tableBody.addEventListener('click', (e) => {
                    const editButton = e.target.closest('.edit-btn');
                    const deleteButton = e.target.closest('.delete-btn');
                    const detailButton = e.target.closest('.detail-btn');

                    if (editButton) {
                        const id = editButton.dataset.id;
                        fetch(`/surat/${id}`)
                            .then(res => res.json())
                            .then(surat => {
                                if (surat) openModalForEdit(surat);
                            });
                    }

                    if (deleteButton) {
                        const id = deleteButton.dataset.id;
                        if (confirm('Anda yakin ingin menghapus surat ini? Aksi ini tidak bisa dibatalkan.')) {
                            fetch(`/surat/${id}`, {
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
                                        fetchSurat();
                                    } else {
                                        alert(data.error || 'Gagal menghapus surat.');
                                    }
                                });
                        }
                    }

                    if (detailButton) {
                        const id = detailButton.dataset.id;
                        fetch(`/surat/${id}`)
                            .then(res => res.json())
                            .then(surat => {
                                if (surat) {
                                    showDetailModal(surat);
                                } else {
                                    alert('Gagal memuat detail surat.');
                                }
                            });
                    }
                });

                setupUI();
                fetchSurat();
            });
        </script>
    </body>

    </html>