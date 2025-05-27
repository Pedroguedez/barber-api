<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'level' => 'Admin',
            'password' => Hash::make('password')
        ]);

        $this->regularUser = User::factory()->create([
            'level' => 'Client',
            'password' => Hash::make('password')
        ]);
    }

    public function test_admin_can_list_all_users(): void
    {
        Sanctum::actingAs($this->adminUser);
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/usuarios');

        $response->assertStatus(200)
                 ->assertJsonStructure(['users' => []])
                 ->assertJsonCount(5, 'users');
    }

    public function test_non_admin_cannot_list_all_users(): void
    {
        Sanctum::actingAs($this->regularUser);
        $response = $this->getJson('/api/usuarios');
        $response->assertStatus(403);
    }

    public function test_admin_can_create_user_successfully(): void
    {
        Sanctum::actingAs($this->adminUser);
        $userData = [
            'name' => 'Novo Usuário',
            'email' => 'novo@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefone' => '11987654321',
            'level' => 'Client',
            'active' => true,
        ];

        $response = $this->postJson('/api/usuarios', $userData);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'user' => ['id', 'name', 'email']])
                 ->assertJsonFragment(['name' => 'Novo Usuário']);
        $this->assertDatabaseHas('users', ['email' => 'novo@example.com']);
    }

    public function test_create_user_fails_with_validation_errors(): void
    {
        Sanctum::actingAs($this->adminUser);
        $userData = [ 'email' => 'invalido'];
        $response = $this->postJson('/api/usuarios', $userData);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'password', 'email']);
    }

    public function test_authenticated_user_can_show_existing_user(): void
    {
        Sanctum::actingAs($this->regularUser);
        $targetUser = User::factory()->create();

        $response = $this->getJson("/api/usuarios/{$targetUser->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['email' => $targetUser->email]);
    }

    public function test_show_user_returns_404_for_non_existent_user(): void
    {
        Sanctum::actingAs($this->regularUser);
        $response = $this->getJson('/api/usuarios/99999');
        $response->assertStatus(404);
    }

    public function test_admin_can_update_user_successfully(): void
    {
        Sanctum::actingAs($this->adminUser);
        $userToUpdate = User::factory()->create();
        $updateData = [
            'name' => 'Nome Atualizado',
            'email' => 'emailatualizado@example.com',
            'active' => false,
        ];

        $response = $this->putJson("/api/usuarios/{$userToUpdate->id}", $updateData);
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Nome Atualizado']);
        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'Nome Atualizado',
            'active' => false,
        ]);
    }

    public function test_admin_can_update_user_password_successfully(): void
    {
        Sanctum::actingAs($this->adminUser);
        $userToUpdate = User::factory()->create();
        $oldPasswordHash = $userToUpdate->password;
        $updateData = [
            'password' => 'novaSenha123',
            'password_confirmation' => 'novaSenha123',
            'name' => $userToUpdate->name,
            'email' => $userToUpdate->email,
            'telefone' => $userToUpdate->telefone,
            'level' => $userToUpdate->level,
            'active' => $userToUpdate->active,
        ];

        $response = $this->putJson("/api/usuarios/{$userToUpdate->id}", $updateData);
        $response->assertStatus(200);

        $updatedUser = User::find($userToUpdate->id);
        $this->assertTrue(Hash::check('novaSenha123', $updatedUser->password));
        $this->assertNotEquals($oldPasswordHash, $updatedUser->password);
    }

    public function test_update_user_returns_404_for_non_existent_user(): void
    {
        Sanctum::actingAs($this->adminUser);
        $response = $this->putJson('/api/usuario/99999', ['name' => 'Qualquer Nome']);
        $response->assertStatus(404);
    }

    public function test_admin_can_delete_user_successfully(): void
    {
        Sanctum::actingAs($this->adminUser);
        $userToDelete = User::factory()->create();

        $response = $this->deleteJson("/api/usuarios/{$userToDelete->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Usuário deletado com sucesso']);
        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    public function test_delete_user_returns_404_for_non_existent_user(): void
    {
        Sanctum::actingAs($this->adminUser);
        $response = $this->deleteJson('/api/usuarios/99999');
        $response->assertStatus(404);
    }

    public function test_can_get_list_of_barbers(): void
    {
        Sanctum::actingAs($this->regularUser); // Qualquer usuário logado pode ver os barbeiros
        User::factory()->count(2)->create(['level' => 'Barber']);
        User::factory()->create(['level' => 'Client']); // Um não-barbeiro

        $response = $this->getJson('/api/barbeiros');
        $response->assertStatus(200)
                 ->assertJsonStructure(['barbeiros' => [['id', 'name']]])
                 ->assertJsonCount(2, 'barbeiros');

        foreach ($response->json('barbeiros') as $barbeiro) {
            $dbUser = User::find($barbeiro['id']);
            $this->assertEquals('Barber', $dbUser->level);
        }
    }

    public function test_get_barbers_returns_empty_list_if_no_barbers(): void
    {
        Sanctum::actingAs($this->regularUser);
        User::factory()->create(['level' => 'Client']);

        $response = $this->getJson('/api/barbeiros');
        $response->assertStatus(200)
                 ->assertJsonCount(0, 'barbeiros');
    }
}