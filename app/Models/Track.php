<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;

class Track extends Model
{
    protected $table = 'track';

    protected $fillable = [
        'serial_number',
        'username',
        'status',
        'keterangan',
        'tanggal_awal',
        'tanggal_ahir',
    ];

    // Relasi ke tabel barang (1 track → 1 barang)
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'serial_number', 'serial_number');
    }
}
