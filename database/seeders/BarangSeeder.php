<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;       // <-- Pastikan model Barang di-import
use Illuminate\Support\Facades\DB; // <-- Import DB Facade

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        DB::table('barang')->insert([
            [
                'perusahaan' => 'SCO',
                'jenis_barang' => 'Laptop',
                'no_asset' => 'AST/SCO/LAP/2023/001',
                'merek' => 'Lenovo ThinkPad X1 Carbon',
                'tgl_pengadaan' => '2023-01-15',
                'serial_number' => 'SN-SCO-LAP-001XYZ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan' => 'SCT',
                'jenis_barang' => 'PC/AIO',
                'no_asset' => 'AST/SCT/PC/2022/010',
                'merek' => 'HP EliteOne 800 G6 AIO',
                'tgl_pengadaan' => '2022-11-20',
                'serial_number' => 'SN-SCT-PC-010ABC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan' => 'Migen',
                'jenis_barang' => 'Printer',
                'no_asset' => 'AST/MIG/PRN/2024/005',
                'merek' => 'Canon PIXMA G3010',
                'tgl_pengadaan' => '2024-03-01',
                'serial_number' => 'SN-MIG-PRN-005DEF',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan' => 'SCP',
                'jenis_barang' => 'HP',
                'no_asset' => 'AST/SCP/HP/2023/012',
                'merek' => 'Samsung Galaxy A54 5G',
                'tgl_pengadaan' => '2023-05-22',
                'serial_number' => 'SN-SCP-HP-012GHI',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'perusahaan' => 'SCO',
                'jenis_barang' => 'Proyektor',
                'no_asset' => 'AST/SCO/PRO/2024/002',
                'merek' => 'Epson EB-X51',
                'tgl_pengadaan' => '2024-02-10',
                'serial_number' => 'SN-SCO-PRO-002JKL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}