<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_archivo_csv
 * @property string $nombre
 * @property \Illuminate\Support\Carbon $fecha
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competidor> $competidores
 * @property-read int|null $competidores_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv whereIdArchivoCsv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArchivoCsv extends Model
{
    use HasFactory;

    protected $table = 'archivo_csv';
    protected $primaryKey = 'id_archivo_csv';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function competidores()
    {
        return $this->hasMany(Competidor::class, 'id_archivo_csv', 'id_archivo_csv');
    }
}
