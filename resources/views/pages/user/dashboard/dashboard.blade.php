@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-grid">

    {{-- Kartu Selamat Datang --}}
    <div class="welcome-card">
        <div class="welcome-text">
            <h3>Selamat Datang di SIAP BAPER!</h3>
            <p>Anda berada di halaman utama sistem Pengajuan Barang dan ATK RRI Ranai.</p>
        </div>
        <div class="welcome-icon">
            <i class='bx bxs-component'></i>
        </div>
    </div>

    {{-- Statistik Hari Ini --}}
    <div class="stats-card">
        <div class="stats-header">
            <p>{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
        </div>
        <div class="stats-body">
            <div class="stats-icon">
                <i class='bx bxs-user-check'></i>
            </div>
            <div class="stats-info">
                <p>Total Pengajuan Hari Ini</p>
                <h3>{{ $totalPengajuanHariIni }} Pengajuan</h3>
            </div>
        </div>
    </div>

</div>

{{-- Statistik Status Pengajuan --}}
<div class="quick-stats-grid mt-4">

    <div class="stat-card">
        <div class="stat-card-icon icon-blue">
            <i class='bx bx-send'></i>
        </div>
        <div class="stat-card-info">
            <p>Total Diajukan</p>
            <h3>{{ $totalDiajukan }}</h3>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon icon-green">
            <i class='bx bx-check-circle'></i>
        </div>
        <div class="stat-card-info">
            <p>Total Disetujui</p>
            <h3>{{ $totalDisetujui }}</h3>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon icon-yellow">
            <i class='bx bx-edit-alt'></i>
        </div>
        <div class="stat-card-info">
            <p>Total Direvisi</p>
            <h3>{{ $totalDirevisi }}</h3>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon icon-red">
            <i class='bx bx-x-circle'></i>
        </div>
        <div class="stat-card-info">
            <p>Total Ditolak</p>
            <h3>{{ $totalDitolak }}</h3>
        </div>
    </div>

</div>
@endsection
