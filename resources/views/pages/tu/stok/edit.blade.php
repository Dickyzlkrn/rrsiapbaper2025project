@extends('layouts.app')

@section('title', 'Edit Stok Barang')

@section('content')
<div class="form-container">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h3>Edit Stok Barang</h3>
        <a href="{{ route('tu.stok.index') }}" class="btn-back">
            <i class='bx bx-arrow-back'></i>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Notifikasi Error --}}
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Edit --}}
    <form action="{{ route('tu.stok.update', $stok->id) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" class="form-control" value="{{ $stok->nama_barang }}" readonly>
        </div>

        <div class="form-group mb-3">
            <label for="stok_awal">Stok Awal</label>
            <input type="number" name="stok_awal" class="form-control" value="{{ old('stok_awal', $stok->stok_awal) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="jumlah_masuk">Jumlah Masuk Sebelumnya</label>
            <input type="number" name="jumlah_masuk" class="form-control" value="{{ old('jumlah_masuk', $stok->jumlah_masuk) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="tambah_jumlah_masuk">Tambah Jumlah Masuk (Baru)</label>
            <input type="number" name="tambah_jumlah_masuk" class="form-control" min="0" value="0">
        </div>

        <div class="form-group mb-3">
            <label for="stok_akhir">Stok Akhir Saat Ini</label>
            <input type="number" class="form-control" value="{{ $stok->stok_awal + $stok->jumlah_masuk - $terpakai }}" readonly>
        </div>

        <button type="submit" class="btn btn-success">
            <i class='bx bx-refresh'></i> Update Stok
        </button>
    </form>
</div>
@endsection
