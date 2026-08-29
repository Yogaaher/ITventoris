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
     * Jika track yang diubah adalah track terbaru, sinkronkan statusnya ke barang.
     */
    public function updated(Track $track): void
    {
        $barang = Barang::where('serial_number', $track->serial_number)->first();
        if ($barang) {
            $latestTrack = Track::where('serial_number', $track->serial_number)->latest('id')->first();
            if ($latestTrack && $latestTrack->id === $track->id && $barang->status !== $track->status) {
                $barang->status = $track->status;
                $barang->saveQuietly();
            }
        }
    }

    /**
     * Handle the Track "deleted" event.
     * Jika track terbaru dihapus, kembalikan status barang ke track sebelumnya atau 'tersedia'.
     */
    public function deleted(Track $track): void
    {
        $barang = Barang::where('serial_number', $track->serial_number)->first();
        if ($barang) {
            $latestTrack = Track::where('serial_number', $track->serial_number)->latest('id')->first();
            if ($latestTrack) {
                if ($barang->status !== $latestTrack->status) {
                    $barang->status = $latestTrack->status;
                    $barang->saveQuietly();
                }
            } else {
                $barang->status = 'tersedia';
                $barang->saveQuietly();
            }
        }
    }
}
