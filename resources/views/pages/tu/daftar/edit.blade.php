@extends('layouts.app')

@section('title', 'Edit Pengajuan Barang')

@section('content')
    <div class="form-container">
        <div class="page-header">
            <h3>Edit Daftar Pengajuan</h3>
            <a href="{{ route('tu.daftar.index') }}" class="btn-back">
                <i class='bx bx-arrow-back'></i>
                <span>Kembali</span>
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class='bx bxs-error-circle'></i>
                <ul class="m-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tu.pengajuan.update', $pengajuan->id) }}" method="POST" class="form-input">
            @csrf
            @method('PUT')

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
                    <label for="keterangan">Keterangan Umum</label>
                    <input type="text" id="keterangan" name="keterangan" class="form-control"
                        value="{{ old('keterangan', $pengajuan->keterangan) }}">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="diajukan" {{ old('status', $pengajuan->status) == 'diajukan' ? 'selected' : '' }}>
                            Diajukan</option>
                        <option value="disetujui" {{ old('status', $pengajuan->status) == 'disetujui' ? 'selected' : '' }}>
                            Disetujui</option>
                        <option value="ditolak" {{ old('status', $pengajuan->status) == 'ditolak' ? 'selected' : '' }}>
                            Ditolak</option>
                    </select>
                </div>
            </div>

            <hr>
            <h5>Detail Barang</h5>
            <div id="items-container">
                @foreach ($pengajuan->items as $index => $item)
                    @php
                        $stok = \App\Models\StokBarang::where('nama_barang', $item->nama_barang)->first();
                        $stokAkhir = $stok?->stok_akhir ?? 0;
                        $overLimit = $item->jumlah > $stokAkhir;
                    @endphp
                    <div class="form-grid item-row mb-2" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        {{-- Nama Barang --}}
                        <div class="form-group" style="flex: 1;">
                            <label>Nama Barang</label>
                            <input type="text" name="items[{{ $index }}][nama_barang]" class="form-control"
                                value="{{ old("items.$index.nama_barang", $item->nama_barang) }}" required>
                        </div>

                        {{-- Jumlah --}}
                        <div class="form-group" style="flex: 0.5;">
                            <label>Jumlah</label>
                            <input type="number" name="items[{{ $index }}][jumlah]"
                                class="form-control item-jumlah {{ $overLimit ? 'is-invalid' : '' }}"
                                value="{{ old("items.$index.jumlah", $item->jumlah) }}" min="1"
                                data-nama="{{ $item->nama_barang }}" required>
                            <div class="text-danger error-msg" style="{{ $overLimit ? '' : 'display: none' }}">
                                @if ($overLimit)
                                    Melebihi stok tersedia ({{ $stokAkhir }})
                                @endif
                            </div>
                        </div>

                        {{-- Stok Akhir --}}
                        <div class="form-group" style="flex: 0.5;">
                            <label>Stok Akhir</label>
                            <input type="text" class="form-control bg-light" value="Stok: {{ $stokAkhir }}" readonly>
                        </div>

                        {{-- Keterangan Kecil per barang --}}
                        <div class="form-group" style="flex: 1;">
                            <label>Keterangan</label>
                            <input type="text" name="items[{{ $index }}][keterangan_kecil]" class="form-control"
                                value="{{ old("items.$index.keterangan_kecil", $item->keterangan_kecil) }}">
                        </div>

                        {{-- Hapus row --}}
                        <div class="form-group">
                            <button type="button" class="btn-submit" style="background-color: #e74c3c; color: white;"
                                onclick="removeItemRow(this)">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mb-3">
                <button type="button" class="btn-submit" style="background-color: #6c757d; color: white;"
                    onclick="addItemRow()">
                    <i class='bx bx-plus'></i> <span>Tambah Barang</span>
                </button>
            </div>

            <div class="form-actions mt-4">
                <button type="submit" id="btn-update" class="btn-submit">
                    <i class='bx bx-edit-alt'></i>
                    <span>Update Pengajuan</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        let itemIndex = Number('{{ count($pengajuan->items ?? []) }}');
        const stokList = @json($stokList);
        const updateButton = document.getElementById('btn-update');

        function addItemRow() {
            const container = document.getElementById('items-container');
            const html = `
                <div class="form-grid item-row mb-2" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <div class="form-group" style="flex: 1;">
                        <input type="text" name="items[${itemIndex}][nama_barang]" class="form-control" placeholder="Nama Barang" required>
                    </div>
                    <div class="form-group" style="flex: 0.5;">
                        <input type="number" name="items[${itemIndex}][jumlah]" class="form-control item-jumlah" placeholder="Jumlah" min="1" required>
                        <div class="text-danger error-msg" style="display: none;"></div>
                    </div>
                    <div class="form-group" style="flex: 0.5;">
                        <input type="text" class="form-control bg-light" placeholder="Stok: -" readonly>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <input type="text" name="items[${itemIndex}][keterangan_kecil]" class="form-control" placeholder="Keterangan Kecil">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn-submit" style="background-color: #e74c3c; color: white;" onclick="removeItemRow(this)">
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
                validateStok();
            }
        }

        function validateStok() {
            let valid = true;
            document.querySelectorAll('.item-row').forEach(row => {
                const jumlahInput = row.querySelector('.item-jumlah');
                const nama = jumlahInput.dataset.nama || row.querySelector('input[name*="[nama_barang]"]').value.trim();
                const jumlah = parseInt(jumlahInput.value) || 0;
                const stok = stokList[nama] ?? 0;
                const errorDiv = row.querySelector('.error-msg');

                if (jumlah > stok) {
                    valid = false;
                    errorDiv.style.display = 'block';
                    errorDiv.textContent = `Melebihi stok tersedia (${stok})`;
                    jumlahInput.classList.add('is-invalid');
                } else {
                    errorDiv.style.display = 'none';
                    errorDiv.textContent = '';
                    jumlahInput.classList.remove('is-invalid');
                }
            });

            updateButton.disabled = !valid;
        }

        document.addEventListener('DOMContentLoaded', () => {
            validateStok();
            document.getElementById('items-container').addEventListener('input', function(e) {
                if (e.target && e.target.classList.contains('item-jumlah')) {
                    validateStok();
                }
            });
        });
    </script>
@endsection
