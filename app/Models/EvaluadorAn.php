<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id_evaluador_an
 * @property int|null $id_usuario
 * @property int|null $id_area_nivel
 * @property bool $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AreaNivel|null $areaNivel
 * @property-read \App\Models\Usuario|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn whereIdAreaNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn whereIdEvaluadorAn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EvaluadorAn extends Model
{
    use HasFactory;

    protected $table = 'evaluador_an';
    protected $primaryKey = 'id_evaluador_an';
    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'id_area_nivel',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function areaNivel(): BelongsTo
    {
        return $this->belongsTo(AreaNivel::class, 'id_area_nivel');
    }

}
