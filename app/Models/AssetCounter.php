<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCounter extends Model
{
    use HasFactory;

    protected $table = 'asset_counters';

    protected $fillable = [
        'perusahaan_id',
        'nomor_terakhir',
    ];
}
