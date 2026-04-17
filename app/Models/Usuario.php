<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <--- VITAL para tu API

/**
 * @property int $id_usuario
 * @property int|null $id_persona
 * @property string $email
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EvaluadorAn> $evaluadoresAn
 * @property-read int|null $evaluadores_an_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Persona|null $persona
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResponsableArea> $responsableAreas
 * @property-read int|null $responsable_areas_count
 * @property-read \App\Models\UsuarioRol|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rol> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UsuarioRol> $usuarioRoles
 * @property-read int|null $usuario_roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereIdPersona($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Usuario extends Authenticatable
{
    // Agregamos HasApiTokens y Notifiable
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = true;

    protected $fillable = [
        'id_persona',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function usuarioRoles(): HasMany
    {
        return $this->hasMany(UsuarioRol::class, 'id_usuario');
    }

    public function responsableAreas(): HasMany
    {
        return $this->hasMany(ResponsableArea::class, 'id_usuario');
    }

    public function evaluadoresAn(): HasMany
    {
        return $this->hasMany(EvaluadorAn::class, 'id_usuario');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'usuario_rol', 'id_usuario', 'id_rol')
                    ->withPivot('id_olimpiada')
                    ->using(UsuarioRol::class)
                    ->withTimestamps();
    }
}
