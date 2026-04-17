<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_grupo
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competidor> $competidores
 * @property-read int|null $competidores_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GrupoCompetidor> $grupoCompetidores
 * @property-read int|null $grupo_competidores_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereIdGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupo';
    protected $primaryKey = 'id_grupo';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
    ];

    public function competidores()
    {
        return $this->belongsToMany(Competidor::class, 'grupo_competidor', 'id_grupo', 'id_competidor')
                    ->withTimestamps();
    }

    public function grupoCompetidores()
    {
        return $this->hasMany(GrupoCompetidor::class, 'id_grupo');
    }
}
