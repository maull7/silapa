<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SEBASA - Sekolah Bahasa Polri</title>

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
            justify-content: center;
            /* Pusatkan horizontal */
            align-items: center;
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
            width: 100%;
            margin-top: 0.5rem;
            background-color: #191970;
            border-radius: 20px;
        }

        /* Nama pengguna */
        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
        }



        /* Minimalist sidebar structure */
        .main-sidebar {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            background: #fff;
            overflow-x: hidden;
            /* Prevent horizontal scrolling */
            width: 250px;
            /* Fixed width */
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 70px);
            padding: 0;
            overflow-x: hidden;
            /* Prevent horizontal scrolling */
        }

        /* Menu area with clean styling */
        .menu-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            /* Prevent horizontal scrolling */
            padding: 0.5rem 0;
            scrollbar-width: thin;
            max-width: 100%;
            /* Ensure content doesn't exceed sidebar width */
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
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

        /* Elegant menu items */
        .nav-sidebar .nav-item {
            margin: 4px 8px;
            width: calc(100% - 16px);
            /* Account for margins */
        }

        /* Enhanced hover animations for sidebar items */
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
            background-color: #007bff;
            transition: width 0.3s ease;
        }

        .nav-sidebar .nav-link:not(.active):hover:before {
            width: 100%;
        }

        .nav-sidebar .nav-link:not(.active):hover i {
            color: #007bff;
            transform: scale(1.1);
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link:not(.active):hover p {
            color: #007bff;
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
            background-color: #007bff;
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
            color: #007bff;
            transform: scale(1.1);
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link:hover p {
            color: rgb(255, 255, 255);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link.active {
            background-color: #007bff;
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

        /* Clean footer for logout */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            margin-top: auto;
        }

        /* Elegant logout button */
        .logout-button {
            display: block;
            width: 100%;
            padding: 0.65rem;
            border-radius: 6px;
            text-align: center;
            color: #555;
            background-color: rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }

        .logout-button:hover {
            background-color: #f8d7da;
            color: #721c24;
            text-decoration: none;
        }

        .logout-button i {
            margin-right: 8px;
        }

        /* Refined navbar */
        .main-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.03);
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
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-primary elevation-2">
        <!-- Brand Logo -->
        <div class="brand-link">
            <a href="#" class="brand">
                <img src="{{ url('img/image.png') }}" alt="Logo" class="brand-logo">
                <div class="brand-text-container">
                    <span class="brand-text">SEBASA</span>
                    <span class="brand-subtext">Sekolah Bahasa Polri</span>
                </div>
            </a>
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->name }}</span>
            </div>
        </div>


        <!-- Sidebar -->
        <div class="sidebar">
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

                        @if (auth()->user()->role == 1)
                            <li class="nav-header">Master</li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-toggle="collapse" data-target="#masterMenu">
                                    <i class="fas fa-cogs"></i>
                                    <p>Data Master <i class="fas fa-chevron-down float-right"></i></p>
                                </a>
                                <ul id="masterMenu" class="collapse nav flex-column">

                                    <li class="nav-item">
                                        <a href="/master_materi"
                                            class="nav-link {{ request()->is('master_materi*') ? 'active' : '' }}">
                                            <i class="fas fa-book"></i>
                                            <p>Master Materi</p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('master_soal.index') }}"
                                            class="nav-link {{ request()->routeIs('master_soal.*') ? 'active' : '' }}">
                                            <i class="fas fa-book-open"></i>
                                            <p>Master Soal</p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('master_quiz.index') }}"
                                            class="nav-link {{ request()->routeIs('master_quiz.*') ? 'active' : '' }}">
                                            <i class="fas fa-clipboard-list"></i>
                                            <p>Master Quiz</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('get.jadwal.guru') }}"
                                            class="nav-link {{ request()->routeIs('get.jadwal.guru') ? 'active' : '' }}">
                                            <i class="fas fa-clock"></i>
                                            <p>Jadwal Saya</p>
                                        </a>
                                    </li>


                                </ul>
                            </li>
                        @else
                            <li class="nav-header">Master</li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-toggle="collapse" data-target="#masterMenu">
                                    <i class="fas fa-cogs"></i>
                                    <p>Data Master <i class="fas fa-chevron-down float-right"></i></p>
                                </a>
                                <ul id="masterMenu" class="collapse nav flex-column">
                                    <li class="nav-item" data-toggle="collapse" data-target="#masterMenu">
                                        <a href="{{ route('master_guru.index') }}"
                                            class="nav-link {{ request()->routeIs('master_guru.*') ? 'active' : '' }}">
                                            <i class="fas fa-user"></i> Master Guru
                                        </a>
                                    </li>
                                    <li class="nav-item" data-toggle="collapse" data-target="#masterMenu">
                                        <a href="/master_siswa"
                                            class="nav-link {{ request()->is('master_siswa*') ? 'active' : '' }}">
                                            <i class="fas fa-user-graduate"></i> Master Siswa
                                        </a>
                                    </li>
                                    <li class="nav-item" data-toggle="collapse" data-target="#masterMenu">
                                        <a href="/master_user"
                                            class="nav-link {{ request()->is('master_user*') ? 'active' : '' }}">
                                            <i class="fas fa-users"></i> Master User
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/master_materi"
                                            class="nav-link {{ request()->is('master_materi*') ? 'active' : '' }}">
                                            <i class="fas fa-book"></i>
                                            <p>Master Materi</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('master_kelas.index') }}"
                                            class="nav-link {{ request()->routeIs('master_kelas.*') ? 'active' : '' }}">
                                            <i class="fas fa-landmark"></i>
                                            <p>Master Kelas</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('master_soal.index') }}"
                                            class="nav-link {{ request()->routeIs('master_soal.*') ? 'active' : '' }}">
                                            <i class="fas fa-book-open"></i>
                                            <p>Master Soal</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('master_jurusan.index') }}"
                                            class="nav-link {{ request()->routeIs('master_jurusan.*') ? 'active' : '' }}">
                                            <i class="fas fa-school"></i>
                                            <p>Master Jurusan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('master_quiz.index') }}"
                                            class="nav-link {{ request()->routeIs('master_quiz.*') ? 'active' : '' }}">
                                            <i class="fas fa-clipboard-list"></i>
                                            <p>Master Quiz</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('master_jadwal.index') }}"
                                            class="nav-link {{ request()->routeIs('master_jadwal.*') ? 'active' : '' }}">
                                            <i class="fas fa-clipboard-list"></i>
                                            <p>Master Jadwal</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <li class="nav-header">Map Soal</li>

                        <li class="nav-item">
                            <a href="{{ route('master_map_soal.index') }}"
                                class="nav-link {{ request()->routeIs('master_map_soal.*') ? 'active' : '' }}">
                                <i class="fas fa-clipboard-list"></i>
                                <p>Mapping Soal</p>
                            </a>
                        </li>

                        <li class="nav-header">Laporan</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-toggle="collapse" data-target="#laporan">
                                <i class="fas fa-folder-open"></i>
                                <p>Data Laporan <i class="fas fa-chevron-down float-right"></i></p>
                            </a>
                            <ul id="laporan" class="collapse nav flex-column">

                                <li class="nav-item">
                                    <a href="{{ route('laporan_nilai.index') }}"
                                        class="nav-link {{ request()->routeIs('laporan_nilai.*') ? 'active' : '' }}">
                                        <i class="fas fa-file-invoice"></i>
                                        <p>Laporan Nilai</p>
                                    </a>
                                </li>
                            </ul>
                        </li>


                    </ul>
                </nav>
            </div>

            <!-- Footer Section for Logout -->
            <div class="sidebar-footer">
                <a href="/logout" class="logout-button">
                    <i class="fas fa-power-off"></i> Logout
                </a>
            </div>
        </div>
        <!-- /.sidebar -->
    </aside>

    @yield('konten')

    <footer class="main-footer">
        <strong>Copyright &copy; 2024 </strong>
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

</body>

</html>
