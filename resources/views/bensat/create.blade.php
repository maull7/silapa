@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <h1 class="m-0"><i class="fas fa-paper-plane"></i> Tambah Berkas Bensat</h1>
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

                        <form action="{{ route('bensat.store', $request->id) }}" method="POST"
                            enctype="multipart/form-data" id="pengajuanForm">
                            @csrf

                            <div class="row">
                                @php
                                    $filePath1 = $bensat->upload_1 ?? null;
                                    $filePath2 = $bensat->upload_2 ?? null;
                                @endphp

                                <!-- Upload 1 -->
                                <div class="col-md-6 mb-3">
                                    <label for="upload_1" class="form-label">Upload File 1</label>
                                    @if ($filePath1)
                                        <div class="mb-2">
                                            @php
                                                $ext1 = strtolower(pathinfo($filePath1, PATHINFO_EXTENSION));
                                                $isImg1 = in_array($ext1, ['jpg', 'jpeg', 'png']);
                                                $isPdf1 = $ext1 === 'pdf';
                                            @endphp

                                            @if ($isImg1)
                                                <img src="{{ asset('berkas_bensat/' . $filePath1) }}"
                                                    class="img-fluid border rounded" style="max-height: 200px;">
                                            @elseif ($isPdf1)
                                                <embed src="{{ asset('berkas_bensat/' . $filePath1) }}"
                                                    type="application/pdf" width="100%" height="200px"
                                                    class="border rounded">
                                            @else
                                                <a href="{{ asset('berkas_bensat/' . $filePath1) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary">
                                                    👁️ Lihat File Sebelumnya
                                                </a>
                                            @endif

                                            <p class="mb-0 small text-muted">{{ $filePath1 }}</p>
                                        </div>
                                    @endif

                                    <input type="file" name="upload_1"
                                        class="form-control @error('upload_1') is-invalid @enderror"
                                        accept=".jpg,.jpeg,.png,.pdf" onchange="previewFile(event, 'preview1')">
                                    @error('upload_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="preview1" class="mt-2"></div>
                                </div>

                                <!-- Upload 2 -->
                                <div class="col-md-6 mb-3">
                                    <label for="upload_2" class="form-label">Upload File 2</label>
                                    @if ($filePath2)
                                        <div class="mb-2">
                                            @php
                                                $ext2 = strtolower(pathinfo($filePath2, PATHINFO_EXTENSION));
                                                $isImg2 = in_array($ext2, ['jpg', 'jpeg', 'png']);
                                                $isPdf2 = $ext2 === 'pdf';
                                            @endphp

                                            @if ($isImg2)
                                                <img src="{{ asset('berkas_bensat/' . $filePath2) }}"
                                                    class="img-fluid border rounded" style="max-height: 200px;">
                                            @elseif ($isPdf2)
                                                <embed src="{{ asset('berkas_bensat/' . $filePath2) }}"
                                                    type="application/pdf" width="100%" height="200px"
                                                    class="border rounded">
                                            @else
                                                <a href="{{ asset('berkas_bensat/' . $filePath2) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary">
                                                    👁️ Lihat File Sebelumnya
                                                </a>
                                            @endif

                                            <p class="mb-0 small text-muted">{{ $filePath2 }}</p>
                                        </div>
                                    @endif

                                    <input type="file" name="upload_2"
                                        class="form-control @error('upload_2') is-invalid @enderror"
                                        accept=".jpg,.jpeg,.png,.pdf" onchange="previewFile(event, 'preview2')">
                                    @error('upload_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="preview2" class="mt-2"></div>
                                </div>
                            </div>

                            <!-- Tombol -->
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save"></i> Simpan Pengajuan
                                </button>
                            </div>
                        </form>






                        <style>
                            .upload-step {
                                transition: all 0.3s ease;
                            }

                            .progress {
                                background-color: #e9ecef;
                            }

                            .file-preview .alert {
                                margin-bottom: 0;
                            }

                            .step-file-display {
                                background-color: #fff;
                            }
                        </style>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <!-- JS Preview -->
    <script>
        function previewFile(event, previewId) {
            const file = event.target.files[0];
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';

            if (!file) return;

            const ext = file.name.split('.').pop().toLowerCase();

            if (['jpg', 'jpeg', 'png'].includes(ext)) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML =
                        `<img src="${e.target.result}" class="img-fluid border rounded" style="max-height: 200px;">`;
                };
                reader.readAsDataURL(file);
            } else if (ext === 'pdf') {
                const url = URL.createObjectURL(file);
                preview.innerHTML =
                    `<embed src="${url}" type="application/pdf" width="100%" height="200px" class="border rounded">`;
            } else {
                preview.innerHTML = `<p class="text-danger small">Preview tidak tersedia untuk format ini.</p>`;
            }
        }
    </script>
@endsection
