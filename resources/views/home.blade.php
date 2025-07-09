@extends('dashboard')

@section('konten')
    <style>
        /* Base styles */
        .content-wrapper {
            background-color: #f8f9fc;
            padding: 1.5rem;
        }

        /* Card styles */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
        }

        /* Icon styles */
        .icon-bg {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .bg-primary-light {
            background-color: rgba(78, 115, 223, 0.1);
        }

        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.1);
        }

        .bg-warning-light {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .bg-danger-light {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .bg-info-light {
            background-color: rgba(23, 162, 184, 0.1);
        }

        .icon-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Badge styles */
        .badge-soft-success {
            color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }

        .badge-soft-warning {
            color: #ffc107;
            background-color: rgba(255, 193, 7, 0.1);
        }

        .badge-soft-danger {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
        }

        /* Animation for wave */
        .wave-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .wave {
            width: 100%;
            height: 60px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .display-4 {
                font-size: 2rem;
            }
        }
    </style>

    <div class="content-wrapper">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <!-- Indikator -->
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>

            <!-- Gambar -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{ url('img/carousel/bg1.jpg') }}" alt="Slide 1"
                        style="height: 200px; object-fit: cover;">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="{{ url('img/carousel/bg2.jpg') }}" alt="Slide 2"
                        style="height: 200px; object-fit: cover;">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="{{ url('img/carousel/bg3.jpg') }}" alt="Slide 3"
                        style="height: 200px; object-fit: contain; background-color: #f0f0f0;">
                </div>
            </div>

            <!-- Tombol Panah -->
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Sebelumnya</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Selanjutnya</span>
            </a>
        </div>

        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <h1 class="m-0 font-weight-bold text-primary">Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-right text-muted">
                            <i class="far fa-clock mr-1"></i> Last updated: {{ now()->format('d M Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <!-- Welcome Card -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-gradient-primary text-white overflow-hidden shadow-lg">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="mr-4">
                                    <div class="avatar bg-white text-primary rounded-circle p-3 shadow-sm">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-weight-bold mb-1">Selamat Datang, {{ Auth::user()->name }}!</h4>
                                    <p class="mb-0 opacity-75">Sistem Layanan Pencairan Anggaran</p>
                                </div>




                                <div class="ml-auto text-right">
                                    <span class="badge badge-light font-weight-bold py-2 px-3">
                                        <i class="fas fa-shield-alt mr-1"></i>
                                        @php
                                            $userId = auth()->user()->id_jabatan;
                                            $jabatan = DB::table('master_jabatan')->where('id', $userId)->first();

                                        @endphp
                                        {{ $jabatan->nama_jabatan }}
                                    </span>
                                </div>
                            </div>
                            <div class="wave-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none"
                                    class="wave">
                                    <path fill="rgba(255, 255, 255, 0.1)"
                                        d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,133.3C672,139,768,181,864,181.3C960,181,1056,139,1152,122.7C1248,107,1344,117,1392,122.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Overview -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="font-weight-bold mb-0 mr-2">Analytics Overview</h5>
                            <div class="flex-grow-1 border-bottom"></div>
                        </div>
                    </div>
                </div>

                <!-- Primary Stats -->
                <div class="row">

                    @php
                        $role = Auth::user()->role;
                        $isBos = in_array($role, [0, 4, 5]); // admin, kepsek, yayasan
                    @endphp

                    {{-- Total Pengajuan --}}
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-bg bg-warning-light rounded-lg p-3 mr-3">
                                        <i class="fas fa-book-open text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">Total Pengajuan</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-baseline">
                                    <h3 class="font-weight-bold mb-0">
                                        {{ $isBos ? $totalAll : $total }}
                                    </h3>
                                    <span class="badge badge-soft-warning ml-2">
                                        <i class="fas fa-check-circle"></i> {{ $isBos ? $totalAll : $total }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer border-0 bg-transparent p-0">
                                <a href="" class="btn btn-link text-warning btn-block">
                                    Lihat Pengajuan <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Total Approve --}}
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-bg bg-success-light rounded-lg p-3 mr-3">
                                        <i class="fas fa-check-circle text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">Total Di Approve</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-baseline">
                                    <h3 class="font-weight-bold mb-0">
                                        {{ $isBos ? $approveAll : $approve }}
                                    </h3>
                                    <span class="badge badge-soft-success ml-2">
                                        <i class="fas fa-thumbs-up"></i> {{ $isBos ? $approveAll : $approve }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer border-0 bg-transparent p-0">
                                <a href="" class="btn btn-link text-success btn-block">
                                    Lihat Data <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Total Reject --}}
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-bg bg-danger-light rounded-lg p-3 mr-3">
                                        <i class="fas fa-times-circle text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">Total Di Rejected</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-baseline">
                                    <h3 class="font-weight-bold mb-0">
                                        {{ $isBos ? $rejectAll : $reject }}
                                    </h3>
                                    <span class="badge badge-danger ml-2">
                                        <i class="fas fa-thumbs-down"></i> {{ $isBos ? $rejectAll : $reject }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer border-0 bg-transparent p-0">
                                <a href="" class="btn btn-link text-danger btn-block">
                                    Lihat Data <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>


                </div>


                <!-- Secondary Stats Row -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="font-weight-bold mb-0 mr-2">Module Management</h5>
                            <div class="flex-grow-1 border-bottom"></div>
                        </div>
                    </div>
                </div>


                <!-- System Stats -->

            </div>
        </section>
    </div>
@endsection
