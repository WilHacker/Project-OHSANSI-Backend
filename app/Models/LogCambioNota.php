<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id_log_cambio_nota
 * @property int|null $id_evaluacion
 * @property int $id_usuario_autor
 * @property numeric $nota_nueva
 * @property numeric $nota_anterior
 * @property string $motivo_cambio
 * @property \Illuminate\Support\Carbon $fecha_cambio
 * @property-read \App\Models\Usuario $autor
 * @property-read \App\Models\Evaluacion|null $evaluacion
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereFechaCambio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereIdEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereIdLogCambioNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereIdUsuarioAutor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereMotivoCambio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereNotaAnterior($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereNotaNueva($value)
 * @mixin \Eloquent
 */
class LogCambioNota extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'log_cambio_nota';
    protected $primaryKey = 'id_log_cambio_nota';

    protected $fillable = [
        'id_evaluacion',
        'id_usuario_autor',
        'nota_nueva',
        'nota_anterior',
        'motivo_cambio',
        'fecha_cambio',
    ];

    protected $casts = [
        'fecha_cambio' => 'datetime',
        'nota_nueva' => 'decimal:2',
        'nota_anterior' => 'decimal:2',
    ];

    public function evaluacion(): BelongsTo
    {
        return $this->belongsTo(Evaluacion::class, 'id_evaluacion', 'id_evaluacion');
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_autor', 'id_usuario');
    }
}
