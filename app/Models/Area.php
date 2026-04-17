<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id_area
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AreaOlimpiada> $areaOlimpiadas
 * @property-read int|null $area_olimpiadas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Olimpiada> $olimpiadas
 * @property-read int|null $olimpiadas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereIdArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Area extends Model
{
    use HasFactory;

    protected $table = 'area';
    protected $primaryKey = 'id_area';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
    ];

    public function olimpiadas(): BelongsToMany
    {
        return $this->belongsToMany(Olimpiada::class, 'area_olimpiada', 'id_area', 'id_olimpiada')
                    ->withTimestamps();
    }

    public function areaOlimpiadas(): HasMany
    {
        return $this->hasMany(AreaOlimpiada::class, 'id_area', 'id_area');
    }

    public function areaOlimpiada(): HasOne
    {
        return $this->hasOne(AreaOlimpiada::class, 'id_area', 'id_area');
    }

    protected static function booted(): void
    {
        // Se ejecuta al Crear o Actualizar
        static::saved(function () {
            Cache::forget('catalogo_areas');
        });

        // Se ejecuta al Eliminar
        static::deleted(function () {
            Cache::forget('catalogo_areas');
        });
    }
}
