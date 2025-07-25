<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $key
 * @property int $value
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sequence whereValue($value)
 * @mixin \Eloquent
 */
class Sequence extends Model
{
    use HasFactory;

    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['key', 'value'];
}
