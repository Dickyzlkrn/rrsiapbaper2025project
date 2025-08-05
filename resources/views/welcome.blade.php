{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-grid">
        
        {{-- Bagian Kiri: Kartu Ucapan Selamat Datang --}}
        <div class="welcome-card">
            <div class="welcome-text">
                <h3>Selamat Datang, Admin!</h3>
                <p>Anda berada di halaman utama sistem Pengajuan Barang dan ATK RRI Bandar Lampung. Silakan gunakan menu di samping untuk memulai.</p>
            </div>
            <div class="welcome-icon">
                <i class='bx bxs-component'></i>
            </div>
        </div>

        {{-- Bagian Kanan: Kartu Statistik Hari Ini --}}
        <div class="stats-card">
            <div class="stats-header">
                {{-- Menggunakan Carbon untuk tanggal format Indonesia --}}
                <p>{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
            </div>
            <div class="stats-body">
                <div class="stats-icon">
                    <i class='bx bxs-user-check'></i>
                </div>
                <div class="stats-info">
                    <p>Total Pengajuan Hari Ini</p>
                    {{-- Ganti $totalPengajuanHariIni dengan variabel dari Controller Anda --}}
                    <h3>{{ $totalPengajuanHariIni ?? '15' }} Orang</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Anda bisa menambahkan konten lain di bawah sini, misalnya tabel atau chart --}}
    <div class="next-section-placeholder">
        <h2>Konten Selanjutnya</h2>
        <p>Area ini bisa diisi dengan tabel data pengajuan terbaru, chart, atau informasi lainnya.</p>
    </div>

@endsection