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
                        <h1 class="m-0">Verifikasi Pengajuan</h1>
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

                <!-- Tombol Ekspor -->

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Pengajuan
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table id="tabel-data" class="table datatable table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Yang Mengajukan</th>
                                    <th width="15%">Nama Pengadaan</th>
                                    <th width="15%">Deskripsi</th>

                                    <th width="10%">Status</th>
                                    <th width="10%">Keterangan</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            {{ $item->desc }}
                                        </td>

                                        <td>
                                            @if ($item->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                @if ($item->status == 'approve' && $item->level < 4)
                                                    <span class="badge badge-warning">Menunggu Di Approve</span>
                                                @elseif($item->status == 'repair' && $item->level < 4)
                                                    <span class="badge badge-info">Sudah diperbaiki</span>
                                                @elseif($item->status == 'revisi' && $item->level < 4)
                                                    <span class="badge badge-info">Menunggu revisi</span>
                                                @elseif($item->status == 'rejected')
                                                    <span class="badge badge-warning">Di tolak</span>
                                                @else
                                                    <span class="badge badge-success">Di Approve</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $item->keterangan }}</td>





                                        <!-- Delete Modal -->

                                        <td>
                                            @php
                                                $userLevel = Auth::user()->role;
                                                $pengajuanLevel = $item->level;
                                            @endphp

                                            @if ($pengajuanLevel === $userLevel - 1 && in_array($item->status, ['pending', 'approve', 'repair']))
                                                <button class="btn btn-success btn-sm" data-toggle="modal"
                                                    data-target="#approveModal-{{ $item->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#rejectModal-{{ $item->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @elseif($pengajuanLevel == $userLevel && $item->status == 'revisi')
                                                <a href="{{ route('revisi', ['id' => $item->id, 'from' => request()->path()]) }}"
                                                    class="btn btn-secondary btn-sm">
                                                    <i class="fas fa-wrench"></i>
                                                </a>
                                            @else
                                            @endif
                                            <a href="{{ route('detail', ['id' => $item->id, 'from' => request()->path()]) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>


                                            <div class="modal fade" id="approveModal-{{ $item->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="approveLabel-{{ $item->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form action="{{ route('approve', $item->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success text-white">
                                                                <h5 class="modal-title"
                                                                    id="approveLabel-{{ $item->id }}">Setujui Pengajuan
                                                                </h5>
                                                                <button type="button" class="close text-white"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="comment-approve-{{ $item->id }}">Komentar</label>
                                                                    <textarea name="komentar" id="comment-approve-{{ $item->id }}" class="form-control" rows="3"
                                                                        placeholder="Masukkan komentar jika ada..."></textarea>
                                                                </div>
                                                                <p class="mt-3">Yakin ingin menyetujui pengajuan ini?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit"
                                                                    class="btn btn-success">Setujui</button>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Batal</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            {{-- Modal Reject --}}
                                            @foreach ($data as $item)
                                                <div class="modal fade" id="rejectModal-{{ $item->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="rejectLabel-{{ $item->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <form action="{{ route('reject', $item->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title"
                                                                        id="rejectLabel-{{ $item->id }}">Tolak / Revisi
                                                                        Pengajuan</h5>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <!-- Aksi -->
                                                                    <div class="form-group">
                                                                        <label><strong>Pilih Aksi:</strong></label>
                                                                        <select name="aksi" class="form-control"
                                                                            id="action-{{ $item->id }}"
                                                                            onchange="toggleRevisiSection({{ $item->id }})"
                                                                            required>
                                                                            <option value="tolak">Tolak</option>
                                                                            <option value="revisi">Revisi</option>
                                                                        </select>
                                                                    </div>

                                                                    <!-- Komentar Umum -->
                                                                    <div class="form-group">
                                                                        <label><strong>Komentar Umum</strong></label>
                                                                        <textarea name="komentar" class="form-control" rows="3"
                                                                            placeholder="Tulis alasan umum penolakan atau revisi..."></textarea>
                                                                    </div>

                                                                    <!-- Komentar per file -->
                                                                    <div class="revisi-section d-none"
                                                                        id="revisi-section-{{ $item->id }}">
                                                                        <label><strong>Pilih File yang Perlu
                                                                                Direvisi:</strong></label>
                                                                        <div class="row">
                                                                            @foreach ($item->uploads as $upload)
                                                                                @php
                                                                                    $fileName = $upload->file_path;
                                                                                    $field = $upload->field;
                                                                                    $fileUrl = asset(
                                                                                        'request_uploads/' . $fileName,
                                                                                    );
                                                                                @endphp

                                                                                <div class="col-md-6 mb-3">
                                                                                    <div class="card border">
                                                                                        <div class="card-body">
                                                                                            <div class="form-check mb-2">
                                                                                                <input type="checkbox"
                                                                                                    class="form-check-input"
                                                                                                    id="check-{{ $field }}-{{ $item->id }}"
                                                                                                    name="revisi_files[{{ $upload->id }}][checked]"
                                                                                                    value="on"
                                                                                                    onchange="toggleFileComment('{{ $field }}', {{ $item->id }})">
                                                                                                <label
                                                                                                    class="form-check-label"
                                                                                                    for="check-{{ $field }}-{{ $item->id }}">
                                                                                                    Revisi:
                                                                                                    {{ Str::headline($field) }}
                                                                                                </label>
                                                                                            </div>

                                                                                            <div>
                                                                                                @php
                                                                                                    $extension = strtolower(
                                                                                                        pathinfo(
                                                                                                            $fileName,
                                                                                                            PATHINFO_EXTENSION,
                                                                                                        ),
                                                                                                    );
                                                                                                @endphp

                                                                                                <!-- Link download -->
                                                                                                <a href="{{ $fileUrl }}"
                                                                                                    target="_blank"
                                                                                                    class="d-block mb-2 text-primary">
                                                                                                    <i
                                                                                                        class="fas fa-file-download me-1"></i>
                                                                                                    {{ $fileName }}
                                                                                                </a>

                                                                                                <!-- Preview file -->
                                                                                                @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                                                                    <img src="{{ $fileUrl }}"
                                                                                                        alt="Preview"
                                                                                                        class="img-fluid rounded border mb-2"
                                                                                                        style="max-height: 200px;">
                                                                                                @elseif($extension === 'pdf')
                                                                                                    <embed
                                                                                                        src="{{ $fileUrl }}"
                                                                                                        type="application/pdf"
                                                                                                        width="100%"
                                                                                                        height="200px"
                                                                                                        class="border rounded mb-2" />
                                                                                                @else
                                                                                                    <div
                                                                                                        class="text-muted small">
                                                                                                        Preview tidak
                                                                                                        tersedia. Silakan
                                                                                                        unduh file.</div>
                                                                                                @endif


                                                                                            </div>

                                                                                            <div id="comment-{{ $field }}-{{ $item->id }}"
                                                                                                class="d-none">
                                                                                                <label
                                                                                                    for="komentar-{{ $field }}-{{ $item->id }}"
                                                                                                    class="small">
                                                                                                    Komentar File
                                                                                                </label>
                                                                                                <textarea class="form-control form-control-sm" name="revisi_files[{{ $upload->id }}][komentar]"
                                                                                                    id="komentar-{{ $field }}-{{ $item->id }}" rows="2"
                                                                                                    placeholder="Tulis alasan revisi untuk file ini..."></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>

                                                                    <p class="mt-3 text-danger font-weight-bold">Yakin
                                                                        ingin melanjutkan aksi ini?</p>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Kirim</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Batal</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach





                                        </td>





                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>


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
        function toggleRevisiSection(id) {
            const select = document.getElementById('action-' + id);
            const revisiBox = document.getElementById('revisi-section-' + id);
            if (select.value === 'revisi') {
                revisiBox.classList.remove('d-none');
            } else {
                revisiBox.classList.add('d-none');
            }
        }

        function toggleFileComment(field, itemId) {
            const checkbox = document.getElementById(`check-${field}-${itemId}`);
            const commentDiv = document.getElementById(`comment-${field}-${itemId}`);
            const textarea = document.getElementById(`komentar-${field}-${itemId}`);

            if (checkbox.checked) {
                commentDiv.classList.remove('d-none');
                textarea.setAttribute('required', 'required');
            } else {
                commentDiv.classList.add('d-none');
                textarea.removeAttribute('required');
                textarea.value = ''; // Clear textarea when unchecked
            }
        }
    </script>
@endsection
