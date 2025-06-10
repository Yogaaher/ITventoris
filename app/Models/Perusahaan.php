<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory; // Tambahkan ini

    // --- PERUBAHAN DI SINI ---
    // Definisikan kolom yang boleh diisi
    protected $fillable = [
        'nama_perusahaan',
        'singkatan',
    ];
}