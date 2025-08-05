@extends('layouts.app')

@section('title', 'Edit Akun')

@section('content')
<div class="data-container">
    <h3>Edit Akun</h3>

    <form action="{{ route('tu.akun.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div class="mb-3">
            <label for="name">Nama</label>
            <input type="text" name="name" id="name" class="form-control"
                value="{{ old('name', $user->name) }}" required>
            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        {{-- NIP (disimpan di kolom email) --}}
        <div class="mb-3">
            <label for="email">NIP / Username</label>
            <input type="text" name="email" id="email" class="form-control"
                value="{{ old('email', $user->email) }}" required>
            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        {{-- Role --}}
        <div class="mb-3">
            <label for="role_id">Role</label>
            <select name="role_id" id="role_id" class="form-control" required>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ (old('role_id', $user->role_id) == $role->id) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            @error('role_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="approved" {{ (old('status', $user->status) == 'approved') ? 'selected' : '' }}>Approved</option>
                <option value="submitted" {{ (old('status', $user->status) == 'submitted') ? 'selected' : '' }}>Submitted</option>
                <option value="rejected" {{ (old('status', $user->status) == 'rejected') ? 'selected' : '' }}>Rejected</option>
            </select>
            @error('status')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        {{-- Password Baru (opsional) --}}
        <div class="mb-3">
            <label for="password">Password Baru (Opsional)</label>
            <input type="password" name="password" id="password" class="form-control"
                placeholder="Kosongkan jika tidak ingin mengubah password">
            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        {{-- Upload Tanda Tangan --}}
        <div class="mb-3">
            <label for="signature">Tanda Tangan (PNG)</label>
            <input type="file" name="signature" id="signature" accept="image/png" class="form-control">
            @error('signature')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        {{-- Preview tanda tangan lama --}}
        @if ($user->signature)
            <div class="mb-3">
                <label>Tanda Tangan Saat Ini:</label><br>
                <img src="{{ asset('storage/' . $user->signature) }}" alt="Tanda Tangan" height="100">
            </div>
        @endif

        <button type="submit" class="btn-add-new d-flex align-items-center gap-3">
            <i class='bx bx-save'></i> Simpan Perubahan
        </button>
    </form>
</div>
@endsection
