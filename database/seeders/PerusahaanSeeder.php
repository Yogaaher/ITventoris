<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PerusahaanSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('perusahaans')->truncate();
        DB::table('perusahaans')->insert([
            ['id' => 1, 'nama_perusahaan' => 'PT SCUTO INDONESIA', 'singkatan' => 'SCO', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_perusahaan' => 'CV PERISAI DISTRIBUSI', 'singkatan' => 'PDI', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama_perusahaan' => 'CV PERISAI INDONESIA', 'singkatan' => 'PIN', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama_perusahaan' => 'PT PROTEKSI ABADI OTOMOTIF INDONESIA', 'singkatan' => 'PAO', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama_perusahaan' => 'PT SCUTO TOTAL SOLUSI', 'singkatan' => 'STS', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama_perusahaan' => 'PT SCUTO WARNA INDONESIA', 'singkatan' => 'SWI', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama_perusahaan' => 'PT WARNA EKA PALEMBANG', 'singkatan' => 'WEP', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nama_perusahaan' => 'PT WARNA EKA JAKARTA', 'singkatan' => 'WEJ', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'nama_perusahaan' => 'PT WARNA DWI JAKARTA', 'singkatan' => 'WDJ', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'nama_perusahaan' => 'PT WARNA PERKASA EKA', 'singkatan' => 'WPE', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'nama_perusahaan' => 'PT MIGEN KREASI BANGSA', 'singkatan' => 'MKB', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'nama_perusahaan' => 'PT WARNA PERKASA CEMERLANG', 'singkatan' => 'WPC', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'nama_perusahaan' => 'PT SCUTO DETAILING INDONESIA', 'singkatan' => 'SDI', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'nama_perusahaan' => 'PT SCT MOTOR INDONESIA', 'singkatan' => 'SCT', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'nama_perusahaan' => 'PT SCUTO TOTAL OTO', 'singkatan' => 'STO', 'created_at' => now(), 'updated_at' => now()],
        ]);
        Schema::enableForeignKeyConstraints();
    }
}