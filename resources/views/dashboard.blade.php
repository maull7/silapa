<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Layanan Pencairan Anggaran</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ url('img/favicon.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ url('img/favicon.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ url('img/favicon.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}  ">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}  ">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ url('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ url('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ url('plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    @yield('style')

    <style>
        /* Core minimalist styling */
        body {
            font-family: 'Source Sans Pro', sans-serif;
        }

        /* Elegant brand styling */
        /* Wrapper utama */
        .brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 12px;
        }

        /* Link brand (logo + teks) */
        .brand-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Logo */
        .brand-logo {
            width: 36px;
            height: auto;
        }

        /* Kontainer teks */
        .brand-text-container {
            display: flex;
            flex-direction: column;
        }

        /* Teks utama */
        .brand-text {
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 1.1rem;
            margin: 0;
            line-height: 1.2;
            color: #333;
        }

        /* Subteks */
        .brand-subtext {
            font-size: 0.8rem;
            opacity: 0.7;
            letter-spacing: 0.3px;
            color: #555;
        }

        /* Info pengguna */
        .user-info {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            width: 100%;
            margin-top: 0.5rem;
            background: linear-gradient(135deg, #b60909 0%, #d85d5d 100%);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .user-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .user-icon {
            font-size: 1.5rem;
            color: white;
            margin-right: 10px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        /* Nama pengguna */
        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            margin-bottom: 2px;
        }

        /* Role pengguna */
        .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
        }

        /* Update sidebar structure */
        .sidebar {
            position: relative;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 70px);
            padding: 0;
        }

        .menu-area {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem 0;
            margin-bottom: 65px;
            /* Space for logout button */
            max-height: calc(100vh - 200px);
            /* Adjust based on header + footer height */
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 1rem;
            background: #fff;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.03);
            z-index: 1000;
            transition: all 0.3s ease;
        }


        /* Enhanced logout button */
        .logout-button {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            padding: 0.75rem;
            border-radius: 6px;
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .logout-button:hover {
            background-color: #dc3545;
            color: #fff;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .logout-button i {
            margin-right: 8px;
            font-size: 1rem;
            width: 22px;
            text-align: center;
        }

        /* Add fade effect above logout button */
        .menu-area::after {
            content: '';
            position: absolute;
            bottom: 65px;
            left: 0;
            width: 100%;
            height: 20px;
            background: linear-gradient(to top, #fff, rgba(255, 255, 255, 0));
            pointer-events: none;
        }


        /* Sidebar mini adjustments */
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .sidebar-footer {
            width: 250px;
        }

        .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover) .sidebar-footer {
            width: 4.6rem;
        }

        .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover) .logout-button {
            padding: 0.75rem 0;
            justify-content: center;
        }

        .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover) .logout-button i {
            margin-right: 0;
        }

        .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover) .logout-button span {
            display: none;
        }

        /* Add fade effect above logout button */
        .menu-area::after {
            content: '';
            position: fixed;
            bottom: 65px;
            left: 0;
            width: 250px;
            height: 20px;
            background: linear-gradient(to top, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0));
            pointer-events: none;
        }

        /* Ensure proper scrollbar styling */
        .menu-area::-webkit-scrollbar {
            width: 4px;
        }

        .menu-area::-webkit-scrollbar-track {
            background: transparent;
        }

        .menu-area::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 20px;
        }

        /* Enhanced hover animations for sidebar items */
        .nav-sidebar .nav-item {
            margin: 4px 8px;
            width: calc(100% - 16px);
            /* Account for margins */
        }

        .nav-sidebar .nav-link {
            position: relative;
            overflow: hidden;
            padding: 0.65rem 1rem;
            border-radius: 6px;
            color: #555;
            transition: all 0.3s ease;
            white-space: nowrap;
            /* Prevent text wrapping */
            text-overflow: ellipsis;
            /* Add ellipsis for overflowing text */
            max-width: 100%;
            /* Ensure content doesn't exceed container width */
        }

        .nav-sidebar .nav-link:not(.active):hover {
            background-color: rgba(0, 123, 255, 0.05);
            transform: translateX(3px);
        }

        .nav-sidebar .nav-link:not(.active):before {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            height: 2px;
            width: 0;
            background-color: #dc3545;
            transition: width 0.3s ease;
        }

        .nav-sidebar .nav-link:not(.active):hover:before {
            width: 100%;
        }

        .nav-sidebar .nav-link:not(.active):hover i {
            color: #dc3545;
            transform: scale(1.1);
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link:not(.active):hover p {
            color: #dc3545;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link:before {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            height: 2px;
            width: 0;
            background-color: #dc3545;
            transition: width 0.3s ease;
        }

        .nav-sidebar .nav-link:hover {
            background-color: rgba(0, 123, 255, 0.05);
            transform: translateX(3px);
        }

        .nav-sidebar .nav-link:hover:before {
            width: 100%;
        }

        .nav-sidebar .nav-link:hover i {
            color: #dc3545;
            transform: scale(1.1);
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link:hover p {
            color: rgba(255, 255, 255, 0.959);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link.active {
            background-color: #dc3545;
            color: #fff;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.4);
        }

        .nav-sidebar .nav-link.active:before {
            width: 100%;
        }

        .nav-sidebar .nav-link.active i {
            color: #fff;
        }

        /* Smooth transitions for all elements */
        .nav-sidebar .nav-link i,
        .nav-sidebar .nav-link p {
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link i {
            width: 22px;
            text-align: center;
            margin-right: 8px;
            font-size: 0.9rem;
        }

        .nav-sidebar .nav-link p {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-right: 15px;
            /* Add margin to prevent text from touching the edge */
        }

        #masterMenu {
            padding-left: 15px;
            max-width: 100%;
            /* Ensure content doesn't exceed container width */
        }

        #masterMenu .nav-item {
            width: calc(100% - 15px);
            /* Account for padding */
        }

        /* Section headers with subtle styling */
        .nav-header {
            font-size: 0.75rem;
            padding: 0.75rem 1.3rem 0.5rem;
            color: #aaa;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            margin-top: 10px;
        }



        /* Refined navbar */
        .main-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.03);
        }

        .logout-button:hover .logout-text {
            color: white !important;
        }

        .logout-button:hover i {
            color: white !important;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
            max-width: 100%;
            /* tidak melewati wadah */
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .brand-text-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 200px;
            /* batasi panjang teks */
            overflow: hidden;
        }

        .brand-text {
            font-weight: bold;
            font-size: 1.2rem;
            line-height: 1.1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-subtext {
            font-size: 0.75rem;
            color: #666;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ url('plugins/sparklines/sparkline.js') }}"></script>
    <script src="{{ url('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ url('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ url('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script type="text/javascript" src="https://momentjs.com/downloads/moment.js"></script>
    <script type="text/javascript" src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script src="{{ url('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ url('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ url('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ url('dist/js/adminlte.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <div class="wrapper">

    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifikasi -->
            <li class="nav-item dropdown">
                <a class="nav-link position-relative text-danger" data-toggle="dropdown" href="#"
                    aria-expanded="false">
                    <i class="fas fa-bell fa-lg"></i>
                    @if ($notifCount > 0)
                        <span class="badge badge-primary navbar-badge position-absolute" style="top: 0; right: 0;">
                            {{ $notifCount }}
                        </span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow notif-dropdown">
                    <span class="dropdown-header text-danger font-weight-bold">
                        Notifikasi ({{ $notifCount }})
                    </span>
                    <div class="dropdown-divider"></div>

                    @forelse($notifs as $notif)
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope text-danger mr-2"></i>
                            {{ Str::limit($notif->desc, 50) }}
                            <span class="float-right text-muted text-sm">
                                {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                            </span>
                        </a>
                    @empty
                        <span class="dropdown-item text-muted">Tidak ada notifikasi.</span>
                    @endforelse

                    <div class="dropdown-divider"></div>
                    <a href="{{ route('notif.read') }}" class="dropdown-item dropdown-footer text-danger">Lihat
                        Tandai Telah DiBaca</a>
                </div>
            </li>

            <!-- Fullscreen -->
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>


    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-danger elevation-2">
        <!-- Brand Logo -->
        <div class="brand-link">
            <a href="#" class="brand">
                <img src="{{ url('img/image.png') }}" alt="Logo" class="brand-logo">
                <div class="brand-text-container">
                    <span class="brand-text">SILAPA</span>
                    <span class="brand-subtext">Sistem Layanan Pencairan Anggaran</span>
                </div>
            </a>
            <div class="user-info">
                <i class="fas fa-user-circle user-icon"></i>
                <div class="user-details">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">
                        @php
                            $userId = auth()->user()->id_jabatan;
                            $jabatan = DB::table('master_jabatan')->where('id', $userId)->first();

                        @endphp
                        {{ $jabatan->nama_jabatan }}
                    </span>
                </div>
            </div>
        </div>


        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column" style="height: 100%;"">
            <!-- Menu area -->
            <div class="menu-area">
                <nav class="mt-1">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="/home" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        @if (in_array(auth()->user()->role, [1, 2, 3]))
                            <li class="nav-header">Manajemen Pengajuan</li>
                            <li
                                class="nav-item {{ request()->is('master_*') || request()->routeIs('master_*.') ? 'active' : '' }}">
                                <a href="#"
                                    class="nav-link {{ request()->is('ajukan-pengajuan') || request()->routeIs('ajukan-pengajuan') ? 'active' : '' }}"
                                    data-toggle="collapse" data-target="#Pengajuan">
                                    <i class="fas fa-tags"></i>
                                    <p>Pengajuan<i class="fas fa-chevron-down float-right"></i></p>
                                </a>
                                <ul id="Pengajuan" class="collapse nav flex-column">
                                    <li class="nav-item">
                                        <a href="/ajukan-pengajuan"
                                            class="nav-link {{ request()->is('ajukan-pengajuan') ? 'active' : '' }}">
                                            <p>
                                                Formulir Pengajuan
                                                <i class="fas fa-file-alt"></i>
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/pengajuan"
                                            class="nav-link {{ request()->is('pengajuan') ? 'active' : '' }}">
                                            <p>
                                                Permohonan & Riwayat<i class="fas fa-inbox"></i>
                                            </p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        @endif
                        @if (in_array(auth()->user()->role, [2, 3, 4, 5]))
                            <li class="nav-header">Panel Persetujuan</li>
                            <li class="nav-item">
                                <a href="/verifikasi-pengajuan"
                                    class="nav-link {{ request()->is('verifikasi-pengajuan') ? 'active' : '' }}">
                                    <i class="fas fa-check-circle position-relative">
                                        @if ($notifCount > 0)
                                            <span
                                                class="position-absolute top-0 start-100 translate-middle p-1 bg-primary border border-light rounded-circle"
                                                style="font-size: 0.6rem;"></span>
                                        @endif
                                    </i>
                                    <p>
                                        Verifikasi Pengajuan
                                        @if ($notifCount > 0)
                                            <span class="badge badge-primary ml-1">{{ $notifCount }}</span>
                                        @endif
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="/pengajuan-approve"
                                    class="nav-link {{ request()->is('pengajuan-approve') ? 'active' : '' }}">
                                    <i class="fas fa-thumbs-up"></i>
                                    <p>Pengajuan Disetujui</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/pengajuan-reject"
                                    class="nav-link {{ request()->is('pengajuan-reject') ? 'active' : '' }}">
                                    <i class="fas fa-times-circle"></i>
                                    <p>Pengajuan Ditolak</p>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->role == 0)
                            <li class="nav-header">Master</li>
                            <li
                                class="nav-item {{ request()->is('master_*') || request()->routeIs('master_*.') ? 'active' : '' }}">
                                <a href="#"
                                    class="nav-link {{ request()->is('master_*') || request()->routeIs('master_*.') ? 'active' : '' }}"
                                    data-toggle="collapse" data-target="#masterMenu">
                                    <i class="fas fa-cogs"></i>
                                    <p>Data Master <i class="fas fa-chevron-down float-right"></i></p>
                                </a>
                                <ul id="masterMenu" class="collapse nav flex-column">
                                    <li class="nav-item">
                                        <a href="/master_jabatan"
                                            class="nav-link {{ request()->is('master_jabatan*') ? 'active' : '' }}">
                                            <p>
                                                <i class="fas fa-user-shield"></i> Master Jabatan
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/master_user"
                                            class="nav-link {{ request()->is('master_user*') ? 'active' : '' }}">
                                            <p>
                                                <i class="fas fa-user-shield"></i> Master Admin

                                            </p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="/master_pengguna"
                                            class="nav-link {{ request()->is('master_pengguna*') ? 'active' : '' }}">
                                            <p>
                                                <i class="fas fa-user"></i> Master Pengguna

                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-header">Laporan</li>
                            <li class="nav-item">
                                <a href="{{ route('laporan.index') }}"
                                    class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                                    <i class="fas fa-file-invoice"></i>
                                    <p>Laporan</p>
                                </a>
                            </li>
                        @elseif(in_array(auth()->user()->role, [2, 3, 4, 5]))
                            <li class="nav-header">Laporan</li>
                            <li class="nav-item">
                                <a href="{{ route('laporan.index') }}"
                                    class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                                    <i class="fas fa-file-invoice"></i>
                                    <p>Laporan</p>
                                </a>
                            </li>
                        @endif
                    </ul>

                </nav>
            </div>

            <!-- Footer Section for Logout -->
            <div class="sidebar-footer p-3 border-top">
                <a href="{{ route('actionLogout') }}" class="logout-button d-flex align-items-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span class="logout-text">Logout</span>
                </a>
            </div>
        </div>

        <!-- /.sidebar -->
    </aside>

    @yield('konten')

    <footer class="main-footer">
        <strong>Copyright &copy; 2025 </strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if this is the first load after login
            const isFirstLoad = localStorage.getItem('hasLoggedIn') !== 'true';

            if (isFirstLoad) {
                // Store that user has logged in
                localStorage.setItem('hasLoggedIn', 'true');

                // Get current path
                const currentPath = window.location.pathname;

                // If we're just logged in and not already on dashboard
                if (currentPath === '/' || currentPath === '/login') {
                    // Redirect to dashboard
                    window.location.href = '/home';
                } else {
                    // Force the dashboard link to be active regardless of current page
                    const dashboardLink = document.querySelector('a[href="/home"]');
                    if (dashboardLink) {
                        // Remove active class from any other link that might have it
                        document.querySelectorAll('.nav-link.active').forEach(function(el) {
                            el.classList.remove('active');
                        });

                        // Add active class to dashboard
                        dashboardLink.classList.add('active');
                    }
                }
            }
        });

        // Alternative approach: Force dashboard active on specific pages
        // This helps if the user refreshes the page after login
        (function() {
            const currentPath = window.location.pathname;
            // If we're on login page or root
            if (currentPath === '/' || currentPath === '/login') {
                // Mark for redirect on next page load
                sessionStorage.setItem('redirectToDashboard', 'true');
            }

            // Check if we need to redirect
            if (sessionStorage.getItem('redirectToDashboard') === 'true') {
                // Clear the flag
                sessionStorage.removeItem('redirectToDashboard');

                // Force dashboard active
                const dashboardLink = document.querySelector('a[href="/home"]');
                if (dashboardLink) {
                    document.querySelectorAll('.nav-link.active').forEach(function(el) {
                        el.classList.remove('active');
                    });
                    dashboardLink.classList.add('active');
                }
            }
        })();
    </script>

    @yield('script')

