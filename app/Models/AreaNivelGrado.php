<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id_area_nivel
 * @property int $id_grado_escolaridad
 * @property-read \App\Models\AreaNivel $areaNivel
 * @property-read \App\Models\GradoEscolaridad $gradoEscolaridad
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivelGrado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivelGrado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivelGrado query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivelGrado whereIdAreaNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivelGrado whereIdGradoEscolaridad($value)
 * @mixin \Eloquent
 */
class AreaNivelGrado extends Pivot
{
    protected $table = 'area_nivel_grado';
    /** @phpstan-ignore-next-line */
    protected $primaryKey = ['id_area_nivel', 'id_grado_escolaridad'];
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_area_nivel',
        'id_grado_escolaridad',
    ];

    public function areaNivel(): BelongsTo
    {
        return $this->belongsTo(AreaNivel::class, 'id_area_nivel', 'id_area_nivel');
    }

    public function gradoEscolaridad(): BelongsTo
    {
        return $this->belongsTo(GradoEscolaridad::class, 'id_grado_escolaridad', 'id_grado_escolaridad');
    }
}
