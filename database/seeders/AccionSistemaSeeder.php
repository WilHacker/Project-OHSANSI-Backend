<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccionSistema;

class AccionSistemaSeeder extends Seeder
{
    public function run(): void
    {
        $catalogoOficial = [
            // --- SECCIÓN: DASHBOARD ---
            [
                'codigo'      => 'DASHBOARD',
                'nombre'      => 'Dashboard',
                'descripcion' => 'Vista principal de métricas y accesos. Ruta: /dashboard',
            ],

            // --- SECCIÓN: GESTIÓN DE LA OLIMPIADA ---
            [
                'codigo'      => 'OLIMPIADAS',
                'nombre'      => 'Olimpiadas',
                'descripcion' => 'Gestión de eventos olímpicos. Ruta: /olimpiada',
            ],
            [
                'codigo'      => 'AREAS',
                'nombre'      => 'Áreas',
                'descripcion' => 'Administración de áreas de conocimiento. Ruta: /areas',
            ],
            [
                'codigo'      => 'NIVELES',
                'nombre'      => 'Niveles',
                'descripcion' => 'Configuración de grados y niveles. Ruta: /niveles',
            ],
            [
                'codigo'      => 'ASIGNACIONES',
                'nombre'      => 'Asignar Niveles a Áreas',
                'descripcion' => 'Vinculación matricial Área-Nivel. Ruta: /asignarNiveles',
            ],

            // --- SECCIÓN: GESTIÓN DE USUARIOS ---
            [
                'codigo'      => 'RESPONSABLES',
                'nombre'      => 'Responsables de Área',
                'descripcion' => 'Gestión de usuarios encargados de área. Ruta: /responsables',
            ],
            [
                'codigo'      => 'EVALUADORES',
                'nombre'      => 'Evaluadores',
                'descripcion' => 'Gestión de usuarios correctores. Ruta: /evaluadores',
            ],

            // --- SECCIÓN: GESTIÓN DE COMPETIDORES ---
            [
                'codigo'      => 'INSCRIPCION',
                'nombre'      => 'Registrar Competidores',
                'descripcion' => 'Carga masiva e importación de estudiantes. Ruta: /competidores',
            ],
            [
                'codigo'      => 'COMPETIDORES',
                'nombre'      => 'Lista de Competidores',
                'descripcion' => 'Listado general y búsqueda de inscritos. Ruta: /competidoresPage',
            ],

            // --- SECCIÓN: EVALUACIÓN Y CLASIFICACIÓN ---
            [
                'codigo'      => 'COMPETENCIAS',
                'nombre'      => 'Registrar Competencia',
                'descripcion' => 'Gestión operativa de competencias activas. Ruta: /competencias',
            ],
            [
                'codigo'      => 'EXAMENES',
                'nombre'      => 'Exámenes',
                'descripcion' => 'Banco de pruebas y archivos. Ruta: /examenes',
            ],
            [
                'codigo'      => 'SALA_EVALUACION',
                'nombre'      => 'Registrar Evaluación',
                'descripcion' => 'Sala de corrección para evaluadores. Ruta: /evaluaciones',
            ],
            [
                'codigo'      => 'PARAMETROS',
                'nombre'      => 'Parámetros de Clasificación',
                'descripcion' => 'Reglas de puntaje y clasificación. Ruta: /parametrosCalificaciones',
            ],
            [
                'codigo'      => 'MEDALLERO',
                'nombre'      => 'Parametrizar Medallero',
                'descripcion' => 'Configuración de rangos para medallas. Ruta: /medallero',
            ],

            // --- SECCIÓN: CONFIGURACIONES ---
            [
                'codigo'      => 'ACTIVIDADES_FASES',
                'nombre'      => 'Configuración de Actividades',
                'descripcion' => 'Reglas globales de las fases. Ruta: /configuracionFasesGlobales',
            ],
            [
                'codigo'      => 'GESTIONAR_ROLES',
                'nombre'      => 'Configuración de Permisos por Rol',
                'descripcion' => 'Gestión de roles y accesos del sistema. Ruta: /permisosRoles',
            ],
            [
                'codigo'      => 'CRONOGRAMA',
                'nombre'      => 'Configuración de Cronograma',
                'descripcion' => 'Línea de tiempo de actividades. Ruta: /cronograma',
            ],

            // --- SECCIÓN: REPORTES ---
            [
                'codigo'      => 'REPORTES_CAMBIOS',
                'nombre'      => 'Reporte de cambio de calificaciones',
                'descripcion' => 'Auditoría de modificaciones de notas. Ruta: /reportesCambiosCalificaciones',
            ]
        ];

        $this->command->info('🏛️  Cargando Catálogo Oficial de Secciones...');

        foreach ($catalogoOficial as $data) {
            AccionSistema::firstOrCreate(
                ['codigo' => $data['codigo']],
                [
                    'nombre'      => $data['nombre'],
                    'descripcion' => $data['descripcion']
                ]
            );
        }

        $this->command->info('✅ Catálogo cargado correctamente.');
    }
}
