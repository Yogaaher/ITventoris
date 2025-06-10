<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu untuk menghindari duplikasi jika seeder dijalankan ulang
        // DB::table('perusahaans')->truncate();

        DB::table('perusahaans')->insert([
            [
                'id' => 1, // Kita set ID manual agar konsisten dengan seeder barang
                'nama_perusahaan' => 'Scuto Indonesia',
                'singkatan' => 'SCO',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama_perusahaan' => 'Scuto Bike Indonesia',
                'singkatan' => 'SCT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama_perusahaan' => 'Scuto Paint Indonesia',
                'singkatan' => 'SCP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nama_perusahaan' => 'Migen',
                'singkatan' => 'MIG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}