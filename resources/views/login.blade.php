<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SILAPA - Sistem Layanan Pencairan Anggaran</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ url('img/favicon.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ url('img/favicon.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ url('img/favicon.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">
    <style>
        /* Base styles matching dashboard */
        body {
            background-color: #f8f9fc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 400px;
            max-width: 90%;
        }

        /* Card styles matching dashboard */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: all 0.3s ease-in-out;
            overflow: hidden;
            position: relative;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-outline.card-primary {
            border-top: none;
        }

        /* Header styling */
        .card-header {
            background: linear-gradient(135deg, #64b5f6 0%, #1565c0 100%);
            color: white;
            border-bottom: none;
            padding: 25px 20px 70px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(183, 28, 28, 0.3);
        }


        .card-header .h1 {
            color: white;
            margin-top: 10px;
            font-weight: 700;
        }

        .brand-subtext {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
        }

        /* Wave effect like in dashboard */
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
            fill: rgba(255, 255, 255, 0.1);
        }

        /* Card body styling */
        .card-body {
            padding: 30px 25px;
            position: relative;
            z-index: 1;
            margin-top: -20px;
            background-color: #fff;
            border-radius: 12px 12px 0 0;
        }

        .login-box-msg {
            color: #5a5c69;
            font-size: 16px;
            margin-bottom: 25px;
            font-weight: 500;
        }

        /* Form elements styling */
        .form-control {
            border-radius: 10px;
            padding: 12px;
            height: auto;
            border: 1px solid #d1d3e2;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #1565c0;
            box-shadow: 0 0 0 0.2rem rgba(78, 141, 223, 0.25);
        }

        .input-group-text {
            border-radius: 0 10px 10px 0;
            background-color: #f8f9fc;
            border: 1px solid #d1d3e2;
            border-left: none;
            color: #1565c0;
        }

        /* Button styling matching dashboard */
        .btn-primary {
            background: linear-gradient(135deg, #356de5 0%, #1c2cb7 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0017e9 0%, #2780d3 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(223, 78, 78, 0.25);
        }

        /* Alert styling */
        .alert {
            border-radius: 10px;
            border-left: 4px solid #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        /* Logo styling - UPDATED */
        .logo-container {
            background-color: #ffffff;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            /* supaya gambar nggak keluar dari lingkaran */
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* isi penuh dan bulat, mirip cropping */
            border-radius: 50%;
        }


        /* Avatar decoration like in dashboard */
        .avatar-decoration {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Password toggle styling */
        .password-toggle {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            color: #2246be;
        }

        /* Modal styling */
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: none;
            padding: 1.5rem;
        }

        .modal-header.bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        }

        .modal-body {
            padding: 1.5rem;
            font-size: 1rem;
            color: #5a5c69;
        }

        .modal-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            padding: 1rem 1.5rem;
        }

        .btn-secondary {
            background-color: #858796;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
        }

        .btn-secondary:hover {
            background-color: #717384;
        }

        /* Modal animations */
        .modal.fade .modal-dialog {
            transform: translate(0, -50px);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: none;
        }

        /* Inactive account modal specific styles */
        #inactiveAccountModal .modal-content {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(220, 53, 69, 0.15);
        }

        #inactiveAccountModal .modal-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border-bottom: none;
            padding: 1.5rem;
        }

        #inactiveAccountModal .modal-body {
            padding: 2rem;
        }

        #inactiveAccountModal .fa-user-lock {
            color: #dc3545;
            opacity: 0.8;
        }

        #inactiveAccountModal .close {
            color: white;
            opacity: 0.8;
            text-shadow: none;
            transition: opacity 0.3s;
        }

        #inactiveAccountModal .close:hover {
            opacity: 1;
        }

        #inactiveAccountModal .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        #inactiveAccountModal .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .login-box {
                width: 90%;
            }

            .card-header {
                padding: 20px 15px 60px;
            }

            .card-body {
                padding: 25px 15px;
            }

            .logo-container {
                width: 80px;
                height: 80px;
            }


        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- Login card -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <div class="avatar-decoration">
                    <i class="fas fa-user-shield text-white"></i>
                </div>
                <!-- Updated logo container with white background -->
                <div class="logo-container">
                    <img src="{{ url('img/image.png') }}" alt="Logo">
                </div>
                <a href="#" class="h1"><b>SILAPA</b></a><br>
                <span class="brand-subtext">Sistem Layanan Pencairan Anggaran</span>

                <!-- Wave effect -->
                <div class="wave-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none"
                        class="wave">
                        <path
                            d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,133.3C672,139,768,181,864,181.3C960,181,1056,139,1152,122.7C1248,107,1344,117,1392,122.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                @if (session('error'))
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @error('email')
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ $message }}
                    </div>
                @enderror

                @if (session('inactive'))
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-ban mr-2"></i>
                        {{ session('inactive') }}
                    </div>
                @endif

                <form action="{{ route('actionLogin') }}" method="post" name="login-form">
                    @csrf
                    <div class="input-group mb-4">
                        <input type="text" class="form-control" name="nip" placeholder="NRP/NIP" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-id-card"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-4">
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text password-toggle" id="togglePassword">
                                <span class="fas fa-eye"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                            </button>
                        </div>
                    </div>
                </form>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- Inactive Account Modal -->
    <div class="modal fade" id="inactiveAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-exclamation-circle mr-2"></i>Akun Nonaktif
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-lock fa-3x text-danger mb-3"></i>
                        <h5>Akses Ditolak</h5>
                    </div>
                    <p>{{ session('inactive') ?: 'Akun Anda sedang dinonaktifkan. Silakan hubungi administrator untuk informasi lebih lanjut.' }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @if (session('inactive'))
        <script>
            $(document).ready(function() {
                $('#inactiveAccountModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#inactiveAccountModal').modal('show');
            });
        </script>
    @endif

    <!-- jQuery -->
    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('dist/js/adminlte.js') }}"></script>

    <!-- Password Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                // Toggle password visibility
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle eye icon
                this.querySelector('span').classList.toggle('fa-eye');
                this.querySelector('span').classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>

</html>
