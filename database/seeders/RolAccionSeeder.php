<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RolAccion;
use App\Models\AccionSistema;
use App\Models\Rol;

class RolAccionSeeder extends Seeder
{
    public function run(): void
    {
        $rolAdmin = Rol::where('nombre', 'Administrador')->first();
        $accionesSistema = AccionSistema::all();

        if ($rolAdmin) {
            foreach ($accionesSistema as $accion) {
                RolAccion::updateOrCreate(
                    [
                        'id_rol' => $rolAdmin->id_rol,
                        'id_accion_sistema' => $accion->id_accion_sistema
                    ],
                    [
                        'activo' => true
                    ]
                );
            }
        }
    }
}
