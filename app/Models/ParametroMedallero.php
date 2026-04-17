<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_param_medallero
 * @property int|null $id_area_nivel
 * @property int|null $oro
 * @property int|null $plata
 * @property int|null $bronce
 * @property int|null $mencion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AreaNivel|null $areaNivel
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereBronce($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereIdAreaNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereIdParamMedallero($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereMencion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereOro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero wherePlata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ParametroMedallero extends Model
{
    use HasFactory;

    protected $table = 'param_medallero';
    protected $primaryKey = 'id_param_medallero';
    public $timestamps = true;

    protected $fillable = [
        'id_area_nivel',
        'oro',
        'plata',
        'bronce',
        'mencion',
    ];

    protected $casts = [
        'oro'     => 'integer',
        'plata'   => 'integer',
        'bronce'  => 'integer',
        'mencion' => 'integer',
    ];

    public function areaNivel()
    {
        return $this->belongsTo(AreaNivel::class, 'id_area_nivel', 'id_area_nivel');
    }
}