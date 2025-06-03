<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use Carbon\Carbon;

class TrackSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data track lama jika ada, agar seeder bisa dijalankan ulang tanpa duplikasi
        // DB::table('track')->truncate(); // Hati-hati dengan ini di production

        $statuses = ['digunakan', 'diperbaiki', 'dipindah', 'non aktif', 'tersedia'];
        $users = ['andi', 'budi', 'citra', 'dina', 'eko', 'fajar', 'gina', 'hadi', 'tamu', 'bagas', 'agung']; // Tambahkan user jika perlu

        $kerusakanPerJenis = [
            'Laptop' => ['layar tidak menyala', 'baterai cepat habis', 'keyboard error', 'port USB rusak', 'tidak bisa menyala', 'touchpad bermasalah'],
            'PC/AIO' => ['power supply rusak', 'layar tidak tampil', 'motherboard bermasalah', 'RAM error', 'HDD/SSD gagal baca'],
            'Printer' => ['tinta macet', 'tidak bisa menarik kertas', 'hasil cetak buram', 'print head rusak', 'scanner error'],
            'HP' => ['layar retak', 'baterai bocor', 'tidak bisa mengisi daya', 'kamera error', 'sinyal lemah', 'speaker mati'],
            'Proyektor' => ['tidak ada tampilan', 'lampu mati', 'overheating', 'gambar buram', 'port HDMI rusak'],
        ];

        $barangs = Barang::all();

        foreach ($barangs as $barang) {
            $jumlahUserUntukBarangIni = rand(1, 4); // Setiap barang akan digunakan oleh 1 sampai 4 user
            $userTerpilihUntukBarangIni = collect($users)->random($jumlahUserUntukBarangIni)->values(); // Ambil user secara acak dan re-index

            // Tentukan tanggal awal pengadaan barang ini sebagai titik mulai
            // Jika tgl_pengadaan null, kita fallback ke beberapa waktu lalu
            $currentTanggal = Carbon::parse($barang->tgl_pengadaan ?? now()->subYears(2)->subMonths(rand(0, 6)))->startOfDay();

            for ($i = 0; $i < $jumlahUserUntukBarangIni; $i++) {
                $username = $userTerpilihUntukBarangIni[$i];
                $isLastUser = ($i == $jumlahUserUntukBarangIni - 1); // Cek apakah ini user terakhir

                $tanggalAwal = $currentTanggal->copy(); // Tanggal awal untuk user ini

                $tanggalAkhir = null;
                $status = 'digunakan'; // Default status saat digunakan

                if (!$isLastUser) {
                    // Jika bukan user terakhir, tentukan durasi penggunaan
                    $durasiPenggunaanBulan = rand(3, 12); // User menggunakan aset selama 3-12 bulan
                    $tanggalAkhir = $tanggalAwal->copy()->addMonths($durasiPenggunaanBulan)->subDay(); // Tanggal akhir adalah sehari sebelum user berikutnya

                    // Update $currentTanggal untuk user berikutnya
                    $currentTanggal = $tanggalAkhir->copy()->addDay(); // Tanggal awal user berikutnya adalah sehari setelah tanggal akhir user ini

                    // Ada kemungkinan barang diperbaiki atau dipindah sebelum diserahkan ke user berikutnya
                    if (rand(1, 5) == 1) { // 20% kemungkinan ada status lain sebelum user berikutnya
                        $status = collect(['diperbaiki', 'dipindah'])->random();
                    }
                } else {
                    // Jika ini user terakhir, tanggal_ahir adalah NULL
                    // Dan status bisa 'digunakan', 'tersedia', atau 'non aktif' jika sudah lama
                    if ($tanggalAwal->diffInMonths(now()) > 18 && rand(0,1)) { // Jika sudah lama dan beruntung
                        $status = 'non aktif';
                        // Jika non aktif, kita bisa set tanggal akhirnya juga
                        $tanggalAkhir = $tanggalAwal->copy()->addMonths(rand(6,12));
                        if ($tanggalAkhir->greaterThan(now())) {
                            $tanggalAkhir = now()->subDay(); // Pastikan tidak di masa depan
                        }
                    } elseif (rand(0,2) == 0 && $status == 'digunakan') { // 33% kemungkinan jadi 'tersedia' jika masih digunakan
                        $status = 'tersedia';
                    }
                    // Jika statusnya 'digunakan' atau 'tersedia', tanggal_ahir tetap NULL
                    if ($status == 'non aktif' && $tanggalAkhir) {
                        // Tanggal akhir sudah diset
                    } else {
                        $tanggalAkhir = null;
                    }
                }

                $keterangan = "Status aset saat dipegang oleh $username.";
                if ($status == 'diperbaiki') {
                    $keterangan = "Aset diperbaiki karena " . collect($kerusakanPerJenis[$barang->jenis_barang] ?? ['kerusakan umum'])->random() . " saat dipegang/sebelum diserahkan ke $username.";
                } elseif ($status == 'dipindah') {
                    $keterangan = "Aset dipindahkan dari/ke $username ke lokasi/divisi lain.";
                } elseif ($status == 'non aktif') {
                    $keterangan = "Aset dinonaktifkan setelah digunakan oleh $username.";
                } elseif ($status == 'tersedia') {
                    $keterangan = "Aset tersedia setelah digunakan oleh $username atau siap digunakan.";
                }


                // Pastikan tanggal akhir tidak lebih awal dari tanggal awal (sangat jarang terjadi dengan logika ini, tapi jaga-jaga)
                if ($tanggalAkhir && Carbon::parse($tanggalAkhir)->lessThan(Carbon::parse($tanggalAwal))) {
                    $tanggalAkhir = $tanggalAwal->copy()->addMonths(1); // Beri minimal 1 bulan jika ada kesalahan
                }
                
                // Pastikan tanggal akhir tidak melebihi hari ini jika bukan NULL
                if ($tanggalAkhir && Carbon::parse($tanggalAkhir)->isFuture()){
                    $tanggalAkhir = now()->subDay(); // Set ke kemarin jika tanggal akhir di masa depan
                    if(Carbon::parse($tanggalAkhir)->lessThan(Carbon::parse($tanggalAwal))){
                        // jika setelah di set ke kemarin malah lebih kecil dari tanggal awal,
                        // maka user ini dianggap masih aktif
                        $tanggalAkhir = null;
                        $status = 'digunakan'; // atau 'tersedia'
                    }
                }


                DB::table('track')->insert([
                    'serial_number' => $barang->serial_number,
                    'username' => $username,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'tanggal_awal' => $tanggalAwal->toDateString(),
                    'tanggal_ahir' => $tanggalAkhir ? $tanggalAkhir->toDateString() : null,
                    'created_at' => $tanggalAwal->copy()->addHours(rand(1,10)), // Buat created_at sedikit setelah tanggal awal
                    'updated_at' => $tanggalAwal->copy()->addHours(rand(11,20)),
                ]);
            }
        }
    }
}