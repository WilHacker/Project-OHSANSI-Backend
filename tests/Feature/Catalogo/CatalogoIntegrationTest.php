<?php

namespace Tests\Feature\Catalogo;

use App\Models\Departamento;
use App\Models\GradoEscolaridad;
use App\Models\Institucion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CatalogoIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    private function usuario(): Usuario
    {
        return Usuario::factory()->create();
    }

    // ─── Departamentos (GET público, resto requiere auth) ──────────────────────

    public function test_listar_departamentos_es_publico(): void
    {
        Departamento::factory()->create(['nombre' => 'La Paz Test']);

        $this->getJson('/api/v1/departamentos')
            ->assertOk();
    }

    public function test_crear_departamento_guarda_en_bd(): void
    {
        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/departamentos', ['nombre' => 'Oruro Test']);

        $respuesta->assertCreated();
        $this->assertDatabaseHas('departamento', ['nombre' => 'Oruro Test']);
    }

    public function test_crear_departamento_sin_auth_da_401(): void
    {
        $this->postJson('/api/v1/departamentos', ['nombre' => 'Sin Auth'])
            ->assertUnauthorized();
    }

    public function test_actualizar_departamento_modifica_en_bd(): void
    {
        $departamento = Departamento::factory()->create(['nombre' => 'Cochabamba Viejo']);

        $this->actingAs($this->usuario(), 'sanctum')
            ->putJson("/api/v1/departamentos/{$departamento->id_departamento}", ['nombre' => 'Cochabamba Nuevo'])
            ->assertOk();

        $this->assertDatabaseHas('departamento', [
            'id_departamento' => $departamento->id_departamento,
            'nombre'          => 'Cochabamba Nuevo',
        ]);
    }

    public function test_eliminar_departamento_lo_borra_de_bd(): void
    {
        $departamento = Departamento::factory()->create(['nombre' => 'Para Borrar Dept']);

        $this->actingAs($this->usuario(), 'sanctum')
            ->deleteJson("/api/v1/departamentos/{$departamento->id_departamento}")
            ->assertOk();

        $this->assertDatabaseMissing('departamento', [
            'id_departamento' => $departamento->id_departamento,
        ]);
    }

    // ─── Instituciones (GET público, resto requiere auth) ──────────────────────

    public function test_listar_instituciones_es_publico(): void
    {
        Institucion::factory()->create(['nombre' => 'Colegio Test']);

        $this->getJson('/api/v1/instituciones')
            ->assertOk();
    }

    public function test_crear_institucion_guarda_en_bd(): void
    {
        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/instituciones', ['nombre' => 'Unidad Educativa Integración']);

        $respuesta->assertCreated();
        $this->assertDatabaseHas('institucion', ['nombre' => 'Unidad Educativa Integración']);
    }

    public function test_actualizar_institucion_modifica_en_bd(): void
    {
        $institucion = Institucion::factory()->create(['nombre' => 'Colegio Viejo']);

        $this->actingAs($this->usuario(), 'sanctum')
            ->putJson("/api/v1/instituciones/{$institucion->id_institucion}", ['nombre' => 'Colegio Nuevo'])
            ->assertOk();

        $this->assertDatabaseHas('institucion', [
            'id_institucion' => $institucion->id_institucion,
            'nombre'         => 'Colegio Nuevo',
        ]);
    }

    public function test_eliminar_institucion_la_borra_de_bd(): void
    {
        $institucion = Institucion::factory()->create(['nombre' => 'Para Borrar Inst']);

        $this->actingAs($this->usuario(), 'sanctum')
            ->deleteJson("/api/v1/instituciones/{$institucion->id_institucion}")
            ->assertOk();

        $this->assertDatabaseMissing('institucion', [
            'id_institucion' => $institucion->id_institucion,
        ]);
    }

    // ─── Grados de Escolaridad (requiere auth) ─────────────────────────────────

    public function test_listar_grados_devuelve_200(): void
    {
        GradoEscolaridad::factory()->create(['nombre' => 'Grado Test Integración']);

        $this->actingAs($this->usuario(), 'sanctum')
            ->getJson('/api/v1/grados-escolaridad')
            ->assertOk();
    }

    public function test_crear_grado_guarda_en_bd(): void
    {
        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/grados-escolaridad', ['nombre' => 'Sexto de Secundaria Test']);

        $respuesta->assertCreated();
        $this->assertDatabaseHas('grado_escolaridad', ['nombre' => 'Sexto de Secundaria Test']);
    }

    public function test_crear_grado_sin_nombre_da_422(): void
    {
        $this->actingAs($this->usuario(), 'sanctum')
            ->postJson('/api/v1/grados-escolaridad', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nombre']);
    }

    public function test_ver_grado_especifico_devuelve_datos(): void
    {
        $grado = GradoEscolaridad::factory()->create(['nombre' => 'Primero Primaria Test']);

        $respuesta = $this->actingAs($this->usuario(), 'sanctum')
            ->getJson("/api/v1/grados-escolaridad/{$grado->id_grado_escolaridad}");

        $respuesta->assertOk();
    }

    public function test_eliminar_grado_lo_borra_de_bd(): void
    {
        $grado = GradoEscolaridad::factory()->create(['nombre' => 'Para Borrar Grado']);

        $this->actingAs($this->usuario(), 'sanctum')
            ->deleteJson("/api/v1/grados-escolaridad/{$grado->id_grado_escolaridad}")
            ->assertOk();

        $this->assertDatabaseMissing('grado_escolaridad', [
            'id_grado_escolaridad' => $grado->id_grado_escolaridad,
        ]);
    }
}
