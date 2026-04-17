<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_grupo_competidor
 * @property int|null $id_grupo
 * @property int|null $id_competidor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Competidor|null $competidor
 * @property-read \App\Models\Grupo|null $grupo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor whereIdCompetidor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor whereIdGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor whereIdGrupoCompetidor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GrupoCompetidor extends Model
{
    use HasFactory;

    protected $table = 'grupo_competidor';
    protected $primaryKey = 'id_grupo_competidor';
    public $timestamps = true;

    protected $fillable = [
        'id_grupo',
        'id_competidor',
    ];

    public function grupo() {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }

    public function competidor() {
        return $this->belongsTo(Competidor::class, 'id_competidor', 'id_competidor');
    }
}
