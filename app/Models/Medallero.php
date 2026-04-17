<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id_medallero
 * @property int|null $id_competidor
 * @property int|null $id_competencia
 * @property int $puesto
 * @property string $medalla
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Competencia|null $competencia
 * @property-read \App\Models\Competidor|null $competidor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero whereIdCompetencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero whereIdCompetidor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero whereIdMedallero($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero whereMedalla($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero wherePuesto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Medallero extends Model
{
    use HasFactory;

    protected $table = 'medallero';
    protected $primaryKey = 'id_medallero';
    public $timestamps = true;

    protected $fillable = [
        'id_competidor',
        'id_competencia',
        'puesto',
        'medalla',
    ];

    protected $casts = [
        'puesto' => 'integer',
    ];

    /**
     * Get the competidor that won the medal.
     */
    public function competidor(): BelongsTo
    {
        return $this->belongsTo(Competidor::class, 'id_competidor', 'id_competidor');
    }

    /**
     * Get the competencia for the medal.
     */
    public function competencia(): BelongsTo
    {
        return $this->belongsTo(Competencia::class, 'id_competencia', 'id_competencia');
    }
}