</body>

</html>

<style>
    /* Add these styles to hide sidebar and make content full width */
    .sidebar-mini.sidebar-collapse .main-sidebar {
        display: none !important;
    }

    .sidebar-mini.sidebar-collapse .content-wrapper,
    .sidebar-mini.sidebar-collapse .main-footer,
    .sidebar-mini.sidebar-collapse .main-header {
        margin-left: 0 !important;
        width: 100% !important;
    }

    /* Ensure the toggle button still works */
    .nav-link[data-widget="pushmenu"] {
        display: block !important;
    }

    /* Add transition for smooth effect */
    .main-sidebar,
    .content-wrapper,
    .main-footer,
    .main-header {
        transition: all 0.3s ease-in-out;
    }
</style>

<script>
    // Existing scripts...

    // Auto collapse sidebar on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add the sidebar-collapse class to body
        document.body.classList.add('sidebar-collapse');

        // Toggle sidebar visibility when the button is clicked
        // In the <style> section, modify the existing CSS:
        .sidebar - mini.sidebar - collapse.main - sidebar {
                margin - left: -250 px!important;
                display: block!important;
            }

            .sidebar - mini.main - sidebar {
                transition: margin - left 0.3 s ease - in - out;
            }

        // In the JavaScript section, replace the toggle function with:
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.main-sidebar');
            if (document.body.classList.contains('sidebar-collapse')) {
                document.body.classList.remove('sidebar-collapse');
                document.body.classList.add('sidebar-open');
                sidebar.style.marginLeft = '0';
            } else {
                document.body.classList.add('sidebar-collapse');
                document.body.classList.remove('sidebar-open');
                sidebar.style.marginLeft = '-250px';
            }
        });
    });
</script>
