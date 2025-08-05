<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;
    protected $table = 'pengajuan';
    protected $fillable = [
        'user_id',
        'nama_pengaju',
        'ruangan',
        'keterangan',
        'status',
    ];

    // Relasi: satu pengajuan punya banyak item
    public function items()
    {
        return $this->hasMany(PengajuanItem::class);
    }

    // Relasi opsional ke user (jika pakai auth)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
