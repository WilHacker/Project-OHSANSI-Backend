<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaseGlobal extends Model
{
    use HasFactory;

    protected $table = 'fase_global';
    protected $primaryKey = 'id_fase_global';

    protected $fillable = [
        'id_olimpiada',
        'codigo',
        'nombre',
        'orden',
    ];

    protected $casts = [
        'orden'        => 'integer',
        'id_olimpiada' => 'integer',
    ];

    public function olimpiada()
    {
        return $this->belongsTo(Olimpiada::class, 'id_olimpiada', 'id_olimpiada');
    }

    public function cronograma()
    {
        return $this->hasOne(CronogramaFase::class, 'id_fase_global', 'id_fase_global');
    }

    public function configuraciones()
    {
        return $this->hasMany(ConfiguracionAccion::class, 'id_fase_global', 'id_fase_global');
    }

    public function competencias()
    {
        return $this->hasMany(Competencia::class, 'id_fase_global', 'id_fase_global');
    }
}
