<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];
}

