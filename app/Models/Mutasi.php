<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $barang_id
 * @property string $no_asset_lama
 * @property int $perusahaan_lama_id
 * @property string $pengguna_lama
 * @property string $no_asset_baru
 * @property int $perusahaan_baru_id
 * @property string $pengguna_baru
 * @property \Illuminate\Support\Carbon $tanggal_mutasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Barang $barang
 * @property-read \App\Models\Perusahaan $perusahaanBaru
 * @property-read \App\Models\Perusahaan $perusahaanLama
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi whereBarangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi whereNoAssetBaru($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi whereNoAssetLama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi wherePenggunaBaru($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi wherePenggunaLama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi wherePerusahaanBaruId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi wherePerusahaanLamaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi whereTanggalMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mutasi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Mutasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'no_asset_lama',
        'perusahaan_lama_id',
        'pengguna_lama',
        'no_asset_baru',
        'perusahaan_baru_id',
        'pengguna_baru',
        'tanggal_mutasi',
    ];

    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function perusahaanLama()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_lama_id');
    }

    public function perusahaanBaru()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_baru_id');
    }
}