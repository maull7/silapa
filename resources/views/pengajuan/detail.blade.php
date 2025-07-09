@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-0 text-dark font-weight-bold">Detail Pengajuan</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="" class="text-muted">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('pengajuan.index') }}" class="text-muted">Daftar
                                    Pengajuan</a></li>
                            <li class="breadcrumb-item active text-primary">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-file-alt text-primary mr-2"></i>
                            Informasi Pengajuan
                            <span
                                class="badge ml-auto font-weight-normal 
                                @if ($data->status == 'approve') badge-success 
                                @elseif($data->status == 'rejected') badge-danger 
                                @else badge-warning @endif">
                                {{ ucfirst($data->status) }}
                            </span>
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted small font-weight-bold">Judul Pengajuan</h6>
                                    <p class="font-size-lg">{{ $data->title }}</p>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted small font-weight-bold">Deskripsi</h6>
                                    <p class="font-size-lg">{{ $data->desc }}</p>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted small font-weight-bold">Diajukan Oleh</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary text-white rounded-circle mr-2">
                                            {{ substr($data->pengaju_name, 0, 1) }}
                                        </div>
                                        <p class="mb-0 font-size-lg">{{ $data->pengaju_name }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-6">
                                <div class="card bg-light border-0 mb-4">
                                    <div class="card-body">
                                        <h6 class="text-uppercase text-muted small font-weight-bold">Keterangan</h6>
                                        <p class="font-size-lg">{{ $data->keterangan ?: 'Tidak ada keterangan tambahan' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted small font-weight-bold">Tanggal Pengajuan</h6>
                                    <p class="font-size-lg">
                                        {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Pengajuan -->
                        <div class="mb-5">
                            <h5 class="mb-3 d-flex align-items-center">
                                <i class="fas fa-paperclip text-primary me-2"></i>
                                Bukti Pengajuan
                            </h5>

                            <div class="row g-4">
                                @foreach ($fileUploads as $field => $file)
                                    @php
                                        $fileName = $file->file_path;
                                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                        $url = asset('request_uploads/' . $fileName);
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                                        $isPDF = $ext === 'pdf';
                                        $isWord = in_array($ext, ['doc', 'docx']);
                                        $isExcel = in_array($ext, ['xls', 'xlsx']);
                                    @endphp

                                    <div class="col-md-6 col-xl-4">
                                        <div class="card shadow-sm h-100 border-0">
                                            <div class="card-body">
                                                <p class="fw-semibold text-muted small mb-2">
                                                    {{ Str::headline($field) }}
                                                </p>

                                                @if ($isImage)
                                                    <a href="{{ $url }}" data-lightbox="uploads">
                                                        <img src="{{ $url }}" alt="Image preview"
                                                            class="img-fluid rounded shadow-sm border"
                                                            style="max-height: 240px; object-fit: contain; cursor: zoom-in;">
                                                    </a>
                                                    <p class="text-muted small mt-2">Klik gambar untuk memperbesar</p>
                                                @elseif ($isPDF)
                                                    <iframe src="{{ $url }}#toolbar=0&navpanes=0" width="100%"
                                                        height="240px"
                                                        style="border:1px solid #ccc; border-radius: 8px;"></iframe>
                                                    <a href="{{ $url }}" target="_blank"
                                                        class="btn btn-outline-primary btn-sm mt-2 w-100">
                                                        <i class="fas fa-eye me-1"></i> Buka PDF Penuh
                                                    </a>
                                                @elseif ($isWord || $isExcel)
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i
                                                            class="fas fa-file-{{ $isWord ? 'word' : 'excel' }} fa-3x text-{{ $isWord ? 'primary' : 'success' }} me-3"></i>
                                                        <div>
                                                            <p class="mb-1 small">{{ $fileName }}</p>
                                                            <a href="{{ $url }}" target="_blank"
                                                                class="btn btn-sm btn-outline-secondary">
                                                                <i class="fas fa-eye me-1"></i> Lihat Dokumen
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="fas fa-file-alt fa-3x text-secondary me-3"></i>
                                                        <div>
                                                            <p class="mb-1 small">{{ $fileName }}</p>
                                                            <a href="{{ $url }}" target="_blank"
                                                                class="btn btn-sm btn-outline-dark">
                                                                <i class="fas fa-download me-1"></i> Unduh
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        <!-- Approval Timeline -->
                        <div class="mb-4">
                            <h5 class="mb-3 d-flex align-items-center">
                                <i class="fas fa-history text-primary mr-2"></i>
                                Riwayat Persetujuan
                            </h5>

                            <div class="timeline timeline-one-side">
                                @forelse ($approvals as $appr)
                                    <div class="timeline-block mb-3">
                                        <span
                                            class="timeline-step
                @if ($appr->status == 'approve') bg-success
                @elseif($appr->status == 'rejected') bg-danger
                @else bg-secondary @endif">
                                            <i
                                                class="fas 
                    @if ($appr->status == 'approve') fa-check
                    @elseif($appr->status == 'rejected') fa-times
                    @else fa-clock @endif text-white"></i>
                                        </span>
                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-0">{{ $appr->approved_by }}</h6>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($appr->approved_at)->format('d M Y H:i') }}</small>
                                            </div>
                                            <p class="mb-0">
                                                <span
                                                    class="badge 
                        @if ($appr->status == 'approve') badge-success
                        @elseif($appr->status == 'rejected') badge-danger
                        @else badge-secondary @endif">
                                                    {{ ucfirst($appr->status) }}
                                                </span>
                                            </p>

                                            @if ($appr->komentar)
                                                <div class="mt-2 p-2 bg-light rounded">
                                                    <p class="mb-1"><strong>Komentar:</strong></p>
                                                    <p class="mb-2">{{ $appr->komentar }}</p>

                                                    <!-- Button Area -->
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <button class="btn btn-sm btn-outline-primary btn-reply"
                                                            data-approval-id="{{ $appr->id_approvals }}">
                                                            <i class="fas fa-reply mr-1"></i> Balas
                                                        </button>

                                                        @php
                                                            $replyCount = DB::table('reply')
                                                                ->where('approvals_id', $appr->id_approvals) // Filter berdasarkan user yang di-reply
                                                                ->count();
                                                        @endphp

                                                        @if ($replyCount > 0)
                                                            <button
                                                                class="btn btn-sm btn-outline-secondary btn-toggle-replies"
                                                                data-approval-id="{{ $appr->id_approvals }}">
                                                                <i class="fas fa-comments mr-1"></i>
                                                                <span class="reply-count">{{ $replyCount }}</span>
                                                                Balasan
                                                            </button>
                                                        @endif
                                                    </div>

                                                    <!-- Replies Container (Hidden) -->
                                                    <!-- Replies Container (Hidden) -->
                                                    <div class="replies-container mt-2 pl-3 border-left"
                                                        id="replies-{{ $appr->id_approvals }}" style="display: none;">
                                                        @php
                                                            $replies = DB::table('reply')
                                                                ->where('approvals_id', $appr->id_approvals)
                                                                ->orderBy('id_reply', 'asc')
                                                                ->get();
                                                        @endphp

                                                        @foreach ($replies as $reply)
                                                            <div class="reply-item mb-3 p-2 bg-light rounded">
                                                                <div class="d-flex justify-content-between">
                                                                    <small>
                                                                        <strong>
                                                                            @php
                                                                                $replier = DB::table('users')
                                                                                    ->where('id', $reply->user_id)
                                                                                    ->first();

                                                                            @endphp
                                                                            {{ $replier->name ?? 'Unknown User' }}

                                                                            @if ($reply->parent_id)
                                                                                @php
                                                                                    $originalCommenter = DB::table(
                                                                                        'reply',
                                                                                    )
                                                                                        ->join(
                                                                                            'users as u1',
                                                                                            'u1.id',
                                                                                            '=',
                                                                                            'reply.user_id',
                                                                                        ) // user yang membuat reply
                                                                                        ->join(
                                                                                            'users as u2',
                                                                                            'u2.id',
                                                                                            '=',
                                                                                            'reply.parent_id',
                                                                                        ) // user yang dikomentari
                                                                                        ->where(
                                                                                            'reply.id_reply',
                                                                                            $reply->id_reply,
                                                                                        )
                                                                                        ->select(
                                                                                            'reply.*',
                                                                                            'u1.name as original_name',
                                                                                            'u2.name as replier_name',
                                                                                        ) // ambil kolom sesuai kebutuhan
                                                                                        ->first();
                                                                                @endphp

                                                                                <span
                                                                                    class="text-muted font-weight-normal">
                                                                                    membalas
                                                                                    <span
                                                                                        class="font-italic">{{ $originalCommenter->replier_name ?? 'Pengguna' }}</span>
                                                                                </span>
                                                                            @endif
                                                                        </strong>
                                                                    </small>
                                                                    <small
                                                                        class="text-muted">{{ \Carbon\Carbon::parse($reply->created_at)->format('d M Y H:i') }}</small>
                                                                </div>
                                                                <p class="mb-2">{{ $reply->komentar }}</p>

                                                                <!-- Button Reply untuk setiap komentar -->
                                                                <button
                                                                    class="btn btn-sm btn-outline-primary btn-reply-reply"
                                                                    data-reply-id="{{ $reply->id_reply }}"
                                                                    style="font-size: 0.8rem;">
                                                                    <i class="fas fa-reply mr-1"></i> Balas
                                                                </button>

                                                                <!-- Form Reply (Hidden) -->
                                                                <div class="reply-reply-form mt-2"
                                                                    id="reply-reply-form-{{ $reply->id_reply }}"
                                                                    style="display: none;">
                                                                    <form
                                                                        action="{{ route('comment.reply', $reply->approvals_id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="parent_id"
                                                                            value="{{ $reply->user_id }}">
                                                                        <input type="hidden" name="user_id"
                                                                            value="{{ $reply->parent_id }}">
                                                                        <div class="input-group input-group-sm">
                                                                            <input type="text" name="komentar"
                                                                                class="form-control"
                                                                                placeholder="Balas {{ $replier->name ?? 'pengguna' }}..."
                                                                                required>
                                                                            <div class="input-group-append">
                                                                                <button class="btn btn-primary"
                                                                                    type="submit">
                                                                                    <i class="fas fa-paper-plane"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>





                                                    <!-- Reply Form (Hidden) -->
                                                    <div class="reply-form mt-2"
                                                        id="reply-form-{{ $appr->id_approvals }}" style="display: none;">
                                                        <form action="{{ route('approval.reply', $appr->id_approvals) }}"
                                                            method="POST">
                                                            @csrf

                                                            <div class="input-group">
                                                                <input type="text" name="komentar"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Tulis balasan..." required>
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-sm btn-primary" type="submit">
                                                                        <i class="fas fa-paper-plane"></i> Kirim
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light border">

                                        Belum ada riwayat persetujuan
                                    </div>
                                @endforelse
                            </div>


                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            @php
                                $fromPage = session('from_page');

                            @endphp

                            <a href="{{ url($fromPage) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>




                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>



    <!-- Reject Modal -->

    <script>
        $(document).ready(function() {
            // Toggle reply form
            $('.btn-reply').click(function() {
                const approvalId = $(this).data('approval-id');
                $('#reply-form-' + approvalId).toggle();
                $('#replies-' + approvalId).hide(); // Hide replies when showing form
            });

            // Toggle replies visibility
            $('.btn-toggle-replies').click(function() {
                const approvalId = $(this).data('approval-id');
                $('#replies-' + approvalId).toggle();
                $('#reply-form-' + approvalId).hide(); // Hide form when showing replies
            });
            $('.btn-reply-reply').click(function() {
                const replyId = $(this).data('reply-id');
                $('#reply-reply-form-' + replyId).toggle();
                $('#replies-' + replyId).hide(); // Hide replies when showing form
            });
        });
    </script>


    <style>
        .avatar {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .timeline {
            position: relative;
            padding-left: 4rem;
        }

        .timeline-block {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-step {
            position: absolute;
            left: -4rem;
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 3px #fff;
        }

        .timeline-content {
            background: #fff;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid #eee;
        }

        .timeline-content:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .font-size-lg {
            font-size: 1.1rem;
        }
    </style>
@endsection
