<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Paciente;

class PacienteControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de listagem de pacientes.
     */
    public function test_index_displays_pacientes()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar alguns pacientes de teste
        Paciente::create([
            'nome' => 'Teste Paciente',
            'cpf' => '123.456.789-00',
            'cartao_sus' => '123456789012345',
            'data_nascimento' => '1990-01-01',
            'sexo' => 'M',
            'endereco' => 'Rua Teste, 123',
            'telefone' => '(11) 99999-8888',
            'email' => 'teste@example.com'
        ]);

        // Acessar a página de listagem de pacientes com usuário autenticado
        $response = $this->actingAs($user)->get(route('pacientes.index'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se o paciente criado está presente na listagem
        $response->assertSee('Teste Paciente');
        $response->assertSee('123.456.789-00');
    }

    /**
     * Teste de criação de paciente.
     */
    public function test_store_creates_new_paciente()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Dados para criar um novo paciente
        $pacienteData = [
            'nome' => 'Novo Paciente',
            'cpf' => '987.654.321-00',
            'cartao_sus' => '543210987654321',
            'data_nascimento' => '1985-05-15',
            'sexo' => 'F',
            'endereco' => 'Av. Teste, 456',
            'telefone' => '(11) 98888-7777',
            'email' => 'novo@example.com'
        ];

        // Enviar requisição POST para criar paciente
        $response = $this->actingAs($user)->post(route('pacientes.store'), $pacienteData);

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect();

        // Verificar se o paciente foi criado no banco de dados
        $this->assertDatabaseHas('pacientes', [
            'nome' => 'Novo Paciente',
            'cpf' => '987.654.321-00',
            'cartao_sus' => '543210987654321'
        ]);
    }

    /**
     * Teste de exibição de detalhes de um paciente.
     */
    public function test_show_displays_paciente()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um paciente
        $paciente = Paciente::create([
            'nome' => 'Detalhes Paciente',
            'cpf' => '555.666.777-88',
            'cartao_sus' => '555666777888999',
            'data_nascimento' => '1980-10-20',
            'sexo' => 'M',
            'endereco' => 'Rua Detalhe, 789',
            'telefone' => '(11) 97777-6666',
            'email' => 'detalhes@example.com'
        ]);

        // Acessar página de detalhes do paciente
        $response = $this->actingAs($user)->get(route('pacientes.show', $paciente->id));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se os detalhes do paciente são exibidos
        $response->assertSee('Detalhes Paciente');
        $response->assertSee('555.666.777-88');
        $response->assertSee('555666777888999');
    }

    /**
     * Teste de atualização de paciente.
     */
    public function test_update_paciente()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um paciente para ser atualizado
        $paciente = Paciente::create([
            'nome' => 'Paciente Original',
            'cpf' => '111.222.333-44',
            'cartao_sus' => '111222333444555',
            'data_nascimento' => '1975-03-10',
            'sexo' => 'M',
            'endereco' => 'Rua Original, 100',
            'telefone' => '(11) 96666-5555',
            'email' => 'original@example.com'
        ]);

        // Dados atualizados
        $dadosAtualizados = [
            'nome' => 'Paciente Atualizado',
            'cpf' => '111.222.333-44',
            'cartao_sus' => '111222333444555',
            'data_nascimento' => '1975-03-10',
            'sexo' => 'M',
            'endereco' => 'Rua Atualizada, 200',
            'telefone' => '(11) 95555-4444',
            'email' => 'atualizado@example.com'
        ];

        // Enviar requisição PUT para atualizar o paciente
        $response = $this->actingAs($user)->put(route('pacientes.update', $paciente->id), $dadosAtualizados);

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect();

        // Verificar se os dados foram atualizados no banco de dados
        $this->assertDatabaseHas('pacientes', [
            'id' => $paciente->id,
            'nome' => 'Paciente Atualizado',
            'endereco' => 'Rua Atualizada, 200',
            'email' => 'atualizado@example.com'
        ]);
    }

    /**
     * Teste de exclusão de paciente.
     */
    public function test_delete_paciente()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um paciente para ser excluído
        $paciente = Paciente::create([
            'nome' => 'Paciente para Excluir',
            'cpf' => '999.888.777-66',
            'cartao_sus' => '999888777666555',
            'data_nascimento' => '1995-12-25',
            'sexo' => 'F',
            'endereco' => 'Rua para Excluir, 300',
            'telefone' => '(11) 94444-3333',
            'email' => 'excluir@example.com'
        ]);

        // Enviar requisição DELETE para excluir o paciente
        $response = $this->actingAs($user)->delete(route('pacientes.destroy', $paciente->id));

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect();

        // Verificar soft delete - o paciente deve ter deleted_at preenchido
        $this->assertSoftDeleted('pacientes', [
            'id' => $paciente->id
        ]);
    }
}
