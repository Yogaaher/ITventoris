<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class SuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 1. Persiapan: Nonaktifkan constraint & bersihkan tabel
        Schema::disableForeignKeyConstraints();
        DB::table('surats')->truncate();

        // 2. Ambil semua barang yang statusnya 'tersedia' untuk dibuatkan surat serah terima
        $availableBarang = DB::table('barang')->where('status', 'tersedia')->get();

        if ($availableBarang->isEmpty()) {
            $this->command->info('Tidak ada barang dengan status "tersedia" untuk dibuatkan surat serah terima.');
            Schema::enableForeignKeyConstraints();
            return;
        }

        // 3. Siapkan data dummy untuk orang-orang yang terlibat
        $karyawan = [
            ['nama' => 'Ahmad Subagyo', 'jabatan' => 'Frontend Developer'],
            ['nama' => 'Bunga Citra', 'jabatan' => 'Backend Developer'],
            ['nama' => 'Charlie Darmawan', 'jabatan' => 'Project Manager'],
            ['nama' => 'Diana Sari', 'jabatan' => 'UI/UX Designer'],
            ['nama' => 'Eko Prasetyo', 'jabatan' => 'Quality Assurance'],
            ['nama' => 'Fitriani', 'jabatan' => 'Human Resources'],
            ['nama' => 'Gilang Ramadhan', 'jabatan' => 'Finance Staff'],
        ];

        $pemberiIT = ['nama' => 'Rizky Maulana', 'jabatan' => 'IT Support Staff'];
        $penanggungJawabIT = 'Agus Wijaya'; // Kepala Divisi IT

        $suratData = [];
        $barangToUpdate = [];
        $suratCounter = 1;

        // 4. Looping untuk setiap barang yang tersedia
        foreach ($availableBarang as $barang) {
            // Pilih penerima secara acak dari daftar karyawan
            $penerima = $karyawan[array_rand($karyawan)];

            // Generate NIK acak 16 digit
            $nikPenerima = '3174' . mt_rand(100000000000, 999999999999);
            $nikPemberi = '3172' . mt_rand(100000000000, 999999999999);

            // Generate nomor surat unik
            $now = Carbon::now();
            $noSurat = sprintf("BST/%s/%s/%03d", $now->year, $now->format('m'), $suratCounter++);

            // Tambahkan data surat ke array
            $suratData[] = [
                'no_surat' => $noSurat,
                'barang_id' => $barang->id,
                'nama_penerima' => $penerima['nama'],
                'nik_penerima' => substr($nikPenerima, 0, 16),
                'jabatan_penerima' => $penerima['jabatan'],
                'nama_pemberi' => $pemberiIT['nama'],
                'nik_pemberi' => substr($nikPemberi, 0, 16),
                'jabatan_pemberi' => $pemberiIT['jabatan'],
                'penanggung_jawab' => $penanggungJawabIT,
                'keterangan' => 'Serah terima inventaris kantor berupa ' . $barang->merek . ' untuk menunjang pekerjaan. Mohon untuk dijaga dan digunakan dengan sebaik-baiknya.',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Tandai ID barang ini untuk diupdate statusnya nanti
            $barangToUpdate[] = $barang->id;
        }

        // 5. Masukkan semua data surat ke database
        if (!empty($suratData)) {
            DB::table('surats')->insert($suratData);
            $this->command->info(count($suratData) . ' data serah terima berhasil dibuat.');
        }

        // 6. Update status barang dari 'tersedia' menjadi 'digunakan'
        if (!empty($barangToUpdate)) {
            DB::table('barang')->whereIn('id', $barangToUpdate)->update(['status' => 'digunakan']);
            $this->command->info(count($barangToUpdate) . ' status barang berhasil diupdate menjadi "digunakan".');
        }

        // 7. Aktifkan kembali constraint
        Schema::enableForeignKeyConstraints();
    }
}