<?php

namespace App\Exports;

use App\Models\StokBarang;
use App\Models\PengajuanItem;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Support\Responsable;
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
        $query = StokBarang::query();

        // Filter nama barang
        if ($this->request->filled('nama_barang')) {
            $query->where('nama_barang', 'like', '%' . $this->request->nama_barang . '%');
        }

        // Filter berdasarkan tanggal
        if ($this->request->filled('tanggal')) {
            $query->whereDate('created_at', $this->request->tanggal);
        }

        // Filter bulan
        if ($this->request->filled('bulan')) {
            $query->whereMonth('created_at', $this->request->bulan);
        }

        // Filter tahun
        if ($this->request->filled('tahun')) {
            $query->whereYear('created_at', $this->request->tahun);
        }

        $tanggalAwal = $this->request->filled('tanggal_awal') ? Carbon::parse($this->request->tanggal_awal)->startOfDay() : null;
        $tanggalAkhir = $this->request->filled('tanggal_akhir') ? Carbon::parse($this->request->tanggal_akhir)->endOfDay() : null;

        $data = $query->get()->map(function ($item) use ($tanggalAwal, $tanggalAkhir) {
            // Query dasar
            $terpakaiQuery = PengajuanItem::where('nama_barang', $item->nama_barang)
                ->whereHas('pengajuan', function ($q) {
                    $q->where('status', 'disetujui');
                });

            // Filter tanggal pemakaian jika tersedia
            if ($tanggalAwal && $tanggalAkhir) {
                $terpakaiQuery->whereHas('pengajuan', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                    $q->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
                });
            }

            $terpakai = $terpakaiQuery->sum('jumlah');
            $stokAkhir = ($item->stok_awal + $item->jumlah_masuk) - $terpakai;

            return [
                'Nama Barang'   => $item->nama_barang,
                'Satuan'        => $item->satuan ?? '-',
                'Stok Awal'     => $item->stok_awal,
                'Jumlah Masuk'  => $item->jumlah_masuk,
                'Terpakai'      => $terpakai,
                'Stok Akhir'    => $stokAkhir,
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'Nama Barang',
            'Satuan',
            'Stok Awal',
            'Jumlah Masuk',
            'Terpakai',
            'Stok Akhir',
        ];
    }
}
