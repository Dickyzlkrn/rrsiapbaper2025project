<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    protected $table = 'stok_barangs';

    protected $fillable = [
        'nama_barang', 'satuan','stok_awal', 'jumlah_masuk', 'stok_akhir'
    ];
}

