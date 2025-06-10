<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perusahaanMapping = ['SCO' => 1, 'SCT' => 2, 'SCP' => 3, 'Migen' => 4];
        $jenisBarangMapping = [
            'Laptop' => 1, 'HP' => 2, 'PC/AIO' => 3, 'Printer' => 4, 'Proyektor' => 5, 'Others' => 6
        ];

        DB::table('barang')->insert([
            [
                'perusahaan_id' => $perusahaanMapping['SCO'],
                'jenis_barang_id' => $jenisBarangMapping['Laptop'],
                'no_asset' => 'SCO/LTP/2023/01/0001',
                'merek' => 'Lenovo ThinkPad X1 Carbon',
                'tgl_pengadaan' => '2023-01-15',
                'serial_number' => 'PF2S9X8Y',
                'status' => 'digunakan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan_id' => $perusahaanMapping['SCT'],
                'jenis_barang_id' => $jenisBarangMapping['PC/AIO'],
                'no_asset' => 'SCT/PC/2022/11/0001',
                'merek' => 'HP EliteOne 800 G6 AIO',
                'tgl_pengadaan' => '2022-11-20',
                'serial_number' => '8CC1234ABC',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan_id' => $perusahaanMapping['Migen'],
                'jenis_barang_id' => $jenisBarangMapping['Printer'],
                'no_asset' => 'Migen/PRT/2024/03/0001',
                'merek' => 'Canon PIXMA G3010',
                'tgl_pengadaan' => '2024-03-01',
                'serial_number' => 'ABCD12345',
                'status' => 'digunakan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan_id' => $perusahaanMapping['SCP'],
                'jenis_barang_id' => $jenisBarangMapping['HP'],
                'no_asset' => 'SCP/HP/2023/05/0001',
                'merek' => 'Samsung Galaxy A54 5G',
                'tgl_pengadaan' => '2023-05-22',
                'serial_number' => '358765432109876',
                'status' => 'diperbaiki',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan_id' => $perusahaanMapping['SCO'],
                'jenis_barang_id' => $jenisBarangMapping['Proyektor'],
                'no_asset' => 'SCO/PYK/2024/02/0002',
                'merek' => 'Epson EB-X51',
                'tgl_pengadaan' => '2024-02-10',
                'serial_number' => 'X8YZA7BCDE',
                'status' => 'non aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [ 
                'perusahaan_id' => $perusahaanMapping['SCP'],
                'jenis_barang_id' => $jenisBarangMapping['HP'],
                'no_asset' => 'SCP/HP/2025/06/0002',
                'merek' => 'Samsung J2 Prime',
                'tgl_pengadaan' => '2025-06-08',
                'serial_number' => '351234567890123',
                'status' => 'digunakan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}