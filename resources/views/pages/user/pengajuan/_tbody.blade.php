@forelse($pengajuans as $index => $pengajuan)
    @php $rowCount = $pengajuan->items->count(); @endphp
    @foreach ($pengajuan->items as $i => $item)
        <tr>
            @if ($i == 0)
                <td rowspan="{{ $rowCount }}">{{ $index + 1 }}</td>
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
                <td rowspan="{{ $rowCount }}">
                    <div class="action-buttons">
                        @if ($pengajuan->status === 'disetujui')
                            <a href="{{ route('user.pengajuan.pdf', $pengajuan->id) }}" class="btn-action btn-pdf" target="_blank" title="Lihat PDF">
                                <i class='bx bxs-file-pdf'></i>
                            </a>
                        @endif
                    </div>
                </td>
            @endif
        </tr>
    @endforeach
@empty
    <tr>
        <td colspan="8" class="text-center">Belum ada permintaan masuk.</td>
    </tr>
@endforelse
