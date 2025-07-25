<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('barang')->truncate();
        $perusahaan = DB::table('perusahaans')->get()->keyBy('id');
        $jenisBarangMapping = [
            1 => ['nama' => 'Laptop', 'kode' => 'LTP'],
            2 => ['nama' => 'HP', 'kode' => 'HP'],
            3 => ['nama' => 'PC/AIO', 'kode' => 'PC'],
            4 => ['nama' => 'Printer', 'kode' => 'PRT'],
            5 => ['nama' => 'Proyektor', 'kode' => 'PYK'],
            6 => ['nama' => 'Others', 'kode' => 'OTH'],
        ];

        $merekPerJenis = [
            1 => ['Lenovo ThinkPad T14', 'Dell XPS 13', 'HP Spectre x360', 'Macbook Pro 14"', 'Asus Zenbook Duo'],
            2 => ['Samsung Galaxy S23', 'iPhone 15 Pro', 'Xiaomi 13T', 'Oppo Reno10', 'Google Pixel 8'],
            3 => ['HP EliteOne 800 G9 AIO', 'Dell OptiPlex 7010', 'Lenovo IdeaCentre AIO 3', 'PC Rakitan Core i7'],
            4 => ['Epson L3210', 'HP Smart Tank 515', 'Canon PIXMA G3010', 'Brother DCP-T520W'],
            5 => ['Epson EB-X51', 'BenQ MW560', 'InFocus IN114xv', 'Anker Nebula Capsule'],
            6 => ['WD My Passport 1TB', 'Logitech C920 Webcam', 'JBL Flip 6 Speaker', 'Mikrotik RB951Ui'],
        ];

        $statuses = ['digunakan', 'tersedia', 'diperbaiki', 'non aktif'];
        $barangData = [];
        $assetCounters = [];
        for ($i = 0; $i < 30; $i++) {
            $perusahaanId = $perusahaan->keys()->random();
            $perusahaanSingkatan = $perusahaan[$perusahaanId]->singkatan;
            $jenisBarangId = array_rand($jenisBarangMapping);
            $jenisBarang = $jenisBarangMapping[$jenisBarangId];
            $merek = $merekPerJenis[$jenisBarangId][array_rand($merekPerJenis[$jenisBarangId])];
            $tglPengadaan = now()->subYears(rand(0, 4))->subMonths(rand(0, 11))->subDays(rand(0, 28));
            $year = $tglPengadaan->year;
            $month = str_pad($tglPengadaan->month, 2, '0', STR_PAD_LEFT);
            $counterKey = "{$perusahaanSingkatan}/{$year}";
            
            if (!isset($assetCounters[$counterKey])) {
                $assetCounters[$counterKey] = 1;
            }
            $sequence = str_pad($assetCounters[$counterKey]++, 4, '0', STR_PAD_LEFT);
            $barangData[] = [
                'perusahaan_id' => $perusahaanId,
                'jenis_barang_id' => $jenisBarangId,
                'no_asset' => "{$perusahaanSingkatan}/{$jenisBarang['kode']}/{$year}/{$month}/{$sequence}",
                'merek' => $merek,
                'tgl_pengadaan' => $tglPengadaan->toDateString(),
                'serial_number' => Str::upper(Str::random(10)),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('barang')->insert($barangData);
        Schema::enableForeignKeyConstraints();
    }
}