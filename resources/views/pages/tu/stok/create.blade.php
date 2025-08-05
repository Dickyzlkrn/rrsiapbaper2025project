@extends('layouts.app')

@section('title', 'Tambah Barang ke Stok')

@section('content')
<div class="form-container">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h3>Tambah Barang ke Stok</h3>
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

    {{-- Form Tambah --}}
    <form action="{{ route('tu.stok.store') }}" method="POST" class="mt-4">
        @csrf

        <div class="form-group mb-3">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" required value="{{ old('nama_barang') }}">
        </div>

        <div class="form-group mb-3">
            <label for="stok_awal">Stok Awal</label>
            <input type="number" name="stok_awal" id="stok_awal" class="form-control" min="0" required value="{{ old('stok_awal') }}">
        </div>

        <div class="form-group mb-4">
            <label for="satuan">Satuan</label>
            <input type="text" name="satuan" id="satuan" class="form-control" placeholder="Contoh: pcs, liter, box" value="{{ old('satuan') }}">
        </div>

        <button type="submit" class="btn btn-primary">
            <i class='bx bx-save'></i> Simpan
        </button>
    </form>
</div>
@endsection
