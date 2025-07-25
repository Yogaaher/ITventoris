<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $nama_perusahaan
 * @property string $singkatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereNamaPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereSingkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Perusahaan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Perusahaan extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'nama_perusahaan',
        'singkatan',
    ];
}