<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportarRequest;
use App\Jobs\ProcesarImportacionCsvJob;
use App\Services\CompetidorService;
use App\Models\ArchivoCsv;
use App\Models\Olimpiada;
use App\Exceptions\Dominio\RecursoNoEncontradoException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ImportarcsvController extends Controller
{
    public function __construct(
        protected CompetidorService $competidorService
    ) {}

    public function importar(ImportarRequest $request, string $gestion): JsonResponse
    {
        $olimpiada = Olimpiada::where('gestion', $gestion)->first();

        if (!$olimpiada) {
            throw new RecursoNoEncontradoException("La olimpiada de gestión '{$gestion}' no existe.");
        }

        $nombreArchivo      = Str::upper(Str::ascii(trim($request->input('nombre_archivo'))));
        $competidoresData   = $request->input('competidores');
        $umbralAsync        = config('ohsansi.importacion.max_competidores_por_request', 1000);

        $archivoCsv = ArchivoCsv::create([
            'nombre' => $nombreArchivo,
            'fecha'  => now(),
        ]);

        // Si supera el umbral, procesar en background y retornar inmediatamente.
        if (count($competidoresData) > $umbralAsync) {
            ProcesarImportacionCsvJob::dispatch(
                $competidoresData,
                $olimpiada->id_olimpiada,
                $archivoCsv->id_archivo_csv,
                auth()->id()
            );

            return response()->json([
                'mensaje' => 'Archivo recibido. El procesamiento se ejecutará en segundo plano.',
                'datos'   => [
                    'archivo' => [
                        'id'     => $archivoCsv->id_archivo_csv,
                        'nombre' => $archivoCsv->nombre,
                    ],
                    'total_enviados' => count($competidoresData),
                ],
            ], 202);
        }

        // Procesamiento síncrono para lotes pequeños.
        try {
            $resultados = $this->competidorService->procesarImportacion(
                $competidoresData,
                $olimpiada->id_olimpiada,
                $archivoCsv->id_archivo_csv
            );
        } catch (Throwable $e) {
            $archivoCsv->delete();

            Log::error('Error Importación CSV', [
                'gestion'   => $gestion,
                'archivo'   => $nombreArchivo,
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return response()->json([
                'mensaje' => 'Error crítico al procesar el archivo. Contacte al administrador.',
            ], 500);
        }

        $registrados = $resultados['registrados'];
        $duplicados  = $resultados['duplicados'];
        $errores     = $resultados['errores'];

        $reporteDuplicados = array_map(fn ($item) => [
            'nombre_completo' => $item['persona']['nombre'] . ' ' . $item['persona']['apellido'],
            'ci'              => $item['persona']['ci'],
            'motivo'          => 'Ya inscrito en ' . ($item['origen_duplicado'] ?? 'otra lista'),
        ], $duplicados);

        $datos = [
            'resumen' => [
                'total_procesados'  => count($registrados) + count($duplicados) + count($errores),
                'total_registrados' => count($registrados),
                'total_duplicados'  => count($duplicados),
                'total_errores'     => count($errores),
            ],
            'archivo' => [
                'id'     => $archivoCsv->id_archivo_csv,
                'nombre' => $archivoCsv->nombre,
            ],
            'competidores_creados' => array_map(fn ($r) => [
                'nombre_completo' => $r['persona']->nombre . ' ' . $r['persona']->apellido,
                'ci'              => $r['persona']->ci,
                'estado'          => $r['tipo'],
                'area'            => $r['area'],
                'nivel'           => $r['nivel'],
                'institucion'     => $r['institucion'],
            ], $registrados),
        ];

        if (count($duplicados) > 0) {
            $datos['detalles_duplicados'] = $reporteDuplicados;
        }
        if (count($errores) > 0) {
            $datos['detalles_errores'] = $errores;
        }

        return response()->json([
            'mensaje' => 'Proceso de importación finalizado.',
            'datos'   => $datos,
        ], 201);
    }
}
