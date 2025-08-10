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
                    <th>Pengambilan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Data akan diisi AJAX --}}
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-info d-flex justify-content-between align-items-center mt-3">
        <span></span>
        <nav role="navigation" aria-label="Pagination Navigation">
            <ul class="pagination pagination-sm mb-0"></ul>
        </nav>
    </div>
</div>

<script>
    // Simpan dan restore pilihan dropdown pengambilan dari localStorage
    function restorePengambilanValues() {
        document.querySelectorAll('.select-pengambilan').forEach(select => {
            const id = select.dataset.id;
            const savedValue = localStorage.getItem('pengambilan_' + id);
            if (savedValue) {
                select.value = savedValue;
            }
            select.addEventListener('change', function () {
                localStorage.setItem('pengambilan_' + id, this.value);
            });
        });
    }

    // Fungsi load data tabel via AJAX
    function loadTable(url) {
        let formData = new FormData(document.querySelector('.filter-form'));
        let params = new URLSearchParams(formData).toString();

        if (url.indexOf('?') === -1) {
            url += '?ajax=1&' + params;
        } else {
            url += '&ajax=1&' + params;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                document.querySelector('tbody').innerHTML = data.tbody;

                // Restore dropdown pilihan pengambilan
                restorePengambilanValues();

                // Update pagination
                const paginationContainer = document.querySelector('.pagination-info nav ul.pagination');
                if (!paginationContainer) return;

                let paginationHtml = '';

                // Previous page
                if (!data.pagination.prev_page_url) {
                    paginationHtml += '<li class="page-item disabled"><span class="page-link"><i class="bx bx-chevron-left"></i></span></li>';
                } else {
                    paginationHtml += `<li class="page-item"><a class="page-link" href="${data.pagination.prev_page_url}" rel="prev"><i class="bx bx-chevron-left"></i></a></li>`;
                }

                // Pages
                for (let page = 1; page <= data.pagination.last_page; page++) {
                    if (page === data.pagination.current_page) {
                        paginationHtml += `<li class="page-item active" aria-current="page"><span class="page-link">${page}</span></li>`;
                    } else {
                        let pageUrl = data.pagination.path + '?page=' + page;
                        paginationHtml += `<li class="page-item"><a class="page-link" href="${pageUrl}">${page}</a></li>`;
                    }
                }

                // Next page
                if (!data.pagination.next_page_url) {
                    paginationHtml += '<li class="page-item disabled"><span class="page-link"><i class="bx bx-chevron-right"></i></span></li>';
                } else {
                    paginationHtml += `<li class="page-item"><a class="page-link" href="${data.pagination.next_page_url}" rel="next"><i class="bx bx-chevron-right"></i></a></li>`;
                }

                paginationContainer.innerHTML = paginationHtml;

                // Update info halaman
                document.querySelector('.pagination-info span').textContent = `Halaman ${data.pagination.current_page} dari ${data.pagination.last_page} | Total Data: ${data.pagination.total}`;
            })
            .catch(err => console.error(err));
    }

    // Event filter form submit
    document.querySelector('.filter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        loadTable('{{ route("tu.daftar.index") }}');
    });

    // Event klik pagination
    document.querySelector('.pagination-info').addEventListener('click', function (e) {
        if (e.target.tagName === 'A') {
            e.preventDefault();
            let url = e.target.href;
            loadTable(url);
        }
    });

    // Load awal halaman
    document.addEventListener('DOMContentLoaded', function () {
        loadTable('{{ route("tu.daftar.index") }}');
    });

    // Auto refresh setiap 1 detik
    setInterval(() => {
        const activePage = document.querySelector('.pagination-info .page-item.active span')?.textContent || 1;
        let baseUrl = '{{ route("tu.daftar.index") }}';
        let url = new URL(baseUrl, window.location.origin);

        let formData = new FormData(document.querySelector('.filter-form'));
        formData.set('page', activePage);

        url.search = new URLSearchParams(formData).toString();

        loadTable(url.toString());
    }, 2000);
</script>
@endsection
