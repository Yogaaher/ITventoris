<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;

class Track extends Model
{
    protected $table = 'track';

    protected $fillable = [
        'no_asset',
        'username',
        'status',
        'keterangan',
        'tanggal_awal',
        'tanggal_ahir',
    ];

    // Relasi ke tabel barang (1 track → 1 barang)
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'no_asset', 'no_asset');
    }
}
