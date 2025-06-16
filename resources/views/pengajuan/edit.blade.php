@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Pengajuan</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('pengajuan.update', $data->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Judul data -->
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Judul data</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $data->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="col-md-6 mb-3">
                                    <label for="desc" class="form-label">Deskripsi</label>
                                    <input type="text" name="desc"
                                        class="form-control @error('desc') is-invalid @enderror"
                                        value="{{ old('desc', $data->desc) }}" required>
                                    @error('desc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Upload Bukti -->
                                <div class="col-md-12 mb-4">
                                    <label for="bukti" class="form-label">Upload Bukti Baru (PDF/JPG/PNG)</label>
                                    <input type="file" name="bukti"
                                        class="form-control @error('bukti') is-invalid @enderror"
                                        accept=".jpg,.jpeg,.png,.pdf">
                                    @error('bukti')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    @if ($data->bukti)
                                        <div class="mt-2">
                                            <small>Bukti saat ini:</small><br>
                                            @if (Str::endsWith($data->bukti, ['.jpg', '.jpeg', '.png']))
                                                <img src="{{ asset('bukti_pengajuan/' . $data->bukti) }}" alt="Bukti"
                                                    height="100">
                                            @else
                                                <a href="{{ asset('bukti_pengajuan/' . $data->bukti) }}"
                                                    target="_blank">Lihat
                                                    file</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Tombol -->
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary mr-2">Batal</a>
                                <button type="submit" class="btn btn-danger">Perbarui data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
