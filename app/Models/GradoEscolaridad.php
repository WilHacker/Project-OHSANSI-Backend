<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id_grado_escolaridad
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AreaNivel> $areaNiveles
 * @property-read int|null $area_niveles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competidor> $competidores
 * @property-read int|null $competidores_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad whereIdGradoEscolaridad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GradoEscolaridad extends Model
{
    use HasFactory;

    protected $table = 'grado_escolaridad';
    protected $primaryKey = 'id_grado_escolaridad';
    public $timestamps = true;

    protected $fillable = ['nombre'];

    public function areaNiveles()
    {
        return $this->belongsToMany(
            AreaNivel::class,
            'area_nivel_grado',
            'id_grado_escolaridad',
            'id_area_nivel'
        );
    }

    public function competidores()
    {
        return $this->hasMany(Competidor::class, 'id_grado_escolaridad');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('catalogo_grados'));
        static::deleted(fn () => Cache::forget('catalogo_grados'));
    }
}
