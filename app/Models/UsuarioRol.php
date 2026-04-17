<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id_usuario_rol
 * @property int|null $id_usuario
 * @property int|null $id_rol
 * @property int|null $id_olimpiada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Olimpiada|null $olimpiada
 * @property-read \App\Models\Rol|null $rol
 * @property-read \App\Models\Usuario|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereIdOlimpiada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereIdRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereIdUsuarioRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UsuarioRol extends Pivot
{
    use HasFactory;

    protected $table = 'usuario_rol';
    protected $primaryKey = 'id_usuario_rol';
    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'id_rol',
        'id_olimpiada',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    public function olimpiada(): BelongsTo
    {
        return $this->belongsTo(Olimpiada::class, 'id_olimpiada');
    }
}
