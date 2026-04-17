<?php

namespace App\Jobs;

use App\Models\ArchivoCsv;
use App\Services\CompetidorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcesarImportacionCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Tiempo máximo de ejecución del job en segundos.
     * Controla el timeout a nivel de worker, no de PHP runtime.
     */
    public int $timeout = 300;

    /**
     * Reintentos si el job falla.
     */
    public int $tries = 1;

    public function __construct(
        private readonly array $competidoresData,
        private readonly int $olimpiadaId,
        private readonly int $archivoCsvId,
        private readonly int $usuarioId
    ) {}

    public function handle(CompetidorService $competidorService): void
    {
        try {
            $resultados = $competidorService->procesarImportacion(
                $this->competidoresData,
                $this->olimpiadaId,
                $this->archivoCsvId
            );

            Log::info('Importación CSV completada via Job', [
                'archivo_csv_id' => $this->archivoCsvId,
                'olimpiada_id'   => $this->olimpiadaId,
                'usuario_id'     => $this->usuarioId,
                'registrados'    => count($resultados['registrados']),
                'duplicados'     => count($resultados['duplicados']),
                'errores'        => count($resultados['errores']),
            ]);

        } catch (Throwable $e) {
            ArchivoCsv::find($this->archivoCsvId)?->delete();

            Log::error('Error en ProcesarImportacionCsvJob', [
                'archivo_csv_id' => $this->archivoCsvId,
                'olimpiada_id'   => $this->olimpiadaId,
                'usuario_id'     => $this->usuarioId,
                'exception'      => $e->getMessage(),
                'trace'          => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function failed(Throwable $e): void
    {
        Log::error('Job ProcesarImportacionCsvJob falló definitivamente', [
            'archivo_csv_id' => $this->archivoCsvId,
            'exception'      => $e->getMessage(),
        ]);
    }
}
