<?php

namespace Tests\Feature\Sistema;

use App\Models\Olimpiada;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SistemaEstadoIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_estado_sin_olimpiada_activa_devuelve_sin_gestion(): void
    {
        // Ensure no active olimpiada exists in this transaction
        Olimpiada::where('estado', true)->update(['estado' => false]);

        $respuesta = $this->getJson('/api/v1/sistema/estado');

        $respuesta->assertOk()
            ->assertJsonPath('status', 'sin_gestion');
    }

    public function test_estado_es_publico_sin_autenticacion(): void
    {
        $respuesta = $this->getJson('/api/v1/sistema/estado');

        $respuesta->assertOk();
    }

    public function test_estado_con_olimpiada_activa_devuelve_operativo(): void
    {
        Olimpiada::where('estado', true)->update(['estado' => false]);
        Olimpiada::factory()->activa()->create(['gestion' => '2099']);

        $respuesta = $this->getJson('/api/v1/sistema/estado');

        $respuesta->assertOk()
            ->assertJsonPath('status', 'operativo');
    }

    public function test_estado_estructura_basica(): void
    {
        $respuesta = $this->getJson('/api/v1/sistema/estado');

        $respuesta->assertOk()
            ->assertJsonStructure(['status', 'server_timestamp']);
    }
}
