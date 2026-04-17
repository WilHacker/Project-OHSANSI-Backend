<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id_institucion
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competidor> $competidores
 * @property-read int|null $competidores_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion whereIdInstitucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Institucion extends Model
{
    use HasFactory;

    protected $table = 'institucion';
    protected $primaryKey = 'id_institucion';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
    ];

    public function competidores()
    {
        return $this->hasMany(Competidor::class, 'id_institucion', 'id_institucion');
    }

    protected   static function booted(): void
    {
        // Se ejecuta al Crear o Actualizar
        static::saved(function () {
            Cache::forget('catalogo_instituciones');
        });

        // Se ejecuta al Eliminar
        static::deleted(function () {
            Cache::forget('catalogo_instituciones');
        });
    }
}
