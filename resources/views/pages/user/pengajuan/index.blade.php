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

    {{-- Form Filter Tanggal --}}
    <form method="GET" action="{{ route('user.pengajuan') }}" class="filter-form mb-3" style="max-width: 220px;">
        <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal', date('Y-m-d')) }}"
            onchange="this.form.submit()" title="Filter berdasarkan tanggal">
    </form>

    {{-- Notifikasi --}}
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
            <tbody id="pengajuan-tbody">
                @include('pages.user.pengajuan._tbody')
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function fetchPengajuan() {
        const tanggal = document.querySelector('input[name="tanggal"]').value;

        fetch(`{{ route('user.pengajuan.refresh') }}?tanggal=${tanggal}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.tbody) {
                document.getElementById('pengajuan-tbody').innerHTML = data.tbody;
            }
        })
        .catch(err => {
            console.error("Gagal memuat data:", err);
        });
    }

    // Auto refresh setiap 5 detik
    setInterval(fetchPengajuan, 1000);
</script>
@endsection
