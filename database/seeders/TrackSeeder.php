<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;

class TrackSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['digunakan', 'diperbaiki', 'dipindah', 'non aktif'];
        $users = ['andi', 'budi', 'citra', 'dina', 'eko', 'fajar', 'gina', 'hadi'];

        $kerusakanPerJenis = [
            'Laptop' => ['layar tidak menyala', 'baterai cepat habis', 'keyboard error', 'port USB rusak', 'tidak bisa menyala'],
            'PC/AIO' => ['power supply rusak', 'layar tidak tampil', 'motherboard bermasalah', 'RAM error'],
            'Printer' => ['tinta macet', 'tidak bisa menarik kertas', 'hasil cetak buram', 'print head rusak'],
            'HP' => ['layar retak', 'baterai bocor', 'tidak bisa mengisi daya', 'kamera error'],
            'Proyektor' => ['tidak ada tampilan', 'lampu mati', 'overheating', 'gambar buram'],
        ];

        $barangs = Barang::all();

        foreach ($barangs as $barang) {
            $jumlahUser = rand(1, 3);
            $usedUsers = collect($users)->random($jumlahUser);
            $lastDate = now()->subMonths(rand(12, 24))->startOfMonth(); // Start awal timeline untuk barang ini

            foreach ($usedUsers as $username) {
                $status = $statuses[array_rand($statuses)];

                $tanggalAwal = $lastDate->copy();
                $durasi = rand(1, 6); // Durasi penggunaan dalam bulan
                $tanggalAkhir = rand(0, 1) ? $tanggalAwal->copy()->addMonths($durasi) : null;

                // Simpan tanggal akhir untuk dipakai user berikutnya
                $lastDate = $tanggalAkhir ? $tanggalAkhir->copy()->addDays(1) : $tanggalAwal->copy()->addMonths($durasi + 1);

                $keterangan = match ($status) {
                    'digunakan' => "Sedang digunakan oleh $username untuk kebutuhan operasional.",
                    'diperbaiki' => "Pernah diservis karena " . collect($kerusakanPerJenis[$barang->jenis_barang] ?? ['kerusakan tidak diketahui'])->random() . ".",
                    'dipindah' => "Barang dipindah ke divisi lain sesuai permintaan manajer.",
                    'non aktif' => "Sudah tidak digunakan karena penggantian unit baru.",
                    default => "Digunakan oleh $username."
                };

                DB::table('track')->insert([
                    'serial_number' => $barang->serial_number,
                    'username' => $username,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'tanggal_awal' => $tanggalAwal->toDateString(),
                    'tanggal_ahir' => $tanggalAkhir ? $tanggalAkhir->toDateString() : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
