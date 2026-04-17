<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'nivel';
    protected $primaryKey = 'id_nivel';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
    ];

    public function areaNiveles()
    {
        return $this->hasMany(AreaNivel::class, 'id_nivel');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('catalogo_niveles'));
        static::deleted(fn () => Cache::forget('catalogo_niveles'));
    }
}
