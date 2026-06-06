<?php

namespace Tests\Feature\Evaluacion;

use App\Models\Competidor;
use App\Models\DescalificacionAdministrativa;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DescalificacionIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    private function usuario(): Usuario
    {
        return Usuario::factory()->create();
    }

    public function test_listar_descalificados_devuelve_estructura_correcta(): void
    {
        $this->actingAs($this->usuario(), 'sanctum')
            ->getJson('/api/v1/descalificados')
            ->assertOk()
            ->assertJsonStructure(['message', 'cantidad', 'data']);
    }

    public function test_listar_descalificados_sin_auth_da_401(): void
    {
        $this->getJson('/api/v1/descalificados')
            ->assertUnauthorized();
    }

    public function test_descalificar_competidor_guarda_en_bd(): void
    {
        $competidor = Competidor::factory()->create();

        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/descalificados', [
                'id_competidor' => $competidor->id_competidor,
                'observaciones' => 'Conducta inapropiada durante el examen.',
            ]);

        $respuesta->assertCreated()
            ->assertJsonPath('mensaje', 'Competidor descalificado administrativamente con éxito.');

        $this->assertDatabaseHas('descalificacion_administrativa', [
            'id_competidor' => $competidor->id_competidor,
            'observaciones' => 'Conducta inapropiada durante el examen.',
        ]);
    }

    public function test_descalificar_competidor_inexistente_da_422(): void
    {
        $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/descalificados', [
                'id_competidor' => 999999,
                'observaciones' => 'Motivo cualquiera',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['id_competidor']);
    }

    public function test_descalificar_sin_observaciones_da_422(): void
    {
        $competidor = Competidor::factory()->create();

        $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/descalificados', [
                'id_competidor' => $competidor->id_competidor,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['observaciones']);
    }

    public function test_lista_incluye_descalificacion_recien_creada(): void
    {
        $competidor = Competidor::factory()->create();
        DescalificacionAdministrativa::create([
            'id_competidor'          => $competidor->id_competidor,
            'observaciones'          => 'Uso de material no permitido.',
            'fecha_descalificacion'  => now(),
        ]);

        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->getJson('/api/v1/descalificados');

        $respuesta->assertOk();
        $this->assertGreaterThanOrEqual(1, $respuesta->json('cantidad'));
    }
}
