<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;


class AgendamentoControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_store_agendamento_retorna_status_201(): void
    {
        // Criar um usuário de teste
        $cliente = User::factory()->create([
            'nivel' => 'Administrador',
            'telefone' => '11999999999',
            'ativo' => true,
        ]);
        $barbeiro = User::factory()->create([
            'nivel' => 'Barbeiro',
            'telefone' => '11889999999',
            'ativo' => true,
        ]);
        // Simular o login do usuário
        Sanctum::actingAs($cliente);

        // Fazer a requisição autenticada
        $response = $this->postJson('/api/agendamentos', [
            'cliente_id' => $cliente->id,
            'barbeiro_id' => $barbeiro->id,
            'data' => '2025-06-01',
            'horario' => '14:00',
            'status' => 'pendente',
            'observacao' => 'Corte e barba'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure(['message', 'data']);
        }
}
