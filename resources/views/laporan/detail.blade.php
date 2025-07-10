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
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-file-alt text-primary mr-2"></i> Detail Pengajuan
                            <span
                                class="badge ml-auto 
                        @if ($data->status == 'approve') badge-success 
                        @elseif($data->status == 'rejected') badge-danger 
                        @else badge-warning @endif">
                                {{ ucfirst($data->status) }}
                            </span>
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Kiri -->
                            <div class="col-lg-6">
                                <h6 class="text-muted small">Judul Pengajuan</h6>
                                <p>{{ $data->title }}</p>

                                <h6 class="text-muted small">Deskripsi</h6>
                                <p>{{ $data->desc }}</p>

                                <h6 class="text-muted small">Pengaju</h6>
                                <p>{{ $data->name }}</p>
                            </div>

                            <!-- Kanan -->
                            <div class="col-lg-6">
                                <h6 class="text-muted small">Keterangan</h6>
                                <p>{{ $data->keterangan ?: 'Tidak ada keterangan' }}</p>

                                <h6 class="text-muted small">Tanggal Pengajuan</h6>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mt-4"><i class="fas fa-paperclip text-primary me-2"></i> Bukti Pengajuan</h5>
                            <a href="{{ route('laporan.downloadZip', $data->id) }}" class="btn btn-outline-dark btn-sm">
                                <i class="fas fa-file-archive"></i> Download Semua (ZIP)
                            </a>


                        </div>
                        <div class="row">
                            @foreach ($fileUploads as $field => $file)
                                @php
                                    $fileName = $file->file_path;
                                    $url = asset('request_uploads/' . $fileName);
                                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                                    $isPDF = $ext === 'pdf';
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body">
                                            <strong>{{ Str::headline($file->field) }}</strong>
                                            <div class="mt-2">
                                                @if ($isImage)
                                                    <img src="{{ $url }}"
                                                        class="img-fluid rounded shadow-sm border"
                                                        style="max-height: 200px;">
                                                @elseif ($isPDF)
                                                    <iframe src="{{ $url }}#toolbar=0" width="100%"
                                                        height="200px" class="border rounded"></iframe>
                                                @else
                                                    <i class="fas fa-file-alt fa-2x text-secondary"></i>
                                                    <p class="small mt-2">{{ $fileName }}</p>
                                                @endif
                                                <a href="{{ $url }}" target="_blank"
                                                    class="btn btn-outline-primary btn-sm mt-2 w-100">
                                                    <i class="fas fa-download me-1"></i> Lihat Dokumen
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if (!empty($bensat))
                            <h5 class="mt-5"><i class="fas fa-file-invoice-dollar text-success me-2"></i> Dokumen
                                (Bensat)</h5>
                            <div class="row">
                                @foreach (['upload_1', 'upload_2'] as $field)
                                    @php
                                        $fileName = $bensat->$field ?? null;
                                        $url = $fileName ? asset('berkas_bensat/' . $fileName) : null;
                                        $ext = $fileName ? strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) : null;
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                                        $isPDF = $ext === 'pdf';
                                    @endphp

                                    @if ($fileName)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card h-100 shadow-sm">
                                                <div class="card-body">
                                                    <strong>{{ strtoupper(str_replace('_', ' ', $field)) }}</strong>
                                                    <div class="mt-2">
                                                        @if ($isImage)
                                                            <img src="{{ $url }}"
                                                                class="img-fluid rounded shadow-sm border"
                                                                style="max-height: 200px;">
                                                        @elseif ($isPDF)
                                                            <iframe src="{{ $url }}#toolbar=0" width="100%"
                                                                height="200px" class="border rounded"></iframe>
                                                        @else
                                                            <i class="fas fa-file-alt fa-2x text-secondary"></i>
                                                            <p class="small mt-2">{{ $fileName }}</p>
                                                        @endif
                                                        <a href="{{ $url }}" target="_blank"
                                                            class="btn btn-outline-success btn-sm mt-2 w-100">
                                                            <i class="fas fa-download me-1"></i> Unduh
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p>Belum ada dokumen dari bensat</p>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
