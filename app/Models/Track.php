<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Barang;
use App\Traits\LogsActivity;


/**
 * @property int $id
 * @property string $serial_number
 * @property string $username
 * @property string $status
 * @property string $keterangan
 * @property string $tanggal_awal
 * @property string|null $tanggal_ahir
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Barang $barang
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Track newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Track newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Track query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Track whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Track whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Track whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Track whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Track whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Track whereTanggalAhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Track whereTanggalAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Track whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Track whereUsername($value)
 * @mixin \Eloquent
 */
class Track extends Model
{
    use LogsActivity;
    protected $table = 'track';

    protected $fillable = [
        'serial_number',
        'username',
        'status',
        'keterangan',
        'tanggal_awal',
        'tanggal_ahir',
    ];

    // Relasi ke tabel barang (1 track → 1 barang)
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'serial_number', 'serial_number');
    }
}
