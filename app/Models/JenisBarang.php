<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class JenisBarang extends Model
{

    use HasFactory, LogsActivity;

    protected $fillable = [
        'nama_jenis',
        'singkatan',
        'icon',
    ];
}