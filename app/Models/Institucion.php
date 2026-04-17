<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
