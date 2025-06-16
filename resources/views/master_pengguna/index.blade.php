@extends('dashboard')

@section('konten')
    <!-- Add custom CSS for the reset password modal -->
    <style>
        /* Existing styles */
        .avatar-circle {
            width: 80px;
            height: 80px;
            background-color: #3490dc;
            text-align: center;
            border-radius: 50%;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .initials {
            position: relative;
            font-size: 40px;
            line-height: 40px;
            color: #fff;
            font-weight: bold;
        }

        .modal-header.bg-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
        }

        .toggle-password {
            cursor: pointer;
        }

        .password-strength {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            margin-top: 10px;
        }

        .password-strength-bar {
            width: 0;
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        /* New styles for enhanced reset password modal */
        .reset-password-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .user-avatar {
            position: relative;
            margin-bottom: 20px;
        }

        .avatar-badge {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #17a2b8;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border: 3px solid white;
        }

        .password-input-container {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .password-input-container h6 {
            color: #495057;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .password-requirements {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 12px 15px;
            margin-top: 15px;
            border: 1px solid #e9ecef;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .requirement-item i {
            font-size: 8px;
            margin-right: 8px;
            transition: all 0.3s ease;
        }

        .requirement-item:last-child {
            margin-bottom: 0;
        }

        .requirement-met {
            color: #28a745;
        }

        .requirement-unmet {
            color: #6c757d;
        }

        .modal-content-reset {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header-reset {
            background: linear-gradient(135deg, #0693e3 0%, #17a2b8 100%);
            color: white;
            border-bottom: none;
            padding: 20px 25px;
        }

        .modal-footer-reset {
            border-top: none;
            background-color: #f8f9fa;
            padding: 15px 25px;
        }

        .btn-reset {
            border-radius: 50px;
            padding: 8px 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-cancel {
            background-color: #e9ecef;
            color: #495057;
        }

        .btn-submit-reset {
            background: linear-gradient(135deg, #0693e3 0%, #17a2b8 100%);
            color: white;
        }

        /* Reset Password Modal Styles - More specific selectors to avoid conflicts */
        #resetPasswordModal .modal-dialog-centered .modal-content {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        #resetPasswordModal .avatar-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            text-align: center;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(19, 132, 150, 0.3);
            animation: fadeInDown 0.3s ease;
        }

        #resetPasswordModal .modal-header {
            padding: 20px 25px;
            border-bottom: none;
        }

        #resetPasswordModal .modal-body {
            padding: 25px;
        }

        #resetPasswordModal .modal-footer {
            border-top: 1px solid #f0f0f0;
            padding: 15px 25px;
            background-color: #f8f9fa;
        }

        #resetPasswordModal .alert {
            text-align: left;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        #resetPasswordModal .alert-warning {
            border-left-color: #ffc107;
            background-color: #fff8e1;
        }

        #resetPasswordModal .alert-info {
            border-left-color: #17a2b8;
            background-color: #e3f2fd;
        }

        #resetPasswordModal .alert:last-child {
            margin-bottom: 0;
        }

        #resetPasswordModal .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            border: none;
            transition: all 0.3s ease;
            border-radius: 4px;
            font-weight: 500;
            padding: 8px 20px;
        }

        #resetPasswordModal .btn-info:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(23, 162, 184, 0.2);
        }

        #resetPasswordModal .btn-secondary {
            background-color: #f8f9fa;
            color: #495057;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        #resetPasswordModal .btn-secondary:hover {
            background-color: #e9ecef;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive table styles */
        .table-responsive {
            margin-bottom: 1rem;
            -webkit-overflow-scrolling: touch;
        }

        /* Custom scrollbar for better UX */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Ensure buttons in action column stay in one line */
        .btn-group-sm>.btn,
        .btn-sm {
            padding: .25rem .5rem;
            font-size: .875rem;
            line-height: 1.5;
            border-radius: .2rem;
        }

        /* Responsive text handling */
        .text-nowrap {
            white-space: nowrap !important;
        }

        @media screen and (max-width: 768px) {

            .table td,
            .table th {
                padding: .5rem;
            }

            .btn-sm {
                padding: .2rem .4rem;
                font-size: .775rem;
            }
        }
    </style>

    <div class="content-wrapper">


        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Master Pengguna</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show floating-alert" role="alert">
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <!-- Tombol Tambah User -->
                <button type="button" class="btn btn-danger mb-3" data-toggle="modal" data-target="#tambahUser">
                    Tambah Pengguna
                </button>
                <!-- Tombol Ekspor -->

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Pengguna
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table id="tabel-data" class="table datatable table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Nama</th>
                                    <th width="15%">Email</th>

                                    <th width="10%">NIP/NIK</th>
                                    <th width="10%">Jabatan</th>

                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>

                                        <td>{{ $user->nip }}</td>
                                        <td>{{ $user->nama_jabatan }}</td>




                                        <td>
                                            <a href="{{ route('master_pengguna.edit', $user->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Improved Reset Password Button (only for admins) -->
                                            @if (auth()->user()->role == 2)
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                    data-target="#resetPasswordModal{{ $user->id }}"
                                                    title="Reset Password">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteModal{{ $user->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1"
                                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        jika anda menekan tombol hapus maka data akan terhapus
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Kembali</button>
                                                        <form action="{{ route('master_pengguna.destroy', $user->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reset Password Modal -->
                                        <div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1"
                                            aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title" id="resetPasswordModalLabel">
                                                            <i class="fas fa-key mr-2"></i> Reset Password
                                                        </h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <div class="avatar-circle mb-3">
                                                            <span class="initials">{{ substr($user->name, 0, 1) }}</span>
                                                        </div>
                                                        <h5 class="mb-0">{{ $user->name }}</h5>
                                                        <p class="text-muted mb-3">{{ $user->email }}</p>

                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                                            Password akan direset ke default: <strong>12345678</strong>
                                                        </div>

                                                        @if ($user->role == 0)
                                                            <div class="alert alert-info">
                                                                <i class="fas fa-info-circle mr-2"></i>
                                                                Ini adalah akun siswa. Pastikan untuk memberitahu password
                                                                baru kepada siswa yang bersangkutan.
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">
                                                            <i class="fas fa-times mr-1"></i> Batal
                                                        </button>
                                                        <form action="{{ route('master_user.reset_password', $user->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('POST')
                                                            <button type="submit" class="btn btn-info">
                                                                <i class="fas fa-key mr-1"></i> Reset Password
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="tambahUser" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Tambah User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('master_pengguna.store') }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" placeholder="Masukkan nama" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}"
                                required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Masukkan password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" id="nip-group">
                            <label for="nip">NIP/NIK</label>
                            <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip"
                                name="nip" placeholder="Masukkan NIP" value="{{ old('nip') }}">
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="Id">Jabatan</label>
                            <select name="id_jabatan" id="Id" class="form-control" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach ($jabatan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="">Pilih Role</option>
                                <option value="1">Siswa</option>
                                <option value="2">Wali Kelas</option>
                                <option value="3">Kurikulum</option>
                                <option value="4">Kepala Sekolah</option>
                                <option value="5">Kepala Yayasan</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="walas-group">
                            <label for="wali_kelas">Pilih Wali Kelas</label>
                            <select name="id_parent" id="wali_kelas" class="form-control">
                                <option value="">Pilih Wali Kelas</option>
                                @foreach ($walas as $walasItem)
                                    <option value="{{ $walasItem->id }}">{{ $walasItem->name }}</option>
                                @endforeach
                            </select>
                        </div>





                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Impor User -->


    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#tambahUser').modal('show');
            });
        </script>
    @endif

    <script>
        setTimeout(function() {
            $('.floating-alert').alert('close');
        }, 2000);
    </script>

    <script type="text/javascript">
        $('.datatable').DataTable({
            responsive: true
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#role').on('change', function() {
                var selectedRole = $(this).val();

                if (selectedRole == '1') {
                    $('#walas-group').removeClass('d-none');
                } else {
                    $('#walas-group').addClass('d-none');
                    $('#wali_kelas').val('');
                }
            });
        });
    </script>
@endsection
