<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Barang;
use Carbon\Carbon;

class TrackSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('track')->truncate();

        $users = ['andi', 'budi', 'citra', 'dina', 'eko', 'fajar', 'gina', 'hadi', 'tamu', 'bagas', 'agung', 'sari', 'rini'];
        
        // Sesuaikan nama 'key' dengan nama jenis barang di database Anda
        $kerusakanPerJenis = [
            'Laptop' => ['layar tidak menyala', 'baterai cepat habis', 'keyboard error', 'port USB rusak', 'tidak bisa menyala', 'touchpad bermasalah'],
            'PC/AIO' => ['power supply rusak', 'layar tidak tampil', 'motherboard bermasalah', 'RAM error', 'HDD/SSD gagal baca'],
            'Printer' => ['tinta macet', 'tidak bisa menarik kertas', 'hasil cetak buram', 'print head rusak', 'scanner error'],
            'HP' => ['layar retak', 'baterai bocor', 'tidak bisa mengisi daya', 'kamera error', 'sinyal lemah', 'speaker mati'],
            'Proyektor' => ['tidak ada tampilan', 'lampu mati', 'overheating', 'gambar buram', 'port HDMI rusak'],
            'Others' => ['kabel putus', 'konektor rusak', 'tidak terdeteksi', 'perangkat mati total'],
        ];

        // Eager load relasi untuk performa yang lebih baik
        $barangs = Barang::with('jenisBarang')->get();
        $tracks = [];

        foreach ($barangs as $barang) {
            $jumlahUserUntukBarangIni = rand(1, 4);
            $userTerpilihUntukBarangIni = collect($users)->random($jumlahUserUntukBarangIni)->values();
            
            $currentTanggal = Carbon::parse($barang->tgl_pengadaan)->startOfDay();

            for ($i = 0; $i < $jumlahUserUntukBarangIni; $i++) {
                $username = $userTerpilihUntukBarangIni[$i];
                $isLastUser = ($i == $jumlahUserUntukBarangIni - 1);
                
                $tanggalAwal = $currentTanggal->copy();
                $statusSaatIni = 'digunakan';

                // Pastikan tanggal awal tidak di masa depan
                if ($tanggalAwal->isFuture()) {
                    continue; // Lewati jika pengadaan barang di masa depan
                }
                
                $tanggalAkhir = null;
                if (!$isLastUser) {
                    $durasiPenggunaanBulan = rand(3, 12);
                    $tanggalAkhir = $tanggalAwal->copy()->addMonths($durasiPenggunaanBulan)->subDay();
                    $currentTanggal = $tanggalAkhir->copy()->addDay();
                    
                    if (rand(1, 5) == 1) {
                        $statusSaatIni = collect(['diperbaiki', 'dipindah'])->random();
                    }
                } else {
                    // Logika untuk user terakhir
                    $statusSaatIni = $barang->status; // Status terakhir mengikuti status barang
                    if ($statusSaatIni === 'non aktif' || $statusSaatIni === 'diperbaiki') {
                        $tanggalAkhir = $tanggalAwal->copy()->addMonths(rand(6, 15));
                        if ($tanggalAkhir->isFuture()) {
                            $tanggalAkhir = now()->subDay();
                        }
                    } else {
                        $tanggalAkhir = null; // Masih digunakan atau tersedia
                    }
                }
                
                // Final check untuk tanggal akhir
                if ($tanggalAkhir && $tanggalAkhir->greaterThan(now())) {
                    $tanggalAkhir = now()->subDay();
                }
                if ($tanggalAkhir && $tanggalAkhir->lessThan($tanggalAwal)) {
                    $tanggalAkhir = $tanggalAwal->copy()->addMonth();
                }

                $jenisBarangNama = $barang->jenisBarang->nama_jenis ?? 'Others'; // Ambil nama jenis barang dari relasi
                
                $keterangan = "Status aset saat dipegang oleh $username.";
                if ($statusSaatIni == 'diperbaiki') {
                    $kerusakan = collect($kerusakanPerJenis[$jenisBarangNama] ?? ['kerusakan umum'])->random();
                    $keterangan = "Aset diperbaiki karena $kerusakan.";
                } elseif ($statusSaatIni == 'dipindah') {
                    $keterangan = "Aset dipindahkan dari/ke $username ke lokasi lain.";
                } elseif ($statusSaatIni == 'non aktif') {
                    $keterangan = "Aset dinonaktifkan setelah digunakan oleh $username.";
                } elseif ($statusSaatIni == 'tersedia') {
                    $keterangan = "Aset tersedia setelah digunakan oleh $username.";
                }

                $tracks[] = [
                    'serial_number' => $barang->serial_number,
                    'username' => $username,
                    'status' => $statusSaatIni,
                    'keterangan' => $keterangan,
                    'tanggal_awal' => $tanggalAwal->toDateString(),
                    'tanggal_ahir' => $tanggalAkhir ? $tanggalAkhir->toDateString() : null,
                    'created_at' => $tanggalAwal,
                    'updated_at' => $tanggalAwal,
                ];
            }
        }
        
        // Insert semua data track sekaligus untuk performa lebih baik
        DB::table('track')->insert($tracks);

        Schema::enableForeignKeyConstraints();
    }
}