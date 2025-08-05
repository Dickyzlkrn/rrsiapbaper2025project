@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna')

@section('content')
    <div class="form-container">
        <div class="page-header mb-3">
            <h3>Tambah Akun Pengguna</h3>
            <a href="{{ route('tu.akun.index') }}" class="btn-back">
                <i class='bx bx-arrow-back'></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Oops!</strong> Ada kesalahan saat input:<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tu.akun.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="email">NIP / Username</label>
                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password">Kata Sandi</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="role_id">Role</label>
                <select name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="signature">Tanda Tangan (PNG)</label>
                <input type="file" name="signature" accept="image/png"
                       class="form-control @error('signature') is-invalid @enderror">
                @error('signature')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-add-new d-flex align-items-center gap-2">
                <i class='bx bxs-save'></i> Simpan
            </button>
        </form>
    </div>
@endsection
