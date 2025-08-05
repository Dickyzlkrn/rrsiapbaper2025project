@extends('layouts.app')

@section('title', 'Edit Pengajuan Barang')

@section('content')
    <div class="form-container">
        {{-- Header Halaman --}}
        <div class="page-header">
            <h3>Edit Daftar Pengajuan</h3>
            <a href="{{ route('tu.daftar.index') }}" class="btn-back">
                <i class='bx bx-arrow-back'></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Notifikasi Error --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <i class='bx bxs-error-circle'></i>
                <ul class="m-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Edit --}}
        <form action="{{ route('tu.pengajuan.update', $pengajuan->id) }}" method="POST" class="form-input">
            @csrf
            @method('PUT')

            {{-- Layout Grid --}}
            <div class="form-grid">
                <div class="form-group">
                    <label for="nama_pengaju">Nama Pengaju <span class="text-danger">*</span></label>
                    <input type="text" id="nama_pengaju" name="nama_pengaju" class="form-control"
                        value="{{ old('nama_pengaju', $pengajuan->nama_pengaju) }}" readonly>
                </div>

                <div class="form-group">
                    <label for="ruangan">Ruangan <span class="text-danger">*</span></label>
                    <input type="text" id="ruangan" name="ruangan" class="form-control"
                        value="{{ old('ruangan', $pengajuan->ruangan) }}" readonly>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" id="keterangan" name="keterangan" class="form-control"
                        value="{{ old('keterangan', $pengajuan->keterangan) }}">
                </div>

                {{-- Status --}}
                <div class="form-group" style="grid-column: span 2;">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="diajukan" {{ old('status', $pengajuan->status) == 'diajukan' ? 'selected' : '' }}>
                            Diajukan</option>
                        <option value="disetujui" {{ old('status', $pengajuan->status) == 'disetujui' ? 'selected' : '' }}>
                            Disetujui</option>
                        <option value="ditolak" {{ old('status', $pengajuan->status) == 'ditolak' ? 'selected' : '' }}>Ditolak
                        </option>
                        
                    </select>
                </div>
            </div>

            {{-- Detail Barang --}}
            <hr>
            <h5>Detail Barang</h5>
            <div id="items-container">
                @foreach ($pengajuan->items as $index => $item)
                    @php
                        $stok = \App\Models\StokBarang::where('nama_barang', $item->nama_barang)->first();
                        $stokAkhir = $stok?->stok_akhir ?? 0;
                        $overLimit = $item->jumlah > $stokAkhir;
                    @endphp
                    <div class="form-grid item-row mb-2" style="display: flex; gap: 10px; align-items: center;">
                        <div class="form-group" style="flex: 1;">
                            <input type="text" name="items[{{ $index }}][nama_barang]" class="form-control"
                                placeholder="Nama Barang" value="{{ old("items.$index.nama_barang", $item->nama_barang) }}"
                                required>
                        </div>
                        <div class="form-group" style="flex: 0.5;">
                            <input type="number" name="items[{{ $index }}][jumlah]"
                                class="form-control {{ $overLimit ? 'is-invalid' : '' }}" placeholder="Jumlah" min="1"
                                value="{{ old("items.$index.jumlah", $item->jumlah) }}" required>
                            @if($overLimit)
                                <div class="invalid-feedback">Melebihi stok tersedia ({{ $stokAkhir }})</div>
                            @endif
                        </div>
                        <div class="form-group" style="flex: 0.5;">
                            <input type="number" class="form-control bg-light" value="Stok: {{ $stokAkhir }}" readonly>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn-submit" style="background-color: #e74c3c; color: white;"
                                onclick="removeItemRow(this)" title="Hapus Baris">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tombol Tambah Baris --}}
            <div class="mb-3">
                <button type="button" class="btn-submit"
                    style="background-color: #6c757d; color: white; display: flex; align-items: center; gap: 5px;"
                    onclick="addItemRow()">
                    <i class='bx bx-plus'></i> <span>Tambah Barang</span>
                </button>
            </div>

            {{-- Tombol Submit --}}
            <div class="form-actions mt-4">
                <button type="submit" class="btn-submit">
                    <i class='bx bx-edit-alt'></i>
                    <span>Update Pengajuan</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        let itemIndex = Number('{{ count($pengajuan->items ?? []) }}');

        function addItemRow() {
            const container = document.getElementById('items-container');

            const html = `
                <div class="form-grid item-row mb-2" style="display: flex; gap: 10px; align-items: center;">
                    <div class="form-group" style="flex: 1;">
                        <input type="text" name="items[${itemIndex}][nama_barang]" class="form-control" placeholder="Nama Barang" required>
                    </div>
                    <div class="form-group" style="flex: 0.5;">
                        <input type="number" name="items[${itemIndex}][jumlah]" class="form-control" placeholder="Jumlah" min="1" required>
                    </div>
                    <div class="form-group" style="flex: 0.5;">
                        <input type="text" class="form-control bg-light" placeholder="Stok: -" readonly>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn-submit" style="background-color: #e74c3c; color: white;" onclick="removeItemRow(this)" title="Hapus Baris">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
            itemIndex++;
        }

        function removeItemRow(button) {
            const row = button.closest('.item-row');
            if (row) {
                row.remove();
            }
        }
    </script>
@endsection