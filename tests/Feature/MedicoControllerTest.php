<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Medico;

class MedicoControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de listagem de médicos.
     */
    public function test_index_displays_medicos()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar médico de teste
        $medico = Medico::create([
            'nome' => 'Dr. Teste Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '123.456.789-00',
            'telefone' => '(11) 99999-8888',
            'email' => 'dr.teste@example.com',
            'user_id' => $user->id,
        ]);

        // Acessar a página de listagem de médicos com usuário autenticado
        $response = $this->actingAs($user)->get(route('medicos.index'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se o médico criado está presente na listagem
        $response->assertSee('Dr. Teste Silva');
        $response->assertSee('Cardiologia');
        $response->assertSee('12345SP');
    }

    /**
     * Teste de criação de médico.
     */
    public function test_store_creates_new_medico()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Dados para criar um novo médico
        $medicoData = [
            'nome' => 'Dr. Novo Médico',
            'crm' => '54321SP',
            'especialidade' => 'Ortopedia',
            'cpf' => '987.654.321-00',
            'telefone' => '(11) 98888-7777',
            'email' => 'dr.novo@example.com',
            'password' => 'senha12345',
            'password_confirmation' => 'senha12345',
        ];

        // Enviar requisição POST para criar médico
        $response = $this->actingAs($user)->post(route('medicos.store'), $medicoData);

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect(route('medicos.index'));

        // Verificar se o médico foi criado no banco de dados
        $this->assertDatabaseHas('medicos', [
            'nome' => 'Dr. Novo Médico',
            'crm' => '54321SP',
            'especialidade' => 'Ortopedia',
        ]);

        // Verificar se o usuário associado foi criado
        $this->assertDatabaseHas('users', [
            'name' => 'Dr. Novo Médico',
            'email' => 'dr.novo@example.com',
            'tipo' => 'medico',
        ]);
    }

    /**
     * Teste de exibição de detalhes de um médico.
     */
    public function test_show_displays_medico()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. Detalhes',
            'crm' => '99999SP',
            'especialidade' => 'Neurologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 97777-6666',
            'email' => 'dr.detalhes@example.com',
            'user_id' => $user->id
        ]);

        // Acessar página de detalhes do médico
        $response = $this->actingAs($user)->get(route('medicos.show', $medico->id));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se os detalhes do médico são exibidos
        $response->assertSee('Dr. Detalhes');
        $response->assertSee('99999SP');
        $response->assertSee('Neurologia');
    }

    /**
     * Teste de atualização de médico.
     */
    public function test_update_medico()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um usuário para o médico
        $medicoUser = User::factory()->create([
            'name' => 'Dr. Original',
            'email' => 'dr.original@example.com',
            'tipo' => 'medico'
        ]);

        // Criar um médico para ser atualizado
        $medico = Medico::create([
            'nome' => 'Dr. Original',
            'crm' => '55555SP',
            'especialidade' => 'Dermatologia',
            'cpf' => '555.666.777-88',
            'telefone' => '(11) 96666-5555',
            'email' => 'dr.original@example.com',
            'user_id' => $medicoUser->id
        ]);

        // Dados atualizados
        $dadosAtualizados = [
            'nome' => 'Dr. Atualizado',
            'crm' => '55555SP',
            'especialidade' => 'Dermatologia',
            'cpf' => '555.666.777-88',
            'telefone' => '(11) 95555-4444',
            'email' => 'dr.original@example.com',
        ];

        // Enviar requisição PUT para atualizar o médico
        $response = $this->actingAs($user)->put(route('medicos.update', $medico->id), $dadosAtualizados);

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect();

        // Verificar se os dados foram atualizados no banco de dados
        $this->assertDatabaseHas('medicos', [
            'id' => $medico->id,
            'nome' => 'Dr. Atualizado',
            'telefone' => '(11) 95555-4444',
        ]);
    }

    /**
     * Teste de restrição para médico ver apenas seu perfil.
     */
    public function test_medico_can_only_see_own_profile()
    {
        // Criar um usuário para o médico logado
        $medicoUser = User::factory()->create(['tipo' => 'medico']);

        // Criar médico logado
        $medicoLogado = Medico::create([
            'nome' => 'Dr. Logado',
            'crm' => '11111SP',
            'especialidade' => 'Pediatria',
            'cpf' => '111.111.111-11',
            'telefone' => '(11) 91111-1111',
            'email' => 'dr.logado@example.com',
            'user_id' => $medicoUser->id
        ]);

        // Criar outro médico
        $outroMedico = Medico::create([
            'nome' => 'Dr. Outro',
            'crm' => '22222SP',
            'especialidade' => 'Oftalmologia',
            'cpf' => '222.222.222-22',
            'telefone' => '(11) 92222-2222',
            'email' => 'dr.outro@example.com',
            'user_id' => User::factory()->create(['tipo' => 'medico'])->id
        ]);

        // Tentar acessar a listagem de médicos (deve redirecionar para o próprio perfil)
        $response = $this->actingAs($medicoUser)->get(route('medicos.index'));
        $response->assertRedirect(route('medicos.show', $medicoLogado->id));

        // Tentar acessar perfil de outro médico (deve redirecionar com erro)
        $response = $this->actingAs($medicoUser)->get(route('medicos.edit', $outroMedico->id));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /**
     * Teste de exclusão de médico.
     */
    public function test_delete_medico()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um usuário para o médico
        $medicoUser = User::factory()->create(['tipo' => 'medico']);

        // Criar um médico para ser excluído
        $medico = Medico::create([
            'nome' => 'Dr. Para Excluir',
            'crm' => '00000SP',
            'especialidade' => 'Psiquiatria',
            'cpf' => '000.000.000-00',
            'telefone' => '(11) 90000-0000',
            'email' => 'dr.excluir@example.com',
            'user_id' => $medicoUser->id
        ]);

        // Enviar requisição DELETE para excluir o médico
        $response = $this->actingAs($user)->delete(route('medicos.destroy', $medico->id));

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect();

        // Verificar se o médico foi excluído do banco de dados
        $this->assertDatabaseMissing('medicos', ['id' => $medico->id]);

        // Verificar se o usuário associado foi excluído
        $this->assertDatabaseMissing('users', ['id' => $medicoUser->id]);
    }

    /**
     * Teste do endpoint da API para obter médicos por especialidade
     */
    public function test_api_por_especialidade()
    {
        // Criar um usuário para autenticação
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar médicos de diferentes especialidades
        $medicoCardiologista = Medico::create([
            'nome' => 'Dr. Cardiologista',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '123.123.123-12',
            'telefone' => '(11) 99999-9999',
            'email' => 'cardiologista@example.com',
            'user_id' => $user->id
        ]);

        $medicoNeurologista = Medico::create([
            'nome' => 'Dr. Neurologista',
            'crm' => '54321SP',
            'especialidade' => 'Neurologia',
            'cpf' => '321.321.321-32',
            'telefone' => '(11) 88888-8888',
            'email' => 'neurologista@example.com',
            'user_id' => User::factory()->create(['tipo' => 'medico'])->id
        ]);

        // Acessar endpoint da API
        $response = $this->actingAs($user)->getJson('/api/medicos/especialidade/Cardiologia');

        // Verificar se a resposta é bem-sucedida
        $response->assertStatus(200);

        // Verificar se a resposta contém apenas o cardiologista
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'nome' => 'Dr. Cardiologista',
            'especialidade' => 'Cardiologia'
        ]);

        // Verificar que o neurologista não está incluído
        $response->assertJsonMissing([
            'nome' => 'Dr. Neurologista',
            'especialidade' => 'Neurologia'
        ]);
    }
}
