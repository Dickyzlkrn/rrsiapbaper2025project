<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StokBarang extends Model
{
    protected $table = 'stok_barangs';

    protected $fillable = [
        'nama_barang',
        'satuan',
        'stok_awal',
        'jumlah_masuk',
        'stok_akhir',
        'bulan',
        'tahun',
    ];

    // Scope filter stok berdasarkan bulan dan tahun
    public function scopeFilterByMonth($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    // Generate stok awal bulan ini berdasarkan stok akhir bulan lalu
    public static function setStokAwalBulanIni()
    {
        $bulanSekarang = now()->month;
        $tahunSekarang = now()->year;

        $bulanLalu = $bulanSekarang == 1 ? 12 : $bulanSekarang - 1;
        $tahunLalu = $bulanSekarang == 1 ? $tahunSekarang - 1 : $tahunSekarang;

        $stokBulanLalu = self::where('bulan', $bulanLalu)
            ->where('tahun', $tahunLalu)
            ->get();

        foreach ($stokBulanLalu as $stok) {
            self::updateOrCreate(
                [
                    'nama_barang' => $stok->nama_barang,
                    'bulan' => $bulanSekarang,
                    'tahun' => $tahunSekarang,
                ],
                [
                    'satuan' => $stok->satuan,
                    'stok_awal' => $stok->stok_akhir,
                    'jumlah_masuk' => 0,
                    'stok_akhir' => $stok->stok_akhir,
                ]
            );
        }
    }
}
