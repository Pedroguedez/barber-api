<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;


class WorkingHourTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_working_hour_returns_201()
    {
        // Arrange: criar um barbeiro
        $barbeiro = User::factory()->create([
            'level' => 'Barber'
        ]);
        $cliente = User::factory()->create([
            'level' => 'Admin',
            'telefone' => '11999999999',
            'active' => true,
        ]);
        Sanctum::actingAs($cliente);
        $response = $this->postJson('/api/horariosFuncionamento', [
            'employee_id' => $barbeiro->id,
            'weekday' => 'Monday',
            'opening_morning' => '08:00',
            'closing_morning' => '12:00',
            'late_opening' => '13:00',
            'late_closing' => '18:00',
        ]);

        // Assert: verificar resposta
        $response->assertStatus(201)
                 ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'employee_id',
                        'weekday',
                        'opening_morning',
                        'closing_morning',
                        'late_opening',
                        'late_closing',
                        'created_at',
                        'updated_at'
                    ]
                 ]);
    }
}
