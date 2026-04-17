<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id_olimpiada
 * @property string|null $nombre
 * @property string $gestion
 * @property bool $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AreaOlimpiada> $areaOlimpiadas
 * @property-read int|null $area_olimpiadas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Area> $areas
 * @property-read int|null $areas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FaseGlobal> $faseGlobales
 * @property-read int|null $fase_globales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UsuarioRol> $usuarioRoles
 * @property-read int|null $usuario_roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada whereGestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada whereIdOlimpiada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Olimpiada extends Model
{
    use HasFactory;

    protected $table = 'olimpiada';
    protected $primaryKey = 'id_olimpiada';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'gestion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(Area::class, 'area_olimpiada', 'id_olimpiada', 'id_area')
                    ->withTimestamps();
    }

    public function areaOlimpiadas(): HasMany
    {
        return $this->hasMany(AreaOlimpiada::class, 'id_olimpiada', 'id_olimpiada');
    }

    public function faseGlobales(): HasMany
    {
        return $this->hasMany(FaseGlobal::class, 'id_olimpiada', 'id_olimpiada');
    }

    public function usuarioRoles(): HasMany
    {
        return $this->hasMany(UsuarioRol::class, 'id_olimpiada', 'id_olimpiada');
    }
}
