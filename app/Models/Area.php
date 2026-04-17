<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
