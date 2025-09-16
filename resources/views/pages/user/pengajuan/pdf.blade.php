<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Pengajuan Barang #{{ $pengajuan->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .logo-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo-header img {
            height: 60px;
        }

        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .header-table td {
            padding: 3px 0;
            font-size: 12px;
            vertical-align: top;
        }

        h3 {
            text-align: center;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        table.data-table th {
            background-color: #eee;
        }

        .center {
            text-align: center;
        }

        .signature {
            width: 100%;
            margin-top: 60px;
        }

        .signature td {
            text-align: center;
            padding-top: 50px;
        }

        .signature img {
            height: 60px;
        }
    </style>
</head>

<body>

    {{-- Logo dan Header --}}
    <div class="logo-header">
        <img src="{{ public_path('storage/nb.png') }}" style="height:60px;">
    </div>

    <table class="header-table">
        <tr>
            <td width="50%">
                Lembaga Penyiaran Publik<br>
                Radio Republik Indonesia<br>
                Ranai
            </td>
            <td width="50%">
                Tanggal : {{ \Carbon\Carbon::now()->format('d-m-Y') }}<br>
                Pemohon : {{ $pengajuan->nama_pengaju }}
            </td>
        </tr>
    </table>

    <h3>Detail Pengajuan Barang</h3>

    {{-- Info Umum --}}
    <table class="header-info" style="margin-bottom: 20px;">
        <tr>
            <td><strong>ID Pengajuan</strong></td>
            <td>: {{ $pengajuan->id }}</td>
        </tr>
        <tr>
            <td><strong>Ruangan</strong></td>
            <td>: {{ $pengajuan->ruangan }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Pengajuan</strong></td>
            <td>: {{ $pengajuan->created_at->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>: {{ strtoupper($pengajuan->status) }}</td>
        </tr>
    </table>

    {{-- Tabel Barang --}}
    <table class="data-table">
        <thead>
            <tr>
                <th class="center" width="5%">No</th>
                <th>Nama Barang</th>
                <th class="center" width="10%">Jumlah</th>
                <th class="center" width="15%">Satuan</th> {{-- kolom baru --}}
                <th class="center" width="30%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengajuan->items as $i => $item)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="center">{{ $item->jumlah }}</td>
                    <td class="center">{{ $item->stokBarang->satuan ?? '-' }}</td> {{-- ambil dari stok --}}
                    <td>{{ $item->keterangan_kecil ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <table class="signature" style="margin-top: 60px; width: 100%;">
        <tr>
            <td style="width: 50%; text-align: center;">
                Yang Mengeluarkan,<br>
                <strong>Operator Persediaan</strong><br><br>
                @if($admin && $admin->signature)
                    <img src="{{ public_path('storage/' . $admin->signature) }}" alt="Tanda Tangan TU"
                        style="height: 60px; margin: 10px 0;">
                @else
                    <div style="height: 60px;"></div>
                @endif
                <br><u>{{ $admin->name ?? '-' }}</u><br>
                <small>Tata Usaha</small>
            </td>
            <td style="width: 50%; text-align: center;">
                Pemohon,<br><br><br>
                @if($pengajuan->user && $pengajuan->user->signature)
                    <img src="{{ public_path('storage/' . $pengajuan->user->signature) }}" alt="Tanda Tangan Pemohon"
                        style="height: 60px; margin: 10px 0;">
                @else
                    <div style="height: 60px;"></div>
                @endif
                <br><u>{{ $pengajuan->user->name ?? $pengajuan->nama_pengaju }}</u>
            </td>
        </tr>
    </table>
</body>

</html>
