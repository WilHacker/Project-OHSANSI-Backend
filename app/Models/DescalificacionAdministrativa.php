<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id_descalificacion
 * @property int $id_competidor
 * @property string $observaciones
 * @property \Illuminate\Support\Carbon $fecha_descalificacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Competidor $competidor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa whereFechaDescalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa whereIdCompetidor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa whereIdDescalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DescalificacionAdministrativa extends Model
{
    use HasFactory;

    protected $table = 'descalificacion_administrativa';


    protected $primaryKey = 'id_descalificacion';
    protected $fillable = [
        'id_competidor',
        'observaciones',
        'fecha_descalificacion',
    ];

    protected $casts = [
        'fecha_descalificacion' => 'datetime',
    ];

    public function competidor(): BelongsTo
    {
        return $this->belongsTo(Competidor::class, 'id_competidor', 'id_competidor');
    }
}
