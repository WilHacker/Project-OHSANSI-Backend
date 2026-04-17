<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Area extends Model
{
    use HasFactory;

    protected $table = 'area';
    protected $primaryKey = 'id_area';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
    ];

    public function olimpiadas()
    {
        return $this->belongsToMany(Olimpiada::class, 'area_olimpiada', 'id_area', 'id_olimpiada')
                    ->withTimestamps();
    }

    public function areaOlimpiadas()
    {
        return $this->hasMany(AreaOlimpiada::class, 'id_area', 'id_area');
    }

    protected static function booted(): void
    {
        // Se ejecuta al Crear o Actualizar
        static::saved(function () {
            Cache::forget('catalogo_areas');
        });

        // Se ejecuta al Eliminar
        static::deleted(function () {
            Cache::forget('catalogo_areas');
        });
    }
}
