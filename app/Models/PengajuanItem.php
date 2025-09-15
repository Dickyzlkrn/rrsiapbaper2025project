<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'nama_barang',
        'jumlah',
        'keterangan', // ✅ tambahkan field keterangan
        'keterangan_kecil',
    ];

    // Relasi: item ini milik satu pengajuan
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
    public function stokBarang()
    {
        return $this->belongsTo(StokBarang::class, 'nama_barang', 'nama_barang');
    }
}
