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
    public function run(): void
    {
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
            $barangTerakhir = Barang::where('perusahaan_id', $perusahaanId)
                ->orderBy('id', 'desc')
                ->first();
            if ($barangTerakhir) {
                $parts = explode('/', $barangTerakhir->no_asset);
                $nomorUrutTerakhir = (int) end($parts);
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
