@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Sistem Laporan</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @if ($requests->isNotEmpty())
                    <a href="{{ route('laporan_approval.export', request()->query()) }}" class="btn btn-success mb-3">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                @endif




                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Data
                    </div>
                    <div class="card-body">
                        <!-- Form Filter -->
                        <form method="GET" action="{{ route('laporan.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="start_date">Dari Tanggal:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date">Sampai Tanggal:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="status">Status:</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">-- Semua Status --</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="approve" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                            Approved</option>
                                        <option value="rejecte" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                            Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <br>
                                    <button type="submit" class="btn btn-primary mt-2">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary mt-2">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Tabel Hasil -->
                        @if ($requests->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover datatable text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Pengaju</th>
                                            <th>Judul</th>
                                            <th>Deskripsi</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requests as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ $item->desc }}</td>
                                                <td>
                                                    <span
                                                        class="badge 
                                    {{ $item->status == 'approved' ? 'badge-success' : ($item->status == 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                                        {{ ucfirst($item->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                                                <td>{{ $item->keterangan }}</td>
                                                <td>
                                                    <a href="{{ route('detail', $item->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted">Silakan pilih filter untuk melihat data approval.</p>
                        @endif
                    </div>

                </div>


        </section>
    </div>



    <script type="text/javascript">
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true
            });
        });
    </script>


@endsection
