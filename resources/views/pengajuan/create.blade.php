@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <h1 class="m-0"><i class="fas fa-paper-plane"></i> Buat Pengajuan</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Flash message (optional) -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data"
                            id="pengajuanForm">
                            @csrf

                            <div class="row">
                                <!-- Judul Pengajuan -->
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">📄 Judul Pengajuan</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}" placeholder="Contoh: Permintaan Dana Buku" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="col-md-6 mb-3">
                                    <label for="desc" class="form-label">📝 Deskripsi</label>
                                    <input type="text" name="desc"
                                        class="form-control @error('desc') is-invalid @enderror" value="{{ old('desc') }}"
                                        placeholder="Contoh: Dana pembelian buku pelajaran" required>
                                    @error('desc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Upload Bukti -->
                                <div class="col-md-12 mb-4">
                                    <label for="bukti" class="form-label">📎 Upload Bukti <small
                                            class="text-muted">(PDF/JPG/PNG)</small></label>
                                    <input type="file" name="bukti" id="bukti"
                                        class="form-control @error('bukti') is-invalid @enderror"
                                        accept=".jpg,.jpeg,.png,.pdf">
                                    @error('bukti')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    <!-- Preview -->
                                    <div class="mt-2" id="previewContainer" style="display: none;">
                                        <strong>Preview:</strong><br>
                                        <img id="previewImage" src="" alt="Preview" class="img-thumbnail"
                                            style="max-height: 150px;">
                                        <p id="previewText" class="text-muted"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol -->
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-save"></i> Simpan Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- JS Preview -->
    @push('scripts')
        <script>
            document.getElementById('bukti').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const previewContainer = document.getElementById('previewContainer');
                const previewImage = document.getElementById('previewImage');
                const previewText = document.getElementById('previewText');

                if (file) {
                    const fileType = file.type;
                    const reader = new FileReader();

                    if (fileType.startsWith('image/')) {
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewImage.style.display = 'block';
                            previewText.style.display = 'none';
                        }
                        reader.readAsDataURL(file);
                        previewContainer.style.display = 'block';
                    } else if (fileType === 'application/pdf') {
                        previewImage.style.display = 'none';
                        previewText.innerHTML = '📄 File PDF terpilih: <strong>' + file.name + '</strong>';
                        previewText.style.display = 'block';
                        previewContainer.style.display = 'block';
                    } else {
                        previewContainer.style.display = 'none';
                    }
                }
            });
        </script>
    @endpush
@endsection
