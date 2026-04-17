<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id_parametro
 * @property int|null $id_area_nivel
 * @property numeric|null $nota_min_aprobacion
 * @property int|null $cantidad_maxima
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AreaNivel|null $areaNivel
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereCantidadMaxima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereIdAreaNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereIdParametro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereNotaMinAprobacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Parametro extends Model
{
    use HasFactory;

    protected $table = 'parametro';
    protected $primaryKey = 'id_parametro';
    public $timestamps = true;

    protected $fillable = [
        'id_area_nivel',
        'nota_min_aprobacion',
        'cantidad_maxima',
    ];

    protected $casts = [
        'nota_min_aprobacion' => 'decimal:2',
        'cantidad_maxima'     => 'integer',
    ];

    public function areaNivel(): BelongsTo
    {
        return $this->belongsTo(AreaNivel::class, 'id_area_nivel', 'id_area_nivel');
    }
}