<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- jQuery and DataTables -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.4/css/dataTables.bootstrap5.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.css">
        <script src="https://cdn.datatables.net/2.0.4/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.0.4/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap5.js"></script>
        
        <style>
            body {
                background-color: #f8f9fa;
            }
            #wrapper {
                display: flex;
                min-height: 100vh;
            }
            #page-content-wrapper {
                width: 100%;
                overflow-x: hidden;
            }
            /* DataTables Elegant Styling */
            .dt-container {
                padding: 0.5rem 0;
            }
            .dt-layout-row {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
                align-items: center;
                justify-content: space-between;
                padding: 0.75rem 1.5rem !important;
                background-color: #fff;
                border-bottom: 1px solid #f1f3f5;
            }
            .dt-layout-row:first-child {
                border-top: 1px solid #f1f3f5;
            }
            .dt-search {
                display: flex;
                align-items: center;
            }
            .dt-search label {
                display: none !important;
            }
            .dt-search input {
                border-radius: 12px !important;
                border: 1px solid #e9ecef !important;
                padding: 0.5rem 1rem !important;
                background-color: #f8f9fa !important;
                transition: all 0.2s ease;
                min-width: 240px;
                font-size: 0.8125rem;
                box-shadow: none !important;
            }
            .dt-search input:focus {
                background-color: #fff !important;
                border-color: #0d6efd !important;
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.05) !important;
                outline: none;
            }
            .dt-length select {
                border-radius: 10px !important;
                border: 1px solid #e9ecef !important;
                padding: 0.4rem 2rem 0.4rem 0.75rem !important;
                background-color: #f8f9fa !important;
                font-size: 0.8125rem;
                cursor: pointer;
            }
            .dt-info {
                font-size: 0.75rem;
                color: #adb5bd;
                font-weight: 500;
                padding: 1rem 1.5rem !important;
            }
            .dt-paging {
                padding: 1rem 1.5rem !important;
            }
            .dt-paging-nav {
                display: flex;
                gap: 4px;
            }
            .dt-paging-button {
                padding: 0.4rem 0.8rem !important;
                margin: 0 !important;
                border-radius: 8px !important;
                border: 1px solid #e9ecef !important;
                background: #fff !important;
                font-size: 0.75rem !important;
                font-weight: 600 !important;
                color: #495057 !important;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .dt-paging-button:hover:not(.disabled):not(.current) {
                background: #f8f9fa !important;
                border-color: #dee2e6 !important;
                color: #0d6efd !important;
            }
            .dt-paging-button.current {
                background: #0d6efd !important;
                border-color: #0d6efd !important;
                color: #fff !important;
                box-shadow: 0 2px 4px rgba(13, 110, 253, 0.15) !important;
            }
            .dt-paging-button.disabled {
                opacity: 0.3;
                cursor: not-allowed;
            }
            /* Table Styling */
            table.dataTable {
                border-collapse: separate !important;
                border-spacing: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                border: none !important;
            }
            table.dataTable thead th {
                background-color: #fff !important;
                padding: 0.875rem 1rem !important;
                font-weight: 700 !important;
                text-transform: uppercase;
                font-size: 0.7rem;
                letter-spacing: 0.075em;
                color: #94a3b8;
                border-bottom: 1px solid #f1f3f5 !important;
                border-top: none !important;
            }
            table.dataTable tbody td {
                padding: 0.875rem 1rem !important;
                border-bottom: 1px solid #f8f9fa !important;
                color: #334155;
                font-size: 0.875rem;
            }
            table.dataTable tbody tr:hover {
                background-color: #fcfdfe !important;
            }
            table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before {
                background-color: #0d6efd !important;
                border: 2px solid #fff !important;
            }
            /* Card & Layout Tightening */
            .card-header {
                background-color: #fff !important;
                padding: 1rem 1.5rem !important;
            }
            .card-title {
                font-size: 1rem !important;
                letter-spacing: -0.01em;
            }
        </style>
        </style>
    </head>
    <body>
        <div id="wrapper">
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Page Content -->
            <div id="page-content-wrapper" class="d-flex flex-column">
                <!-- Topbar -->
                <header class="navbar navbar-expand navbar-light bg-white shadow-sm mb-4 py-3 px-4">
                    <div class="container-fluid d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <button id="sidebarToggle" class="btn btn-link d-md-none me-3">
                                <i class="bi bi-list fs-3"></i>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                                </svg>
                            </button>
                            @isset($header)
                                <h1 class="h4 mb-0 text-dark font-weight-bold">{{ $header }}</h1>
                            @endisset
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="dropdown">
                                <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center p-0" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="text-end me-2 d-none d-sm-block">
                                        <div class="fw-bold text-dark small lh-1">{{ Auth::user()->name }}</div>
                                        <div class="text-muted extra-small lh-1 mt-1">{{ Auth::user()->email }}</div>
                                    </div>
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" aria-labelledby="userDropdown">
                                    <li><h6 class="dropdown-header d-sm-none">{{ Auth::user()->name }}</h6></li>
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>{{ __('Profile') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i>{{ __('Log Out') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <main class="container-fluid px-4 pb-4">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script>
            window.addEventListener('DOMContentLoaded', event => {
                const sidebarToggle = document.body.querySelector('#sidebarToggle');
                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', event => {
                        event.preventDefault();
                        document.body.classList.toggle('sb-sidenav-toggled');
                    });
                }

                // Initialize DataTables with Premium Design
                if (typeof $ !== 'undefined') {
                    $.extend(true, $.fn.dataTable.defaults, {
                        language: {
                            lengthMenu: "_MENU_",
                            zeroRecords: "Data tidak ditemukan",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            infoEmpty: "Tidak ada data tersedia",
                            infoFiltered: "(difilter dari _MAX_ total data)",
                            search: "",
                            searchPlaceholder: "Cari data...",
                            paginate: {
                                first: '<svg width="16" height="16" fill="currentColor" class="bi bi-chevron-double-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/></svg>',
                                last: '<svg width="16" height="16" fill="currentColor" class="bi bi-chevron-double-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708"/></svg>',
                                next: '<svg width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/></svg>',
                                previous: '<svg width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/></svg>'
                            }
                        },
                        pageLength: 10,
                        responsive: true,
                        layout: {
                            topStart: 'pageLength',
                            topEnd: 'search',
                            bottomStart: 'info',
                            bottomEnd: 'paging'
                        }
                    });

                    $('.datatable').DataTable();
                }
                // Fallback dropdown toggle
                const userDropdown = document.getElementById('userDropdown');
                if (userDropdown) {
                    userDropdown.addEventListener('click', function(e) {
                        e.preventDefault();
                        const menu = userDropdown.nextElementSibling;
                        if (menu.classList.contains('show')) {
                            menu.classList.remove('show');
                        } else {
                            menu.classList.add('show');
                        }
                    });
                    
                    document.addEventListener('click', function(e) {
                        if (!userDropdown.contains(e.target)) {
                            const menu = userDropdown.nextElementSibling;
                            if (menu && menu.classList.contains('show')) {
                                menu.classList.remove('show');
                            }
                        }
                    });
                }
            });
        </script>
        <!-- Bootstrap JS Bundle CDN -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
