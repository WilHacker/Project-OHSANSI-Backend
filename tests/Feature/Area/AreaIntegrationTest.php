<?php

namespace Tests\Feature\Area;

use App\Models\Area;
use App\Models\Nivel;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AreaIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    private function usuario(): Usuario
    {
        return Usuario::factory()->create();
    }

    // ─── Area ─────────────────────────────────────────────────────────────────

    public function test_crear_area_guarda_en_bd(): void
    {
        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/area', ['nombre' => 'Matemáticas Avanzadas']);

        $respuesta->assertCreated()
            ->assertJsonPath('datos.nombre', 'Matemáticas Avanzadas');

        $this->assertDatabaseHas('area', ['nombre' => 'Matemáticas Avanzadas']);
    }

    public function test_crear_area_sin_nombre_da_422(): void
    {
        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/area', []);

        $respuesta->assertUnprocessable()
            ->assertJsonValidationErrors(['nombre']);
    }

    public function test_crear_area_duplicada_da_422(): void
    {
        Area::factory()->create(['nombre' => 'Física Clásica']);

        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/area', ['nombre' => 'Física Clásica']);

        $respuesta->assertUnprocessable()
            ->assertJsonValidationErrors(['nombre']);
    }

    public function test_listar_areas_devuelve_200(): void
    {
        Area::factory()->count(2)->create();

        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->getJson('/api/v1/area');

        $respuesta->assertOk();
    }

    // ─── Nivel ────────────────────────────────────────────────────────────────

    public function test_crear_nivel_guarda_en_bd(): void
    {
        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/niveles', ['nombre' => 'Nivel Test Integración']);

        $respuesta->assertCreated()
            ->assertJsonPath('datos.nombre', 'Nivel Test Integración');

        $this->assertDatabaseHas('nivel', ['nombre' => 'Nivel Test Integración']);
    }

    public function test_crear_nivel_duplicado_da_422(): void
    {
        Nivel::factory()->create(['nombre' => 'Nivel Único']);

        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/niveles', ['nombre' => 'Nivel Único']);

        $respuesta->assertUnprocessable()
            ->assertJsonValidationErrors(['nombre']);
    }
}
