<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class JenisBarangSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('jenis_barangs')->truncate();
        Schema::enableForeignKeyConstraints();
        DB::table('jenis_barangs')->insert([
            [
                'id' => 1,
                'nama_jenis' => 'Laptop',
                'singkatan' => 'LTP',
                'icon' => 'fas fa-laptop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama_jenis' => 'Handphone',
                'singkatan' => 'HP',
                'icon' => 'fas fa-mobile-alt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama_jenis' => 'PC / AIO',
                'singkatan' => 'PC',
                'icon' => 'fas fa-desktop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nama_jenis' => 'Printer',
                'singkatan' => 'PRT',
                'icon' => 'fas fa-print',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'nama_jenis' => 'Proyektor',
                'singkatan' => 'PYK',
                'icon' => 'fas fa-video',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'nama_jenis' => 'Others',
                'singkatan' => 'OTH',
                'icon' => 'fas fa-ellipsis-h',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'nama_jenis' => 'Air Conditioner',
                'singkatan' => 'AC',
                'icon' => 'fas fa-snowflake',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'nama_jenis' => 'Keyboard',
                'singkatan' => 'KYB',
                'icon' => 'fas fa-keyboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}