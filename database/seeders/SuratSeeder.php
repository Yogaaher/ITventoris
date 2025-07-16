<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Sequence;
use Carbon\Carbon;

class SuratSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Persiapan
        Schema::disableForeignKeyConstraints();
        DB::table('surats')->truncate();

        // Bersihkan sequence yang relevan jika seeder dijalankan ulang
        $now = Carbon::now();
        $sequenceKey = 'surat_nomor_' . $now->format('Y_m');
        DB::table('sequences')->where('key', $sequenceKey)->delete();

        // 2. Ambil data yang dibutuhkan
        $availableBarang = DB::table('barang')->where('status', 'tersedia')->get();

        if ($availableBarang->isEmpty()) {
            $this->command->info('Tidak ada barang "tersedia" untuk dibuatkan surat.');
            Schema::enableForeignKeyConstraints();
            return;
        }

        // 3. Siapkan data dummy
        $karyawan = [
            ['nama' => 'Budi Santoso', 'jabatan' => 'Staff Operasional'],
            ['nama' => 'Siti Aminah', 'jabatan' => 'Marketing'],
            ['nama' => 'Andi Wijaya', 'jabatan' => 'Keuangan'],
            ['nama' => 'Dewi Lestari', 'jabatan' => 'HRD'],
            ['nama' => 'Joko Prabowo', 'jabatan' => 'Logistik'],
        ];
        $pemberiIT = ['nama' => 'Rizky Maulana', 'jabatan' => 'IT Support Staff'];
        $penanggungJawabIT = 'Agus Wijaya';

        $suratData = [];
        $barangToUpdate = [];

        // Ambil atau buat sequence untuk bulan ini
        $sequence = Sequence::firstOrCreate(
            ['key' => $sequenceKey],
            ['value' => 0]
        );
        $suratCounter = $sequence->value;

        // 4. Looping untuk setiap barang
        foreach ($availableBarang as $barang) {
            $penerima = $karyawan[array_rand($karyawan)];
            $nikPenerima = '3174' . mt_rand(100000000000, 999999999999);
            $nikPemberi = '3172' . mt_rand(100000000000, 999999999999);

            $suratCounter++;
            $noSurat = sprintf("BST/%s/%s/%03d", $now->year, $now->format('m'), $suratCounter);

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
                'keterangan' => 'Serah terima inventaris kantor...',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $barangToUpdate[] = $barang->id;
        }

        // 5. Masukkan data ke database
        if (!empty($suratData)) {
            DB::table('surats')->insert($suratData);
            $sequence->value = $suratCounter;
            $sequence->save();
            $this->command->info(count($suratData) . ' data serah terima berhasil dibuat.');
        }

        // 6. Update status barang
        if (!empty($barangToUpdate)) {
            DB::table('barang')->whereIn('id', $barangToUpdate)->update(['status' => 'digunakan']);
            $this->command->info(count($barangToUpdate) . ' status barang diupdate.');
        }

        // 7. Aktifkan constraint
        Schema::enableForeignKeyConstraints();
    }
}
