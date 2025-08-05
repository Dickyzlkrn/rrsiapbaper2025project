{{-- resources/views/layouts/partials/navbar.blade.php --}}
@php
    use Illuminate\Support\Facades\Auth;
@endphp

<nav class="navbar">
    <div class="navbar-left">
        {{-- Tombol Perkecil Desktop --}}
        <div class="menu-toggle desktop">
            <i class='bx bx-menu'></i>
        </div>
        {{-- Tombol Mobile --}}
        <div class="menu-toggle mobile">
            <i class='bx bx-menu'></i>
        </div>

        {{-- Form Pencarian --}}
        <form action="#" class="search-form">
            <div class="search-input-container">
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Cari sesuatu...">
            </div>
        </form>
    </div>

    <div class="navbar-right">
        {{-- Dropdown Profil Pengguna --}}
        <div class="profile-dropdown">
            <div class="profile-trigger">
                <img src="https://placehold.co/40x40/3b82f6/ffffff?text={{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}" alt="Foto Profil" class="profile-img">
                <span class="profile-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                <i class='bx bx-chevron-down dropdown-arrow'></i>
            </div>
            <ul class="dropdown-menu">
                <li class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin ingin keluar?')">
                        @csrf
                        <button type="submit" class="dropdown-item logout" style="border: none; background: none; width: 100%; text-align: left;">
                            <i class='bx bxs-log-out'></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
