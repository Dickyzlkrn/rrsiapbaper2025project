<?php

namespace App\Exports;

use App\Models\Pengajuan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class RekapPengajuanTUExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $request;
    protected $flattened = [];
    protected $mergeInfo = [];

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Pengajuan::with('items');

        // Filter tanggal
        if ($this->request->filled('tanggal_awal') && $this->request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [
                $this->request->tanggal_awal,
                \Carbon\Carbon::parse($this->request->tanggal_akhir)->endOfDay()
            ]);
        } elseif ($this->request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $this->request->tanggal_awal);
        } elseif ($this->request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $this->request->tanggal_akhir);
        }

        // Filter tahun
        if ($this->request->filled('tahun')) {
            $query->whereYear('created_at', $this->request->tahun);
        }

        // Filter status
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        $rowIndex = 2;
        $no = 1;

        foreach ($pengajuans as $pengajuan) {
            $itemCount = $pengajuan->items->count();

            foreach ($pengajuan->items as $i => $item) {
                $rows[] = [
                    'no' => $i === 0 ? $no : '',
                    'tanggal' => $i === 0 ? $pengajuan->created_at->format('d-m-Y') : '',
                    'nama_pengaju' => $i === 0 ? $pengajuan->nama_pengaju : '',
                    'ruangan' => $i === 0 ? $pengajuan->ruangan : '',
                    'nama_barang' => $item->nama_barang,
                    'jumlah' => $item->jumlah,
                    'keterangan' => $i === 0 ? ($pengajuan->keterangan ?? '-') : '',
                    'status' => $i === 0 ? strtoupper($pengajuan->status) : '',
                    // Ambil langsung dari kolom pengambilan di tabel, jika null default 'Belum Diambil'
                    
                ];
            }

            $this->mergeInfo[] = [
                'start' => $rowIndex,
                'end' => $rowIndex + $itemCount - 1,
            ];

            $rowIndex += $itemCount;
            $no++;
        }

        $this->flattened = collect($rows);
        return $this->flattened;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Nama Pengaju',
            'Ruangan',
            'Barang',
            'Jumlah',
            'Keterangan',
            'Status',
        
        ];
    }

    public function map($row): array
    {
        return [
            $row['no'],
            $row['tanggal'],
            $row['nama_pengaju'],
            $row['ruangan'],
            $row['nama_barang'],
            $row['jumlah'],
            $row['keterangan'],
            $row['status'],
            
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach ($this->mergeInfo as $merge) {
                    if ($merge['start'] != $merge['end']) {
                        // Tambahkan kolom 'I' untuk Pengambilan
                        foreach (['A', 'B', 'C', 'D', 'G', 'H', 'I'] as $col) {
                            $event->sheet->mergeCells("{$col}{$merge['start']}:{$col}{$merge['end']}");
                            $event->sheet->getStyle("{$col}{$merge['start']}:{$col}{$merge['end']}")
                                ->getAlignment()
                                ->setVertical('center')
                                ->setHorizontal('center');
                        }
                    }
                }
            },
        ];
    }
}
