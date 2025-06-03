<?php

namespace App\Observers;

use App\Models\Track;
use App\Models\Barang;

class TrackObserver
{
    /**
     * Handle the Track "created" event.
     * Setiap kali track baru dibuat, update status di tabel barang.
     */
    public function created(Track $track): void
    {
        // Cari barang yang berelasi dengan track ini
        $barang = Barang::where('serial_number', $track->serial_number)->first();

        if ($barang) {
            // Update status di tabel barang dengan status dari track yang baru dibuat
            if ($barang->status !== $track->status) {
                $barang->status = $track->status;
                $barang->saveQuietly(); // saveQuietly agar tidak memicu event lain jika ada
            }
        }
    }

    /**
     * Handle the Track "updated" event.
     * (Opsional, jika Anda mengizinkan status di track diubah setelah dibuat)
     */
    public function updated(Track $track): void
    {
        // Jika ada kemungkinan status di track diubah dan itu harus tercermin
        // sebagai status TERKINI, maka logika serupa bisa diterapkan di sini.
        // Namun, biasanya track adalah history, jadi 'created' lebih relevan.

        // Jika Anda ingin update barang.status HANYA JIKA track yang diupdate adalah track TERBARU
        // maka logikanya akan lebih kompleks. Untuk sekarang, kita fokus pada 'created'.
        // Contoh sederhana jika setiap update track harus update barang:
        // $barang = $track->barang; // Asumsi relasi barang() sudah ada di Track model
        // if ($barang && $barang->status !== $track->status) {
        //     // Cek apakah ini track terbaru sebelum update
        //     $latestTrackForBarang = $barang->latestTrack()->first();
        //     if ($latestTrackForBarang && $latestTrackForBarang->id === $track->id) {
        //          $barang->status = $track->status;
        //          $barang->saveQuietly();
        //     }
        // }
    }

    // ... (deleted, restored methods jika diperlukan)
}
