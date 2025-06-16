@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Pengguna</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('master_pengguna.update', $user->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password (Biarkan kosong jika tidak ingin diubah)</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Masukkan password baru">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="form-group" id="nip-group">
                                        <label for="nip">NIP</label>
                                        <input type="text" name="nip" id="nip"
                                            class="form-control @error('nip') is-invalid @enderror"
                                            value="{{ old('nip', $user->nip) }}" required>
                                        @error('nip')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="Id">Jabatan</label>
                                        <select name="id_jabatan" id="Id" class="form-control" required>

                                            @foreach ($jabatan as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == $user->id_jabatan ? 'selected' : '' }}>
                                                    {{ $item->nama_jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select name="role" id="role" class="form-control" required>
                                            <option value="">Pilih Role</option>
                                            <option value="1" @selected($user->role == 1)>Siswa</option>
                                            <option value="2" @selected($user->role == 2)>Wali Kelas</option>
                                            <option value="3" @selected($user->role == 3)>Kurikulum</option>
                                            <option value="4" @selected($user->role == 4)>Kepala Sekolah</option>
                                            <option value="5" @selected($user->role == 5)>Kepala Yayasan</option>
                                        </select>
                                    </div>

                                    <div class="form-group d-none" id="walas-group">
                                        <label for="wali_kelas">Pilih Wali Kelas</label>
                                        <select name="id_parent" id="wali_kelas" class="form-control">
                                            <option value="">Pilih Wali Kelas</option>
                                            @foreach ($walas as $walasItem)
                                                <option value="{{ $walasItem->id }}"
                                                    {{ isset($parentId) && $walasItem->id == $parentId ? 'selected' : '' }}>
                                                    {{ $walasItem->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>




                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-danger">Simpan Perubahan</button>
                                <a href="{{ route('master_pengguna.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            function toggleWalasGroup(role) {
                if (role == '1') {
                    $('#walas-group').removeClass('d-none');
                } else {
                    $('#walas-group').addClass('d-none');
                    $('#wali_kelas').val('');
                }
            }

            // Panggil saat halaman pertama kali load
            toggleWalasGroup($('#role').val());

            // Panggil saat role diganti
            $('#role').on('change', function() {
                toggleWalasGroup($(this).val());
            });

            // Set selected wali kelas (jika ada)
            const selectedWaliKelas = "{{ $user->id_parent }}";
            if (selectedWaliKelas) {
                $('#wali_kelas').val(selectedWaliKelas);
            }
        });
    </script>
@endsection
