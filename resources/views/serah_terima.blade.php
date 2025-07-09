<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Berita Serah Terima - {{ $nomor_surat }}</title>
    <style>
        /* CSS ini dioptimalkan untuk library DomPDF */
        @page {
            margin: 2.5cm 2cm;
            /* Margin standar untuk dokumen A4 */
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .header h3 {
            text-decoration: underline;
            font-size: 14pt;
            margin-bottom: 5px;
        }

        .header p {
            margin-top: 0;
            font-size: 12pt;
        }

        /* Tabel untuk data Pihak Pertama dan Kedua */
        .info-table {
            width: 100%;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .info-table .label {
            width: 80px;
            /* Lebar kolom label (Nama, NIK, Jabatan) */
        }

        .info-table .separator {
            width: 10px;
            /* Lebar kolom pemisah (:) */
        }

        /* Tabel utama untuk detail barang */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
            margin-top: 20px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid black;
            padding: 8px;
            vertical-align: top;
        }

        .main-table th {
            background-color: #f2f2f2;
            /* Warna abu-abu muda untuk header tabel */
            font-weight: bold;
            text-align: center;
        }

        .main-table .label-col {
            width: 35%;
            font-weight: bold;
        }

        .keterangan-cell {
            height: 80px;
            /* Memberi ruang lebih untuk keterangan */
        }

        /* Daftar Kewajiban */
        .kewajiban-list {
            padding-left: 20px;
            /* Indentasi untuk ordered list */
            margin-top: 20px;
        }

        .kewajiban-list li {
            margin-bottom: 10px;
            /* Jarak antar poin */
            text-align: justify;
        }

        /* Bagian Tanda Tangan */
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }

        .signature-box {
            width: 48%;
            /* Bagi 2 kolom */
            float: left;
            text-align: center;
        }

        .signature-box.right {
            float: right;
        }

        .signature-space {
            height: 80px;
            /* Ruang untuk tanda tangan basah */
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>

    <div class="header text-center">
        <h3>BERITA SERAH TERIMA</h3>
        <p>Nomor Surat : {{ $nomor_surat ?? '____________________' }}</p>
    </div>

    <p style="margin-top: 30px;">
        Pada hari ini tanggal {{ $tanggal_serah_terima ?? '____________________' }}, Yang bertanda tangan dibawah ini :
    </p>

    {{-- Pihak Pertama --}}
    <table class="info-table">
        <tr>
            <td class="label">Nama</td>
            <td class="separator">:</td>
            <td>{{ $penyerah_nama ?? '____________________' }}</td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td class="separator">:</td>
            <td>{{ $penyerah_nik ?? '____________________' }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="separator">:</td>
            <td>{{ $penyerah_jabatan ?? '____________________' }}</td>
        </tr>
    </table>
    <p>Dalam hal ini disebut <span class="text-bold">PIHAK PERTAMA ( I )</span> atau yang menyerahkan,</p>

    {{-- Pihak Kedua --}}
    <table class="info-table">
        <tr>
            <td class="label">Nama</td>
            <td class="separator">:</td>
            <td>{{ $penerima_nama ?? '____________________' }}</td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td class="separator">:</td>
            <td>{{ $penerima_nik ?? '____________________' }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="separator">:</td>
            <td>{{ $penerima_jabatan ?? '____________________' }}</td>
        </tr>
    </table>
    <p>Dalam hal ini disebut <span class="text-bold">PIHAK KEDUA ( II )</span> atau yang menerima</p>

    <p>Kedua belah pihak secara bersama-sama telah mengadakan serah terima barang berupa :</p>

    <table class="main-table">
        <thead>
            <tr>
                <th colspan="2">JENIS BARANG</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="label-col">Perusahaan</td>
                <td>{{ $barang_perusahaan ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-col">Nomor Asset Berlaku</td>
                <td>{{ $barang_nomor_asset ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-col">Merek</td>
                <td>{{ $barang_merek ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-col">Serial Number</td>
                <td>{{ $barang_serial_number ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-col">Tanggal Pengadaan</td>
                <td>{{ $barang_tanggal_pengadaan ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-col">Penanggung Jawab</td>
                <td>{{ $barang_penanggung_jawab ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-col">Keterangan</td>
                <td class="keterangan-cell">{{ $barang_keterangan ?? '' }}</td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 20px;">
        Untuk selanjutnya <span class="text-bold">PIHAK KEDUA</span> selaku pemegan Inventaris tersebut berkewajiban untuk mematuhi hal-hal sebagai berikut :
    </p>

    <ol class="kewajiban-list">
        <li>Pihak kedua harus menjaga dan merawat barang inventaris yang diberikan oleh Pihak pertama dengan baik.</li>
        <li>Jika terjadi kerusakan dan atau hal lain yang menyebabkan barang inventaris tersebut tidak berfungsi maka Pihak kedua wajib menginformasikan kepada pihak pertama guna diperbaiki secara teknis.</li>
        <li>Jika pihak kedua mengundurkan diri, maka barang inventaris tersebut dikembalikan secara utuh dan dalam keadaan baik seperti pada saat awal pihak pertama memberikan barang inventaris tersebut kepada pihak kedua.</li>
        <li>Pihak kedua telah menerima barang dengan detail (terlampir) diatas dalam keadaan baik sesuai keadaan “kondisi barang”.</li>
        <li>Jika barang yang sudah diterima oleh pihak kedua terjadi kerusakan yang diakibatkan kelalaian oleh pihak kedua, maka pihak kedua wajib mengganti sesuai Surat Keputusan Direktur (SK Direktur) atas pergantian kerugian.</li>
    </ol>

    <p style="margin-top: 20px;">
        Demikian Berita Acara Serah Terima barang inventaris ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
    </p>

    <div class="signature-section">
        <div class="signature-box">
            <p>Yang memberikan</p>
            <div class="signature-space"></div>
            <p class="text-bold">{{ $penyerah_nama ?? '..............................' }}</p>
        </div>
        <div class="signature-box right">
            <p>Yang menerima</p>
            <div class="signature-space"></div>
            <p class="text-bold">{{ $penerima_nama ?? '..............................' }}</p>
        </div>
        <div class="clear"></div>
    </div>

</body>

</html>