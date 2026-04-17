<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id_persona
 * @property string $nombre
 * @property string $apellido
 * @property string $ci
 * @property string $telefono
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competidor> $competidores
 * @property-read int|null $competidores_count
 * @property-read \App\Models\Usuario|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereApellido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereCi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereIdPersona($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereTelefono($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Persona extends Model
{
    use HasFactory;

    protected $table = 'persona';
    protected $primaryKey = 'id_persona';
    public $timestamps = true;

    protected $fillable = [
        'nombre', 'apellido', 'ci', 'telefono', 'email'
    ];

    public function usuario(): HasOne
    {
        return $this->hasOne(Usuario::class, 'id_persona', 'id_persona');
    }

    public function competidores(): HasMany
    {
        return $this->hasMany(Competidor::class, 'id_persona', 'id_persona');
    }

}
