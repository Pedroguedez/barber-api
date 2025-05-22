<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_agendamento_retorna_status_201(): void
    {
        // Criar um usuário de teste
        $cliente = User::factory()->create([
            'level' => 'Admin',
            'telefone' => '11999999999',
            'active' => true,
        ]);
        $barbeiro = User::factory()->create([
            'level' => 'Barber',
            'telefone' => '11889999999',
            'active' => true,
        ]);

        $servico = Service::factory()->create();
        // Simular o login do usuário
        Sanctum::actingAs($cliente);

        // Fazer a requisição autenticada
        $response = $this->postJson('/api/agendamentos', [
            'client_id' => $cliente->id,
            'barber_id' => $barbeiro->id,
            'service_id' => $servico->id,
            'data' => '2025-06-01',
            'time' => '14:00',
            'status' => 'pending',
            'notes' => 'Corte e barba'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure(['data']);
        }
}
