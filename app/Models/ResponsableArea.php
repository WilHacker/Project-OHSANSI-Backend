<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id_responsable_area
 * @property int|null $id_usuario
 * @property int|null $id_area_olimpiada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AreaOlimpiada|null $areaOlimpiada
 * @property-read \App\Models\Usuario|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea whereIdAreaOlimpiada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea whereIdResponsableArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ResponsableArea extends Model
{
    use HasFactory;

    protected $table = 'responsable_area';
    protected $primaryKey = 'id_responsable_area';
    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'id_area_olimpiada',
    ];

    public function areaOlimpiada(): BelongsTo
    {
        return $this->belongsTo(AreaOlimpiada::class, 'id_area_olimpiada', 'id_area_olimpiada');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}