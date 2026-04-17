<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id_area_olimpiada
 * @property int|null $id_area
 * @property int|null $id_olimpiada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Area|null $area
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AreaNivel> $areaNiveles
 * @property-read int|null $area_niveles_count
 * @property-read \App\Models\Olimpiada|null $olimpiada
 * @property-read \App\Models\ResponsableArea|null $responsableArea
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada whereIdArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada whereIdAreaOlimpiada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada whereIdOlimpiada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AreaOlimpiada extends Model
{
    use HasFactory;

    protected $table = 'area_olimpiada';
    protected $primaryKey = 'id_area_olimpiada';
    public $timestamps = true;

    protected $fillable = [
        'id_area',
        'id_olimpiada',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'id_area', 'id_area');
    }

    public function olimpiada(): BelongsTo
    {
        return $this->belongsTo(Olimpiada::class, 'id_olimpiada', 'id_olimpiada');
    }

    public function responsableArea(): HasOne
    {
        return $this->hasOne(ResponsableArea::class, 'id_area_olimpiada', 'id_area_olimpiada');
    }

    public function areaNiveles(): HasMany
    {
        return $this->hasMany(AreaNivel::class, 'id_area_olimpiada', 'id_area_olimpiada');
    }

}
