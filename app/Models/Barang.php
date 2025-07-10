<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'perusahaan_id',
        'jenis_barang_id',
        'no_asset',
        'merek',
        'kuantitas',
        'tgl_pengadaan',
        'serial_number',
        'lokasi',
        'status',
    ];
    protected static function booted()
    {
        static::created(function (Barang $barang) {
            $barang->tracks()->create([
                'username' => '-',
                'status' => 'tersedia',
                'keterangan' => '-',
                'tanggal_awal' => now(),
                'tanggal_ahir' => null,
            ]);
        });
    }

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    public function jenisBarang(): BelongsTo
    {
        return $this->belongsTo(JenisBarang::class, 'jenis_barang_id');
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(\App\Models\Track::class, 'serial_number', 'serial_number')->orderBy('tanggal_awal', 'asc');
    }

    public function latestTrack(): HasOne
    {
        return $this->hasOne(Track::class, 'serial_number', 'serial_number')->latestOfMany();
    }

    public function getStatusTerkiniAttribute(): ?string
    {
        return $this->status;
    }
}
