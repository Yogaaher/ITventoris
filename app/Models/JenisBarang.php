<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini
use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    use HasFactory; // Tambahkan ini

    // --- PERUBAHAN DI SINI ---
    // Definisikan kolom yang boleh diisi
    protected $fillable = [
        'nama_jenis',
        'singkatan',
        'icon',
    ];
}