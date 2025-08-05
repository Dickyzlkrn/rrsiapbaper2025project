@extends('layouts.app')

@section('title', 'Permintaan Barang Masuk')

@section('content')
    <div class="data-container">
        {{-- Header Halaman --}}
        <div class="page-header d-flex justify-content-between align-items-center">
            <h3>Pengajuan</h3>
            <a href="{{ route('user.pengajuan.create') }}" class="btn-add-new">
                <i class='bx bx-plus'></i>
                <span>Tambah Pengajuan</span>
            </a>
        </div>

        {{-- Form Filter Tanggal (Ringkas) --}}
        <form method="GET" action="{{ route('user.pengajuan') }}" class="filter-form mb-3" style="max-width: 220px;">
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal', date('Y-m-d')) }}"
                onchange="this.form.submit()" title="Filter berdasarkan tanggal">
        </form>


        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class='bx bxs-check-circle'></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Tabel Data --}}
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengaju</th>
                        <th>Ruangan</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $index => $pengajuan)
                        @php
                            $rowCount = $pengajuan->items->count();
                        @endphp
                        @foreach ($pengajuan->items as $i => $item)
                            <tr>
                                @if ($i == 0)
                                    <td rowspan="{{ $rowCount }}">{{ $index + 1 }}</td>
                                    <td rowspan="{{ $rowCount }}">{{ $pengajuan->nama_pengaju }}</td>
                                    <td rowspan="{{ $rowCount }}">{{ $pengajuan->ruangan }}</td>
                                @endif

                                {{-- Nama Barang --}}
                                <td>{{ $item->nama_barang }}</td>

                                {{-- Jumlah --}}
                                <td>{{ $item->jumlah }}</td>

                                @if ($i == 0)
                                    <td rowspan="{{ $rowCount }}">{{ $pengajuan->keterangan ?? '-' }}</td>
                                    <td rowspan="{{ $rowCount }}">
                                        @if ($pengajuan->status === 'diajukan')
                                            <span class="status-badge status-diajukan">Diajukan</span>
                                        @elseif($pengajuan->status === 'disetujui')
                                            <span class="status-badge status-disetujui">Disetujui</span>
                                        
                                        @elseif($pengajuan->status === 'ditolak')
                                            <span class="status-badge status-ditolak">Ditolak</span>
                                        @endif
                                    <td rowspan="{{ $rowCount }}">
                                        <div class="action-buttons">
                                            @if ($pengajuan->status === 'disetujui')
                                                <a href="{{ route('user.pengajuan.pdf', $pengajuan->id) }}"
                                                    class="btn-action btn-pdf" target="_blank" title="Lihat PDF">
                                                    <i class='bx bxs-file-pdf'></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada permintaan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
