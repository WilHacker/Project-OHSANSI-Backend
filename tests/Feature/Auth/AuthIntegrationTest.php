<?php

namespace Tests\Feature\Auth;

use App\Models\Persona;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_con_usuario_real_retorna_token(): void
    {
        $persona = Persona::factory()->create();
        $usuario = Usuario::factory()->create([
            'id_persona' => $persona->id_persona,
            'email'      => 'juez.test@ohsansi.bo',
            'password'   => Hash::make('secreto123'),
        ]);

        $respuesta = $this->postJson('/api/v1/auth/login', [
            'email'    => 'juez.test@ohsansi.bo',
            'password' => 'secreto123',
        ]);

        $respuesta->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'user'])
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('user.email', 'juez.test@ohsansi.bo');
    }

    public function test_login_con_password_incorrecta_da_401(): void
    {
        $persona = Persona::factory()->create();
        Usuario::factory()->create([
            'id_persona' => $persona->id_persona,
            'email'      => 'otro.test@ohsansi.bo',
            'password'   => Hash::make('correcta'),
        ]);

        $respuesta = $this->postJson('/api/v1/auth/login', [
            'email'    => 'otro.test@ohsansi.bo',
            'password' => 'equivocada',
        ]);

        $respuesta->assertUnauthorized();
    }

    public function test_login_usuario_inexistente_da_401(): void
    {
        $respuesta = $this->postJson('/api/v1/auth/login', [
            'email'    => 'noexiste@ohsansi.bo',
            'password' => 'cualquiera',
        ]);

        $respuesta->assertUnauthorized();
    }

    public function test_me_retorna_usuario_autenticado(): void
    {
        $usuario = Usuario::factory()->create();

        $respuesta = $this->actingAs($usuario, 'sanctum')
            ->getJson('/api/v1/auth/me');

        $respuesta->assertOk()
            ->assertJsonPath('user.email', $usuario->email);
    }
}
