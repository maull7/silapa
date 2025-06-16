<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Pengaju</th>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requests as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->desc }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $item->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
