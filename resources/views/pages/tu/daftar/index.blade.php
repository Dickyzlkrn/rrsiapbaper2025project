@extends('layouts.app')

@section('title', 'Daftar Permintaan Barang')

@section('content')
    <div class="data-container">
        <div class="page-header d-flex justify-content-between align-items-center">
            <h3>Daftar Permintaan Barang</h3>
            <a href="{{ route('tu.rekap.excel', request()->query()) }}" class="btn-export">
                <i class='bx bxs-download'></i>
                <span>Rekap Excel</span>
            </a>
        </div>

        {{-- Form Filter --}}
        <form method="GET" action="{{ route('tu.daftar.index') }}" class="filter-form mt-3 mb-4">
            <div class="form-filter-group d-flex gap-3 align-items-end flex-wrap">
                <div>
                    <label for="tanggal_awal">Dari Tanggal</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ request('tanggal_awal') }}"
                        class="form-control">
                </div>
                <div>
                    <label for="tanggal_akhir">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                        class="form-control">
                </div>
                <div>
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">-- Semua --</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary btn-filter">
                        <i class='bx bx-filter'></i> Filter
                    </button>
                </div>
            </div>
        </form>

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class='bx bxs-check-circle'></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Tabel --}}
        <div class="table-wrapper">
            <table class="data-table table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengaju</th>
                        <th>Ruangan</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Pengambilan</th> {{-- Dropdown disini --}}
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $index => $pengajuan)
                        @php $rowCount = $pengajuan->items->count(); @endphp
                        @foreach ($pengajuan->items as $i => $item)
                            <tr>
                                @if ($i == 0)
                                    <td rowspan="{{ $rowCount }}">{{ $pengajuans->firstItem() + $index }}</td>
                                    <td rowspan="{{ $rowCount }}">{{ $pengajuan->nama_pengaju }}</td>
                                    <td rowspan="{{ $rowCount }}">{{ $pengajuan->ruangan }}</td>
                                @endif

                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->jumlah }}</td>

                                @if ($i == 0)
                                    <td rowspan="{{ $rowCount }}">{{ $pengajuan->keterangan ?? '-' }}</td>
                                    <td rowspan="{{ $rowCount }}">
                                        @if ($pengajuan->status === 'diajukan')
                                            <span class="status-badge status-diajukan">Diajukan</span>
                                        @elseif($pengajuan->status === 'disetujui')
                                            <span class="status-badge status-disetujui">Disetujui</span>
                                        @elseif($pengajuan->status === 'ditolak')
                                            <span class="status-badge status-ditolak">Ditolak</span>
                                        @endif
                                    </td>

                                    {{-- Dropdown pengambilan --}}
                                    <td rowspan="{{ $rowCount }}">
                                        <select class="select-pengambilan" data-id="{{ $pengajuan->id }}">
                                            <option value="belum">Belum Diambil</option>
                                            <option value="sudah">Sudah Diambil</option>
                                        </select>
                                    </td>

                                    {{-- Aksi --}}
                                    <td rowspan="{{ $rowCount }}">
                                        <div class="action-buttons">
                                            {{-- PDF --}}
                                            <a href="{{ route('user.pengajuan.pdf', $pengajuan->id) }}"
                                                class="btn-action btn-pdf" target="_blank" title="Lihat PDF">
                                                <i class='bx bxs-file-pdf'></i>
                                            </a>
                                            {{-- Edit --}}
                                            <a href="{{ route('tu.pengajuan.edit', $pengajuan->id) }}"
                                                class="btn-action btn-edit" title="Edit/Revisi">
                                                <i class='bx bxs-pencil'></i>
                                            </a>
                                            {{-- Hapus --}}
                                            <form action="{{ route('tu.pengajuan.delete', $pengajuan->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete"
                                                    onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                                    <i class='bx bxs-trash'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada permintaan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="pagination-info d-flex justify-content-between align-items-center mt-3">
            <span>
                Halaman {{ $pengajuans->currentPage() }} dari {{ $pengajuans->lastPage() }} |
                Total Data: {{ $pengajuans->total() }}
            </span>

            <nav role="navigation" aria-label="Pagination Navigation">
                <ul class="pagination pagination-sm mb-0">
                    {{-- Previous Page --}}
                    @if ($pengajuans->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link"><i class='bx bx-chevron-left'></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $pengajuans->previousPageUrl() }}" rel="prev">
                                <i class='bx bx-chevron-left'></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pages --}}
                    @foreach ($pengajuans->getUrlRange(max(1, $pengajuans->currentPage() - 2), min($pengajuans->lastPage(), $pengajuans->currentPage() + 2)) as $page => $url)
                        @if ($page == $pengajuans->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    {{-- Next Page --}}
                    @if ($pengajuans->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $pengajuans->nextPageUrl() }}" rel="next">
                                <i class='bx bx-chevron-right'></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link"><i class='bx bx-chevron-right'></i></span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>

    {{-- Script simpan pilihan dropdown di localStorage --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.select-pengambilan').forEach(function (select) {
                const id = select.dataset.id;
                const savedValue = localStorage.getItem('pengambilan_' + id);
                if (savedValue) {
                    select.value = savedValue;
                }
                select.addEventListener('change', function () {
                    localStorage.setItem('pengambilan_' + id, this.value);
                });
            });
        });
    </script>
@endsection
