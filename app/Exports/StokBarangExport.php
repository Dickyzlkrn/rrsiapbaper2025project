<?php

namespace App\Exports;

use App\Models\StokBarang;
use App\Models\PengajuanItem;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StokBarangExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Ambil filter dari request
        $namaBarang   = $this->request->nama_barang;
        $tanggalAwal  = $this->request->filled('tanggal_awal') ? Carbon::parse($this->request->tanggal_awal)->startOfDay() : null;
        $tanggalAkhir = $this->request->filled('tanggal_akhir') ? Carbon::parse($this->request->tanggal_akhir)->endOfDay() : null;
        $bulan        = $this->request->bulan;
        $tahun        = $this->request->tahun;

        // Ambil semua data stok
        $stokData = StokBarang::query()
            ->when($namaBarang, fn($q) => $q->where('nama_barang', 'like', "%{$namaBarang}%"))
            ->when($bulan, fn($q) => $q->whereMonth('created_at', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('created_at', $tahun))
            ->get()
            ->map(function ($item) use ($tanggalAwal, $tanggalAkhir) {

                // =========== Hitung Terpakai ===========
                $terpakaiQuery = PengajuanItem::where('nama_barang', $item->nama_barang)
                    ->whereHas('pengajuan', function ($q) {
                        $q->where('status', 'disetujui');
                    });

                if ($tanggalAwal && $tanggalAkhir) {
                    $terpakaiQuery->whereHas('pengajuan', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                        $q->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
                    });
                }

                $terpakai = $terpakaiQuery->sum('jumlah');

                // =========== Hitung Jumlah Masuk ===========
                $jumlahMasuk = StokBarang::where('nama_barang', $item->nama_barang)
                    ->when($tanggalAwal && $tanggalAkhir, function ($q) use ($tanggalAwal, $tanggalAkhir) {
                        $q->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
                    })
                    ->sum('jumlah_masuk');

                // =========== Hitung Stok Awal Dinamis ===========
                $stokMasukSebelumnya = StokBarang::where('nama_barang', $item->nama_barang)
                    ->where('created_at', '<', $tanggalAwal ?? now())
                    ->sum('jumlah_masuk');

                $stokKeluarSebelumnya = PengajuanItem::where('nama_barang', $item->nama_barang)
                    ->whereHas('pengajuan', function ($q) {
                        $q->where('status', 'disetujui');
                    })
                    ->when($tanggalAwal, function ($q) use ($tanggalAwal) {
                        $q->whereHas('pengajuan', function ($sub) use ($tanggalAwal) {
                            $sub->where('created_at', '<', $tanggalAwal);
                        });
                    })
                    ->sum('jumlah');

                $stokAwalDinamis = ($item->stok_awal + $stokMasukSebelumnya) - $stokKeluarSebelumnya;

                // =========== Hitung Stok Akhir ===========
                $stokAkhir = ($stokAwalDinamis + $jumlahMasuk) - $terpakai;

                // =========== Return Data ===========
                return [
                    'Nama Barang'   => $item->nama_barang,
                    'Satuan'        => $item->satuan ?? '-',
                    'Tanggal Masuk Barang Awal' => Carbon::parse($item->created_at)->format('d-m-Y H:i'),
                    'Stok Awal'     => $stokAwalDinamis,
                    'Jumlah Masuk'  => $jumlahMasuk,
                    'Terpakai'      => $terpakai,
                    'Stok Akhir'    => $stokAkhir,
                ];
            });

        return $stokData;
    }

    public function headings(): array
    {
        return [
            'Nama Barang',
            'Satuan',
            'Tanggal Masuk Barang Awal',
            'Stok Awal',
            'Jumlah Masuk',
            'Terpakai',
            'Stok Akhir',
        ];
    }
}
