<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;

class Surat extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'surats';

    protected $fillable = [
        'no_surat',
        'barang_id',
        'nama_penerima',
        'nik_penerima',
        'jabatan_penerima',
        'nama_pemberi',
        'nik_pemberi',
        'jabatan_pemberi',
        'penanggung_jawab',
        'keterangan',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}