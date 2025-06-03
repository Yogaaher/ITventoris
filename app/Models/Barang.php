<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'perusahaan',
        'jenis_barang',
        'no_asset',
        'merek',
        'tgl_pengadaan',
        'serial_number',
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

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class, 'serial_number', 'serial_number');
    }

    public function latestTrack(): HasOne
    {
        return $this->hasOne(Track::class, 'serial_number', 'serial_number')->latestOfMany();
    }

    // Accessor ini sekarang bisa langsung membaca dari kolom 'status' di tabel 'barang'
    // Namun, kita akan memastikan kolom ini selalu sinkron
    public function getStatusTerkiniAttribute(): ?string
    {
        return $this->status; // Langsung ambil dari kolom 'status'
    }

    // Jika ingin tetap disertakan saat toArray/toJson
    // protected $appends = ['status_terkini']; // Tidak perlu jika sudah ada kolom 'status'
}


