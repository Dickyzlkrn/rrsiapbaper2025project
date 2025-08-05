@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::check() ? Auth::user() : null;
@endphp

@if ($user)
<aside class="sidebar">
    <div class="sidebar-header">
        <a href="/">
            <img src="{{ asset('assets/images/rri2.png') }}" alt="Logo RRI" class="sidebar-logo">
        </a>
    </div>

    <nav class="sidebar-nav">
        <ul>
            {{-- DASHBOARD --}}
            <li class="{{ Request::is('tu/dashboard') || Request::is('user/dashboard') ? 'active' : '' }}">
                <a href="{{ $user && $user->role_id == 1 ? route('tu.dashboard') : route('user.dashboard') }}"
                   onclick="if(window.location.pathname === '/') { event.preventDefault(); location.reload(); }">
                    <span class="icon"><i class='bx bxs-dashboard'></i></span>
                    <span class="link-text">Dashboard</span>
                </a>
            </li>

            {{-- PENGAJUAN --}}
            <li class="{{ Request::is('tu/pengajuan*') || Request::is('tu/daftar*') || Request::is('user/pengajuan*') ? 'active' : '' }}">
                <a href="{{ $user && $user->role_id == 1 ? route('tu.pengajuan') : route('user.pengajuan') }}">
                    <span class="icon"><i class='bx bxs-user'></i></span>
                    <span class="link-text">Pengajuan</span>
                </a>
            </li>

            {{-- PERSEDIAAN BARANG (KHUSUS TU) --}}
            @if($user && $user->role_id == 1)
                <li class="{{ Request::is('tu/stok*') ? 'active' : '' }}">
                    <a href="{{ route('tu.stok.index') }}">
                        <span class="icon"><i class='bx bxs-box'></i></span>
                        <span class="link-text">Persediaan Barang</span>
                    </a>
                </li>

                {{-- MANAJEMEN AKUN (KHUSUS TU) --}}
                <li class="{{ Request::is('tu/akun*') ? 'active' : '' }}">
                    <a href="{{ route('tu.akun.index') }}">
                        <span class="icon"><i class='bx bxs-user-account'></i></span>
                        <span class="link-text">Manajemen Akun</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</aside>
@endif
