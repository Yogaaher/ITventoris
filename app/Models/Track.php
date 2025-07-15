<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Barang;
use App\Traits\LogsActivity;


class Track extends Model
{
    use LogsActivity;
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
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'serial_number', 'serial_number');
    }
}
