<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $no_surat
 * @property int $barang_id
 * @property string $nama_penerima
 * @property string $nik_penerima
 * @property string $jabatan_penerima
 * @property string $nama_pemberi
 * @property string $nik_pemberi
 * @property string $jabatan_pemberi
 * @property string $penanggung_jawab
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Barang $barang
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereBarangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereJabatanPemberi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereJabatanPenerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereNamaPemberi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereNamaPenerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereNikPemberi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereNikPenerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereNoSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat wherePenanggungJawab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Surat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Surat extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'surats';

    protected $fillable = [
        'no_surat',
        'barang_id',
        'nama_penerima',
        'nik_penerima',
        'jabatan_penerima',
        'nama_pemberi',
        'nik_pemberi',
        'jabatan_pemberi',
        'penanggung_jawab',
        'keterangan',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}