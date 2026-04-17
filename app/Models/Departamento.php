<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamento';
    protected $primaryKey = 'id_departamento';
    public $timestamps = true;

    protected $fillable = ['nombre'];

    public function competidores()
    {
        return $this->hasMany(Competidor::class, 'id_departamento');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('catalogo_departamentos'));
        static::deleted(fn () => Cache::forget('catalogo_departamentos'));
    }
}
