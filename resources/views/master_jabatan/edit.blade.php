<!-- View: jurusan.edit -->
@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Jabatan</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-edit me-1"></i>
                        Edit Jabatan
                    </div>
                    <div class="card-body">
                        <form action="{{ route('master_jabatan.update', $data->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="nama_jabatan">Nama Jabatan</label>
                                <input type="text" name="nama_jabatan" class="form-control"
                                    value="{{ $data->nama_jabatan }}" required>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-danger">Simpan</button>
                                <a href="{{ route('master_jabatan.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
