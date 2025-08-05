<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Stok Awal</th>
            <th>Jumlah Masuk</th>
            <th>Terpakai</th>
            <th>Stok Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stokBarangs as $i => $barang)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $barang->nama_barang }}</td>
            <td>{{ $barang->stok_awal }}</td>
            <td>{{ $barang->jumlah_masuk }}</td>
            <td>{{ $barang->terpakai }}</td>
            <td>{{ $barang->stok_akhir_dinamis }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
