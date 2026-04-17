<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
