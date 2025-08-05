@extends('layouts.app')

@section('title', 'Daftar Stok Barang ATK')

@section('content')
    <div class="data-container">
        {{-- Header --}}
        <div class="page-header d-flex justify-content-between align-items-center">
            <h3>Daftar Stok Barang ATK</h3>
            <div class="d-flex gap-4">
                <a href="{{ route('tu.stok.create') }}" class="btn-add-new d-flex align-items-center gap-4">
                    <i class='bx bx-plus'></i>
                    <span>Tambah Barang</span>
                </a>
            </div>
        </div>

        {{-- Form Filter --}}
        <form method="GET" action="{{ route('tu.stok.index') }}" class="filter-form mt-3 mb-4">
            <div class="form-filter-group d-flex gap-3 align-items-end flex-wrap">
                <div>
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" value="{{ request('nama_barang') }}"
                        class="form-control" placeholder="Cari nama barang...">
                </div>
                <div>
                    <label for="tanggal_awal">Dari Tanggal</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ request('tanggal_awal') }}"
                        class="form-control">
                </div>
                <div>
                    <label for="tanggal_akhir">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                        class="form-control">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary btn-filter">
                        <i class='bx bx-filter'></i> Filter
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('tu.stok.export', request()->query()) }}"
                        class="btn btn-success d-flex align-items-center gap-1">
                        <i class='bx bx-file'></i> Export Excel
                    </a>
                    <a href="{{ route('tu.stok.index') }}" class="btn btn-secondary">
                        <i class='bx bx-reset'></i> Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Info Filter --}}
        @if (request()->filled('tanggal_awal') && request()->filled('tanggal_akhir'))
            <div class="alert alert-info">
                Menampilkan data <strong>pemakaian</strong> dari tanggal 
                <strong>{{ \Carbon\Carbon::parse(request('tanggal_awal'))->format('d-m-Y') }}</strong> 
                sampai 
                <strong>{{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d-m-Y') }}</strong>.
            </div>
        @else

        @endif

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class='bx bxs-check-circle'></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Tabel --}}
        <div class="table-wrapper" style="max-height: 600px; overflow-y: auto;">
            <table class="data-table table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Tanggal Masuk Barang Awal</th>
                        <th>Satuan</th>
                        <th>Stok Awal</th>
                        <th>Jumlah Masuk</th>
                        <th>Terpakai</th>
                        <th>Stok Akhir</th>
                        <th>Aksi</th> {{-- Kolom Aksi (Edit & Hapus) --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokBarangs as $barang)
                        <tr @if(($barang->stok_akhir_dinamis ?? 0) <= 0) style="background-color: #ffe5e5;" @endif>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->created_at->format('d-m-Y H:i') }}</td> {{-- Tanggal barang ditambahkan --}}
                            <td>{{ $barang->satuan ?? '-' }}</td>
                            <td>{{ $barang->stok_awal }}</td>
                            <td>{{ $barang->jumlah_masuk }}</td>
                            <td>{{ $barang->terpakai ?? 0 }}</td>
                            <td>{{ $barang->stok_akhir_dinamis ?? $barang->stok_awal + $barang->jumlah_masuk }}</td>
                            <td>
                                <div class="action-buttons d-flex gap-1">
                                    <a href="{{ route('tu.stok.edit', $barang->id) }}" class="btn-action btn-edit"
                                        title="Edit Stok">
                                        <i class='bx bxs-pencil'></i>
                                    </a>
                                    <form action="{{ route('tu.stok.destroy', $barang->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus">
                                            <i class='bx bxs-trash'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data stok barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
