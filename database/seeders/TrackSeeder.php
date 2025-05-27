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

        // Daftar kerusakan relevan per jenis barang
        $kerusakanPerJenis = [
            'Laptop' => [
                'layar tidak menyala',
                'baterai cepat habis',
                'keyboard error',
                'port USB rusak',
                'tidak bisa menyala',
            ],
            'PC/AIO' => [
                'power supply rusak',
                'layar tidak tampil',
                'motherboard bermasalah',
                'RAM error',
            ],
            'Printer' => [
                'tinta macet',
                'tidak bisa menarik kertas',
                'hasil cetak buram',
                'print head rusak',
            ],
            'HP' => [
                'layar retak',
                'baterai bocor',
                'tidak bisa mengisi daya',
                'kamera error',
            ],
            'Proyektor' => [
                'tidak ada tampilan',
                'lampu mati',
                'overheating',
                'gambar buram',
            ]
        ];

        $barangs = Barang::all();

        foreach ($barangs as $barang) {
            $jumlahUser = rand(1, 3);
            $usedUsers = collect($users)->random($jumlahUser);

            foreach ($usedUsers as $username) {
                $tanggalAwal = now()->subMonths(rand(3, 18));
                $tanggalAkhir = rand(0, 1) ? now()->subMonths(rand(0, 2)) : null;

                $status = $statuses[array_rand($statuses)];

                // Ambil kerusakan relevan jika status diperbaiki
                $keterangan = match ($status) {
                    'digunakan' => "Sedang digunakan oleh $username untuk kebutuhan operasional.",
                    'diperbaiki' => "Pernah diservis karena " . collect($kerusakanPerJenis[$barang->jenis_barang] ?? ['kerusakan tidak diketahui'])->random() . ".",
                    'dipindah' => "Barang dipindah ke divisi lain sesuai permintaan manajer.",
                    'non aktif' => "Sudah tidak digunakan karena penggantian unit baru.",
                    default => "Digunakan oleh $username."
                };

                DB::table('track')->insert([
                    'no_asset' => $barang->no_asset,
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
