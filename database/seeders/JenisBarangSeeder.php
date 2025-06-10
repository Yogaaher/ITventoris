<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class JenisBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu
        // DB::table('jenis_barangs')->truncate();

        DB::table('jenis_barangs')->insert([
            [
                'id' => 1, // Kita set ID manual agar konsisten
                'nama_jenis' => 'Laptop',
                'singkatan' => 'LTP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama_jenis' => 'Handphone',
                'singkatan' => 'HP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama_jenis' => 'PC / AIO',
                'singkatan' => 'PC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nama_jenis' => 'Printer',
                'singkatan' => 'PRT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'nama_jenis' => 'Proyektor',
                'singkatan' => 'PYK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'nama_jenis' => 'Others',
                'singkatan' => 'OTH',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}