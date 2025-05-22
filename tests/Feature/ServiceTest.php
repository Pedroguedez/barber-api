<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_service_returns_201()
    {
        $admin = User::factory()->create(['level' => 'Admin']);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/servicos', [
            'name' => 'Corte de cabelo',
            'price' => 50.00,
            'duration' => 30
        ]);

        $response->assertStatus(201);
    }

    public function test_list_services_returns_data()
    {
        $admin = User::factory()->create(['level' => 'Admin']);
        Sanctum::actingAs($admin);

        Service::factory()->count(3)->create();

        $response = $this->getJson('/api/servicos');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'Services');
    }

    public function test_update_service_returns_updated_data()
    {
        $admin = User::factory()->create(['level' => 'Admin']);
        Sanctum::actingAs($admin);
        $service = Service::factory()->create();

        $response = $this->putJson("/api/servicos/{$service->id}", [
            'name' => 'Corte atualizado',
            'price' => 60,
            'duration' => 40,
            'active' => false,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Corte atualizado']);
    }

    public function test_delete_service_returns_success_message()
    {
        $admin = User::factory()->create(['level' => 'Admin']);
        Sanctum::actingAs($admin);
        $service = Service::factory()->create();

        $response = $this->deleteJson("/api/servicos/{$service->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Serviço deletado com sucesso']);
    }

    public function test_show_nonexistent_service_returns_404()
    {
        $admin = User::factory()->create(['level' => 'Admin']);
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/servicos/9999');
        $response->assertStatus(404)
                 ->assertJson(['message' => 'Serviço não encontrado']);
    }
}
