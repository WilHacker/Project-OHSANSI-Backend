<?php

namespace Tests\Feature\Olimpiada;

use App\Models\Olimpiada;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OlimpiadaIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    private function usuario(): Usuario
    {
        return Usuario::factory()->create();
    }

    public function test_crear_olimpiada_guarda_en_bd_y_devuelve_201(): void
    {
        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/olimpiadas', [
                'nombre'  => 'Olimpiada Test 2099',
                'gestion' => '2099',
            ]);

        $respuesta->assertCreated()
            ->assertJsonPath('datos.gestion', '2099')
            ->assertJsonPath('datos.activa', false);

        $this->assertDatabaseHas('olimpiada', [
            'nombre'  => 'Olimpiada Test 2099',
            'gestion' => '2099',
        ]);
    }

    public function test_crear_olimpiada_sin_campos_da_422(): void
    {
        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/olimpiadas', []);

        $respuesta->assertUnprocessable()
            ->assertJsonValidationErrors(['nombre', 'gestion']);
    }

    public function test_crear_olimpiada_duplicada_da_422(): void
    {
        Olimpiada::factory()->create(['nombre' => 'Olimpiada Duplicada', 'gestion' => '2088']);

        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/olimpiadas', [
                'nombre'  => 'Olimpiada Duplicada',
                'gestion' => '2088',
            ]);

        $respuesta->assertUnprocessable()
            ->assertJsonValidationErrors(['nombre']);
    }

    public function test_listar_olimpiadas_devuelve_estructura_correcta(): void
    {
        Olimpiada::factory()->count(3)->create();

        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->getJson('/api/v1/olimpiadas');

        $respuesta->assertOk()
            ->assertJsonStructure([
                'mensaje',
                'datos' => [['id', 'nombre', 'gestion', 'activa']],
            ]);
    }

    public function test_activar_olimpiada_cambia_estado_en_bd(): void
    {
        $olimpiada = Olimpiada::factory()->create(['estado' => false]);

        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->patchJson("/api/v1/olimpiadas/{$olimpiada->id_olimpiada}/activar");

        $respuesta->assertOk()
            ->assertJsonPath('datos.activa', true);

        $this->assertDatabaseHas('olimpiada', [
            'id_olimpiada' => $olimpiada->id_olimpiada,
            'estado'       => true,
        ]);
    }

    public function test_activar_olimpiada_desactiva_las_anteriores(): void
    {
        $olimpiada1 = Olimpiada::factory()->create(['estado' => true]);
        $olimpiada2 = Olimpiada::factory()->create(['estado' => false]);

        $this->actingAs($this->usuario(), 'sanctum')
            ->patchJson("/api/v1/olimpiadas/{$olimpiada2->id_olimpiada}/activar")
            ->assertOk();

        $this->assertDatabaseHas('olimpiada', [
            'id_olimpiada' => $olimpiada1->id_olimpiada,
            'estado'       => false,
        ]);
        $this->assertDatabaseHas('olimpiada', [
            'id_olimpiada' => $olimpiada2->id_olimpiada,
            'estado'       => true,
        ]);
    }

    public function test_activar_olimpiada_inexistente_da_404(): void
    {
        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->patchJson('/api/v1/olimpiadas/99999/activar');

        $respuesta->assertNotFound();
    }
}
