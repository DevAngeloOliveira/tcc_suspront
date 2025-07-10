<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Atendente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AtendenteControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Testa se um usuário não autenticado é redirecionado para o login.
     */
    public function test_index_redirects_unauthenticated_user(): void
    {
        $response = $this->get('/atendentes');
        $response->assertRedirect('/login');
    }

    /**
     * Testa se um usuário comum não admin não pode acessar a lista de atendentes.
     */
    public function test_index_denies_non_admin_user(): void
    {
        // Criar um usuário atendente
        $user = User::factory()->create(['tipo' => 'atendente']);

        // Tentar acessar como o usuário atendente
        $response = $this->actingAs($user)->get('/atendentes');

        // O AdminMiddleware retorna um erro 403 para usuários não autorizados
        $response->assertStatus(403);
    }

    /**
     * Testa se um admin pode acessar a lista de atendentes.
     */
    public function test_index_allows_admin_user(): void
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Acessar como o usuário admin
        $response = $this->actingAs($user)->get('/atendentes');

        // Deve mostrar a página corretamente
        $response->assertStatus(200);
        $response->assertViewIs('atendentes.index');
    }

    /**
     * Testa a criação de um novo atendente por um admin.
     */
    public function test_store_creates_atendente_as_admin(): void
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Dados para criação do atendente
        $data = [
            'nome' => $this->faker->name,
            'registro' => $this->faker->unique()->numerify('REG-####'),
            'cpf' => $this->faker->numerify('###.###.###-##'),
            'telefone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        // Enviar requisição como o usuário admin
        $response = $this->actingAs($user)->post('/atendentes', $data);

        // Verificar se foi criado e redirecionado
        $response->assertRedirect('/atendentes');
        $response->assertSessionHas('success', 'Atendente cadastrado com sucesso!');

        // Verificar se existe no banco de dados
        $this->assertDatabaseHas('atendentes', [
            'nome' => $data['nome'],
            'registro' => $data['registro'],
            'cpf' => $data['cpf'],
        ]);

        // Verificar se o usuário foi criado
        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'tipo' => 'atendente'
        ]);
    }

    /**
     * Testa a atualização de um atendente por um admin.
     */
    public function test_update_atendente_as_admin(): void
    {
        // Criar um usuário admin
        $adminUser = User::factory()->create(['tipo' => 'admin']);

        // Criar um usuário para o atendente
        $userAtendente = User::factory()->create([
            'name' => 'Atendente Original',
            'email' => 'atendente.original@example.com',
            'tipo' => 'atendente'
        ]);

        // Criar o atendente
        $atendente = Atendente::create([
            'nome' => 'Atendente Original',
            'registro' => 'REG-1234',
            'cpf' => '123.456.789-00',
            'telefone' => '(00) 90000-0000',
            'email' => 'atendente.original@example.com',
            'user_id' => $userAtendente->id
        ]);

        // Dados para atualização
        $updateData = [
            'nome' => 'Atendente Atualizado',
            'registro' => 'REG-5678',
            'cpf' => '987.654.321-00',
            'telefone' => '(00) 98888-8888',
            'email' => 'atendente.atualizado@example.com'
        ];

        // Enviar requisição como o usuário admin
        $response = $this->actingAs($adminUser)->put("/atendentes/{$atendente->id}", $updateData);

        // Verificar se foi atualizado e redirecionado
        $response->assertRedirect("/atendentes/{$atendente->id}");
        $response->assertSessionHas('success', 'Atendente atualizado com sucesso!');

        // Verificar se foi atualizado no banco de dados
        $this->assertDatabaseHas('atendentes', [
            'id' => $atendente->id,
            'nome' => $updateData['nome'],
            'registro' => $updateData['registro'],
            'cpf' => $updateData['cpf'],
        ]);
    }
}
