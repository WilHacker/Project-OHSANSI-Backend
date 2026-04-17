<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id_nivel
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AreaNivel> $areaNiveles
 * @property-read int|null $area_niveles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel whereIdNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Nivel extends Model
{
    use HasFactory;

    protected $table = 'nivel';
    protected $primaryKey = 'id_nivel';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
    ];

    public function areaNiveles(): HasMany
    {
        return $this->hasMany(AreaNivel::class, 'id_nivel');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('catalogo_niveles'));
        static::deleted(fn () => Cache::forget('catalogo_niveles'));
    }
}
