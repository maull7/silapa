<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SEBASA</title>

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
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border-bottom: none;
            padding: 25px 20px 70px;
            position: relative;
            overflow: hidden;
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
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .input-group-text {
            border-radius: 0 10px 10px 0;
            background-color: #f8f9fc;
            border: 1px solid #d1d3e2;
            border-left: none;
            color: #4e73df;
        }

        /* Button styling matching dashboard */
        .btn-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #224abe 0%, #4e73df 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.25);
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
            padding: 5px;
        }

        .logo-container img {
            width: 80px;
            height: auto;
            object-fit: contain;
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
            color: #224abe;
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

            .logo-container img {
                width: 60px;
            }
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <div class="avatar-decoration">
                    <i class="fas fa-key text-white"></i>
                </div>
                <div class="logo-container">
                    <img src="{{ url('img/image.png') }}" alt="Logo">
                </div>
                <a href="#" class="h1"><b>SEBASA</b></a><br>
                <span class="brand-subtext">Sekolah Bahasa Polri</span>
                <div class="wave-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none"
                        class="wave">
                        <path
                            d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,133.3C672,139,768,181,864,181.3C960,181,1056,139,1152,122.7C1248,107,1344,117,1392,122.7L1440,128L1440,320L0,320Z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Silahkan Ubah Password Anda Untuk Melanjutkan Login</p>

                @if (session('error'))
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('change.password') }}" method="post">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="new_password" id="newPassword"
                            placeholder="Password Baru" required>
                        <div class="input-group-append">
                            <div class="input-group-text password-toggle" id="toggleNewPassword">
                                <span class="fas fa-eye"></span>
                            </div>
                        </div>
                        @error('new_password')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group mb-4">
                        <input type="password" class="form-control" name="new_password_confirmation"
                            id="confirmPassword" placeholder="Konfirmasi Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text password-toggle" id="toggleConfirmPassword">
                                <span class="fas fa-eye"></span>
                            </div>
                        </div>
                        @error('new_password_confirmation')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save mr-2"></i> Simpan Password Baru
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Toggle Password Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = [{
                    btn: 'toggleNewPassword',
                    input: 'newPassword'
                },
                {
                    btn: 'toggleConfirmPassword',
                    input: 'confirmPassword'
                }
            ];

            toggles.forEach(pair => {
                const toggleBtn = document.getElementById(pair.btn);
                const inputField = document.getElementById(pair.input);

                toggleBtn.addEventListener('click', function() {
                    const type = inputField.getAttribute('type') === 'password' ? 'text' :
                        'password';
                    inputField.setAttribute('type', type);
                    this.querySelector('span').classList.toggle('fa-eye');
                    this.querySelector('span').classList.toggle('fa-eye-slash');
                });
            });
        });
    </script>

    <!-- Scripts -->
    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('dist/js/adminlte.js') }}"></script>
</body>


</html>
