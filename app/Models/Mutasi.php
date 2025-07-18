<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'no_asset_lama',
        'perusahaan_lama_id',
        'pengguna_lama',
        'no_asset_baru',
        'perusahaan_baru_id',
        'pengguna_baru',
        'tanggal_mutasi',
    ];

    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function perusahaanLama()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_lama_id');
    }

    public function perusahaanBaru()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_baru_id');
    }
}