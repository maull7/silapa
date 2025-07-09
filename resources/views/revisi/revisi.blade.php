@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <h1 class="m-0"><i class="fas fa-paper-plane"></i>Revisi Pengajuan</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Flash message -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('revisi.store') }}" method="POST" enctype="multipart/form-data"
                            id="pengajuanForm">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="id" value="{{ $data['request']->id }}">

                                @foreach ($data['revisi_files'] as $file)
                                    @php
                                        $field = $file['field'];
                                        $fileName = $file['file_path'] ?? null;
                                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                        $url = $fileName ? asset('request_uploads/' . $fileName) : null;

                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                                        $isPDF = $ext === 'pdf';
                                    @endphp

                                    <div class="col-md-6 mb-3">
                                        <label for="{{ $field }}" class="form-label">
                                            📎 {{ strtoupper(str_replace('_', ' ', $field)) }}
                                            <span class="badge bg-danger text-white ms-1">✏️ Revisi</span>
                                            <small class="text-muted d-block">(PDF/JPG/PNG)</small>
                                        </label>

                                        {{-- Preview File Lama --}}
                                        @if ($fileName)
                                            <div class="mb-2">
                                                <p class="text-muted small mb-1">File Lama:</p>
                                                @if ($isImage)
                                                    <a href="{{ $url }}" target="_blank">
                                                        <img src="{{ $url }}" alt="Preview"
                                                            class="img-fluid rounded border shadow-sm"
                                                            style="max-height: 200px; object-fit: contain;">
                                                    </a>
                                                @elseif ($isPDF)
                                                    <embed src="{{ $url }}" type="application/pdf" width="100%"
                                                        height="200px" class="border rounded" />
                                                @else
                                                    <a href="{{ $url }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary">
                                                        👁️ Lihat File Lama
                                                    </a>
                                                    <p class="mb-0 small text-muted">{{ $fileName }}</p>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Input file revisi --}}
                                        <input type="file" name="{{ $field }}" id="{{ $field }}"
                                            class="form-control border-danger @error($field) is-invalid @enderror"
                                            accept=".jpg,.jpeg,.png,.pdf"
                                            onchange="previewNewFile(event, '{{ $field }}')">

                                        {{-- Preview File Baru --}}
                                        <div id="preview-{{ $field }}" class="mt-2"></div>

                                        {{-- Komentar --}}
                                        <small class="text-danger d-block mt-1">
                                            ⚠ Komentar: {{ $file['comment'] }}
                                        </small>

                                        @error($field)
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach





                            </div>


                            <!-- Tombol -->
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </section>
    </div>
    <script>
        function previewNewFile(event, field) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('preview-' + field);
            previewContainer.innerHTML = '';

            if (!file) return;

            const fileType = file.type;

            if (fileType.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = `
                    <p class="text-muted small mb-1">Preview File Baru:</p>
                    <img src="${e.target.result}" class="img-fluid rounded border" style="max-height: 200px;" />
                `;
                };
                reader.readAsDataURL(file);
            } else if (fileType === 'application/pdf') {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = `
                    <p class="text-muted small mb-1">Preview File Baru:</p>
                    <embed src="${e.target.result}" type="application/pdf" width="100%" height="200px" class="border rounded" />
                `;
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.innerHTML = `
                <p class="text-muted small">File dipilih: ${file.name}</p>
            `;
            }
        }
    </script>
@endsection
