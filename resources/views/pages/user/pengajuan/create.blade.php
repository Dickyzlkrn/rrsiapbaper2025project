@extends('layouts.app')

@section('title', 'Tambah Pengajuan Barang')

@section('content')
    <div class="form-container">
        <div class="page-header">
            <h3>Form Pengajuan Barang</h3>
            <a href="{{ route('user.pengajuan') }}" class="btn-back">
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

        <form action="{{ route('user.pengajuan.store') }}" method="POST" class="form-input">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="nama_pengaju">Nama Pengaju <span class="text-danger">*</span></label>
                    <input type="text" id="nama_pengaju" name="nama_pengaju" class="form-control"
                        value="{{ old('nama_pengaju') }}" required>
                </div>
                <div class="form-group">
                    <label for="ruangan">Ruangan <span class="text-danger">*</span></label>
                    <input type="text" id="ruangan" name="ruangan" class="form-control" value="{{ old('ruangan') }}"
                        required>
                </div>

                {{-- Kolom keterangan --}}
                <div class="form-group" style="grid-column: span 2;">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" id="keterangan" name="keterangan" class="form-control"
                        value="{{ old('keterangan') }}">
                </div>
            </div>

            <hr>
            <h5>Detail Barang</h5>

            <div id="items-container">
                <div class="form-grid item-row mb-2"
                    style="display: grid; grid-template-columns: 3fr 1fr 1fr 1fr auto; gap: 10px; align-items: end;">
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang</label>
                        <input type="text" name="items[0][nama_barang]" class="form-control autocomplete-barang"
                            placeholder="Nama Barang" required>
                    </div>

                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="items[0][jumlah]" class="form-control jumlah-barang"
                            placeholder="Jumlah" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="satuan">Satuan</label>
                        <input type="text" class="form-control bg-light satuan-display" value="-" readonly>
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok Akhir</label>
                        <input type="text" class="form-control bg-light stok-display" value="-" readonly>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn-submit" style="background-color: #e74c3c; color: white;"
                            onclick="removeItemRow(this)">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </div>
            </div>


            <div class="mb-3">
                <button type="button" class="btn-submit" style="background-color: #6c757d; color: white;"
                    onclick="addItemRow()">
                    <i class='bx bx-plus'></i> <span>Tambah Barang</span>
                </button>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit" id="submit-btn" disabled>
                    <i class='bx bx-send'></i>
                    <span>Kirim Pengajuan</span>
                </button>
            </div>
        </form>
    </div>

    {{-- JS & jQuery UI --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <style>
        .satuan-display[value="Satuan: -"],
        .stok-display[value="-"] {
            color: red;
            font-weight: bold;
        }
    </style>

    <script>
        let itemIndex = 1;

        function addItemRow() {
            const container = document.getElementById('items-container');
            const html = `
            <div class="form-grid item-row mb-2" style="display: grid; grid-template-columns: 3fr 1fr 1fr 1fr auto; gap: 10px; align-items: center;">
                <!-- Nama Barang -->
                <div class="form-group">
                    <input type="text" name="items[${itemIndex}][nama_barang]" class="form-control autocomplete-barang" placeholder="Nama Barang" required>
                </div>

                <!-- Jumlah -->
                <div class="form-group">
                    <input type="number" name="items[${itemIndex}][jumlah]" class="form-control jumlah-barang" placeholder="Jumlah" min="1" required>
                </div>

                <!-- Satuan -->
                <div class="form-group">
                    <input type="text" class="form-control bg-light satuan-display" value="-" readonly>
                </div>

                <!-- Stok Akhir -->
                <div class="form-group">
                    <input type="text" class="form-control bg-light stok-display" value="-" readonly>
                </div>

                <!-- Tombol Hapus -->
                <div class="form-group" style="display: flex; align-items: flex-end;">
                    <button type="button" class="btn-submit" style="background-color: #e74c3c; color: white;" onclick="removeItemRow(this)">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', html);
            itemIndex++;
            enableAutocomplete();
        }

        function removeItemRow(button) {
            const row = button.closest('.item-row');
            if (row) row.remove();
            toggleSubmitButton();
        }

        function enableAutocomplete() {
            const inputs = document.querySelectorAll('.autocomplete-barang');

            inputs.forEach((input) => {
                if (!input.dataset.bound) {
                    $(input).autocomplete({
                        source: function(request, response) {
                            $.ajax({
                                url: '{{ route('stok.autocomplete') }}',
                                data: {
                                    term: request.term
                                },
                                success: function(data) {
                                    response(data);

                                    const row = input.closest('.item-row');
                                    const satuanDisplay = row.querySelector(
                                        '.satuan-display');
                                    const stokDisplay = row.querySelector('.stok-display');

                                    if (data.length === 0) {
                                        satuanDisplay.value = '-';
                                        stokDisplay.value = '-';
                                        input.dataset.valid = "false";
                                    } else {
                                        satuanDisplay.value = '-';
                                        stokDisplay.value = '-';
                                        input.dataset.valid = "true";
                                    }

                                    toggleSubmitButton();
                                }
                            });
                        },
                        minLength: 1,
                        select: function(event, ui) {
                            const row = input.closest('.item-row');
                            const satuanDisplay = row.querySelector('.satuan-display');
                            const stokDisplay = row.querySelector('.stok-display');
                            const jumlahInput = row.querySelector('.jumlah-barang');

                            satuanDisplay.value = ui.item.satuan ?? '-';

                            if (ui.item.stok_akhir !== undefined) {
                                stokDisplay.value = ui.item.stok_akhir;
                                jumlahInput.setAttribute('max', ui.item.stok_akhir);
                            } else {
                                stokDisplay.value = '-';
                                jumlahInput.removeAttribute('max');
                            }

                            input.dataset.valid = "true";
                            toggleSubmitButton();
                        }
                    });

                    input.addEventListener('input', function() {
                        this.dataset.valid = "false";
                        const row = this.closest('.item-row');
                        const satuanDisplay = row.querySelector('.satuan-display');
                        const stokDisplay = row.querySelector('.stok-display');
                        satuanDisplay.value = '-';
                        stokDisplay.value = '-';
                        toggleSubmitButton();
                    });

                    input.dataset.bound = true;
                }
            });
        }

        function toggleSubmitButton() {
            const allInputs = document.querySelectorAll('.autocomplete-barang');
            const btnSubmit = document.getElementById('submit-btn');

            let allValid = true;
            allInputs.forEach(input => {
                if (input.dataset.valid !== "true") {
                    allValid = false;
                }
            });

            btnSubmit.disabled = !allValid;
        }

        document.addEventListener('DOMContentLoaded', enableAutocomplete);
    </script>

@endsection
