@extends('layouts.app')

@section('title', 'Manajemen Akun Pengguna')

@section('content')
    <div class="data-container">
        <div class="page-header d-flex justify-content-between align-items-center">
            <h3>Manajemen Akun Pengguna</h3>
            <a href="{{ route('tu.akun.create') }}" class="btn-add-new d-flex align-items-center gap-2">
                <i class='bx bx-plus'></i>
                <span>Tambah Akun</span>
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-3">
                <i class='bx bxs-check-circle'></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="table-wrapper" style="max-height: 600px; overflow-y: auto;">
            <table class="data-table table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username/NIP</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Password</th>
                        <th>Tanda Tangan</th> {{-- Tambahan --}}
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->name ?? '-' }}</td>
                            <td>
                                @php
                                    $badgeClass = match ($user->status) {
                                        'approved' => 'status-badge status-disetujui',
                                        'submitted' => 'status-badge status-diajukan',
                                        'rejected' => 'status-badge status-ditolak',
                                        default => 'status-badge bg-secondary'
                                    };
                                @endphp
                                <span class="{{ $badgeClass }}">{{ ucfirst($user->status) }}</span>
                            </td>
                            <td>
                                @if(auth()->user()->role->name === 'tu')
                                    <span class="password-hash" style="display: none;">{{ $user->password }}</span>
                                    <span class="password-hidden">••••••••</span>
                                    <i class="bx bx-show toggle-password-icon"
                                        style="cursor: pointer; margin-left: 8px; font-size: 18px; color: #666;"
                                        title="Lihat Password"></i>
                                @else
                                    <span class="text-muted">••••••••</span>
                                @endif
                            </td>

                            {{-- Kolom tanda tangan --}}
                            <td>
                                @if ($user->signature)
                                    <img src="{{ asset('storage/' . $user->signature) }}" alt="Tanda Tangan" style="height: 50px;">

                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>{{ $user->created_at?->format('d-m-Y') ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2 align-items-center">
                                    <a href="{{ route('tu.akun.edit', $user->id) }}" class="btn-action btn-edit"
                                        title="Edit Akun">
                                        <i class='bx bxs-pencil'></i>
                                    </a>
                                    <form action="{{ route('tu.akun.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus Akun">
                                            <i class='bx bxs-trash'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">Belum ada akun terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-password-icon').forEach(function (icon) {
                icon.addEventListener('click', function () {
                    const cell = icon.closest('td');
                    const hidden = cell.querySelector('.password-hidden');
                    const hash = cell.querySelector('.password-hash');

                    if (hash.style.display === 'none') {
                        hash.style.display = 'inline';
                        hidden.style.display = 'none';
                        icon.classList.remove('bx-show');
                        icon.classList.add('bx-hide');
                        icon.title = 'Sembunyikan Password';
                    } else {
                        hash.style.display = 'none';
                        hidden.style.display = 'inline';
                        icon.classList.remove('bx-hide');
                        icon.classList.add('bx-show');
                        icon.title = 'Lihat Password';
                    }
                });
            });
        });
    </script>
@endpush