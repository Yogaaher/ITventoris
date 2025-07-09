<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barang;
use App\Models\AssetCounter;
use App\Models\Track;
use App\Models\Perusahaan;
use App\Models\JenisBarang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->call([
            PerusahaanSeeder::class,
            JenisBarangSeeder::class,
            BarangSeeder::class,
            TrackSeeder::class,
            UserSeeder::class,
            SuratSeeder::class,
        ]);

        echo "\nSyncing asset counters...\n";
        $perusahaanIds = Barang::select('perusahaan_id')->distinct()->pluck('perusahaan_id');

        foreach ($perusahaanIds as $perusahaanId) {
            // Untuk setiap perusahaan, cari nomor aset TERTINGGI
            // Kita harus "membedah" nomor aset untuk mendapatkan angka terakhirnya
            $barangTerakhir = Barang::where('perusahaan_id', $perusahaanId)
                // Kita tidak bisa order by no_asset langsung karena formatnya kompleks, jadi kita order by ID
                // Asumsi ID yang lebih tinggi berarti barang lebih baru
                ->orderBy('id', 'desc')
                ->first();

            if ($barangTerakhir) {
                // "Bedah" no_asset untuk mendapatkan nomor urutnya
                // Contoh: SCT/LTP/2023/12/0015 -> kita butuh 15
                $parts = explode('/', $barangTerakhir->no_asset);
                $nomorUrutTerakhir = (int) end($parts); // Ambil bagian terakhir dan ubah ke integer

                // Gunakan updateOrCreate untuk membuat atau memperbarui counter
                AssetCounter::updateOrCreate(
                    ['perusahaan_id' => $perusahaanId],
                    ['nomor_terakhir' => $nomorUrutTerakhir]
                );

                $perusahaan = Perusahaan::find($perusahaanId);
                echo "  - Counter for {$perusahaan->singkatan} set to: {$nomorUrutTerakhir}\n";
            }
        }
        echo "Asset counters synced successfully!\n";
    }
}
