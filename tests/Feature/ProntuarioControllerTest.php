<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Prontuario;
use App\Models\Consulta;
use Carbon\Carbon;

class ProntuarioControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de listagem de prontuários.
     */
    public function test_index_displays_prontuarios()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente
        $paciente = Paciente::create([
            'nome' => 'Paciente Prontuário',
            'cpf' => '123.456.789-00',
            'cartao_sus' => '123456789012345',
            'data_nascimento' => '1990-01-01',
            'sexo' => 'M',
            'endereco' => 'Rua Prontuário, 123',
            'telefone' => '(11) 99999-8888',
            'email' => 'paciente.prontuario@example.com'
        ]);

        // Criar prontuário
        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Histórico médico do paciente',
            'medicamentos_atuais' => 'Medicamento A, Medicamento B',
            'observacoes' => 'Observações gerais'
        ]);

        // Acessar a página de listagem de prontuários
        $response = $this->actingAs($user)->get(route('prontuarios.index'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se o prontuário está presente na listagem (pelo nome do paciente)
        $response->assertSee('Paciente Prontuário');
    }

    /**
     * Teste de criação de prontuário.
     */
    public function test_store_creates_new_prontuario()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente sem prontuário
        $paciente = Paciente::create([
            'nome' => 'Paciente Sem Prontuário',
            'cpf' => '987.654.321-00',
            'cartao_sus' => '987654321098765',
            'data_nascimento' => '1985-05-15',
            'sexo' => 'F',
            'endereco' => 'Rua Sem Prontuário, 456',
            'telefone' => '(11) 98888-7777',
            'email' => 'sem.prontuario@example.com'
        ]);

        // Dados para criar um novo prontuário
        $prontuarioData = [
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Histórico médico detalhado',
            'medicamentos_atuais' => 'Paracetamol 500mg, Omeprazol 20mg',
            'observacoes' => 'Alérgico a penicilina'
        ];

        // Enviar requisição POST para criar prontuário
        $response = $this->actingAs($user)->post(route('prontuarios.store'), $prontuarioData);

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect(route('prontuarios.index'));

        // Verificar se o prontuário foi criado no banco de dados
        $this->assertDatabaseHas('prontuarios', [
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Histórico médico detalhado',
            'medicamentos_atuais' => 'Paracetamol 500mg, Omeprazol 20mg',
            'observacoes' => 'Alérgico a penicilina'
        ]);
    }

    /**
     * Teste de visualização de prontuário.
     */
    public function test_show_displays_prontuario()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente e prontuário
        $paciente = Paciente::create([
            'nome' => 'Paciente Visualizar',
            'cpf' => '111.222.333-44',
            'cartao_sus' => '111222333444555',
            'data_nascimento' => '1978-07-25',
            'sexo' => 'M',
            'endereco' => 'Rua Visualizar, 789',
            'telefone' => '(11) 97777-6666',
            'email' => 'paciente.visualizar@example.com'
        ]);

        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Paciente com hipertensão',
            'medicamentos_atuais' => 'Losartana 50mg',
            'observacoes' => 'Fazer acompanhamento da pressão'
        ]);

        // Acessar página de detalhes do prontuário
        $response = $this->actingAs($user)->get(route('prontuarios.show', $prontuario->id));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se os detalhes do prontuário são exibidos
        $response->assertSee('Paciente Visualizar');
        $response->assertSee('Paciente com hipertensão');
        $response->assertSee('Losartana 50mg');
    }

    /**
     * Teste de atualização de prontuário.
     */
    public function test_update_prontuario()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente e prontuário
        $paciente = Paciente::create([
            'nome' => 'Paciente Atualizar',
            'cpf' => '555.666.777-88',
            'cartao_sus' => '555666777888999',
            'data_nascimento' => '1965-12-10',
            'sexo' => 'F',
            'endereco' => 'Rua Atualizar, 321',
            'telefone' => '(11) 96666-5555',
            'email' => 'paciente.atualizar@example.com'
        ]);

        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Histórico antigo',
            'medicamentos_atuais' => 'Medicamentos antigos',
            'observacoes' => 'Observações antigas'
        ]);

        // Dados atualizados
        $dadosAtualizados = [
            'historico_medico' => 'Histórico médico atualizado',
            'medicamentos_atuais' => 'Novos medicamentos',
            'observacoes' => 'Novas observações importantes'
        ];

        // Enviar requisição PUT para atualizar o prontuário
        $response = $this->actingAs($user)->put(route('prontuarios.update', $prontuario->id), $dadosAtualizados);

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect(route('prontuarios.show', $prontuario->id));

        // Verificar se os dados foram atualizados no banco de dados
        $this->assertDatabaseHas('prontuarios', [
            'id' => $prontuario->id,
            'historico_medico' => 'Histórico médico atualizado',
            'medicamentos_atuais' => 'Novos medicamentos',
            'observacoes' => 'Novas observações importantes'
        ]);
    }

    /**
     * Teste de restrição de acesso para médicos.
     */
    public function test_medico_can_only_access_own_patients_prontuarios()
    {
        // Criar usuários médicos
        $medicoUser1 = User::factory()->create(['tipo' => 'medico']);
        $medicoUser2 = User::factory()->create(['tipo' => 'medico']);

        // Criar médicos
        $medico1 = Medico::create([
            'nome' => 'Dr. Autorizado',
            'crm' => '12345SP',
            'especialidade' => 'Clínica Geral',
            'cpf' => '111.111.111-11',
            'telefone' => '(11) 91111-1111',
            'email' => 'dr.autorizado@example.com',
            'user_id' => $medicoUser1->id
        ]);

        $medico2 = Medico::create([
            'nome' => 'Dr. Não Autorizado',
            'crm' => '54321SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '222.222.222-22',
            'telefone' => '(11) 92222-2222',
            'email' => 'dr.nao.autorizado@example.com',
            'user_id' => $medicoUser2->id
        ]);

        // Criar paciente e prontuário
        $paciente = Paciente::create([
            'nome' => 'Paciente Restrição',
            'cpf' => '333.333.333-33',
            'cartao_sus' => '333333333333333',
            'data_nascimento' => '1990-03-30',
            'sexo' => 'M',
            'endereco' => 'Rua Restrição, 100',
            'telefone' => '(11) 93333-3333',
            'email' => 'paciente.restricao@example.com'
        ]);

        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Histórico de teste',
        ]);

        // Criar uma consulta com o médico autorizado
        $consulta = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico1->id,
            'prontuario_id' => $prontuario->id,
            'data_hora' => Carbon::yesterday(),
            'tipo_consulta' => 'Rotina',
            'queixa_principal' => 'Dor de cabeça',
            'status' => 'concluida'
        ]);

        // Médico autorizado deve conseguir ver o prontuário
        $response = $this->actingAs($medicoUser1)->get(route('prontuarios.show', $prontuario->id));
        $response->assertStatus(200);

        // Médico não autorizado não deve conseguir ver o prontuário
        $response = $this->actingAs($medicoUser2)->get(route('prontuarios.show', $prontuario->id));
        $response->assertRedirect(route('prontuarios.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Teste de exclusão de prontuário.
     */
    public function test_delete_prontuario()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente e prontuário
        $paciente = Paciente::create([
            'nome' => 'Paciente Excluir Prontuário',
            'cpf' => '999.888.777-66',
            'cartao_sus' => '999888777666555',
            'data_nascimento' => '1995-12-25',
            'sexo' => 'F',
            'endereco' => 'Rua Excluir, 300',
            'telefone' => '(11) 94444-3333',
            'email' => 'paciente.excluir.prontuario@example.com'
        ]);

        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Prontuário para excluir',
        ]);

        // Enviar requisição DELETE para excluir o prontuário
        $response = $this->actingAs($user)->delete(route('prontuarios.destroy', $prontuario->id));

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect(route('pacientes.show', $paciente->id));

        // Verificar se o prontuário foi excluído do banco de dados
        $this->assertDatabaseMissing('prontuarios', ['id' => $prontuario->id]);
    }

    /**
     * Teste que não deve permitir excluir prontuário com consultas associadas.
     */
    public function test_cannot_delete_prontuario_with_consultas()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente e prontuário
        $paciente = Paciente::create([
            'nome' => 'Paciente Não Excluir',
            'cpf' => '444.444.444-44',
            'cartao_sus' => '444444444444444',
            'data_nascimento' => '1980-05-05',
            'sexo' => 'M',
            'endereco' => 'Rua Não Excluir, 400',
            'telefone' => '(11) 95555-2222',
            'email' => 'paciente.nao.excluir@example.com'
        ]);

        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Prontuário não deve ser excluído',
        ]);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. Teste',
            'crm' => '99999SP',
            'especialidade' => 'Clínica Geral',
            'cpf' => '555.555.555-55',
            'telefone' => '(11) 95555-5555',
            'email' => 'dr.teste@example.com',
            'user_id' => $user->id
        ]);

        // Criar uma consulta associada ao prontuário
        $consulta = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'prontuario_id' => $prontuario->id,
            'data_hora' => Carbon::yesterday(),
            'tipo_consulta' => 'Rotina',
            'queixa_principal' => 'Qualquer queixa',
            'status' => 'concluida'
        ]);

        // Enviar requisição DELETE para excluir o prontuário
        $response = $this->actingAs($user)->delete(route('prontuarios.destroy', $prontuario->id));

        // Verificar que o redirecionamento ocorreu com erro
        $response->assertSessionHas('error');

        // Verificar que o prontuário ainda existe no banco de dados
        $this->assertDatabaseHas('prontuarios', ['id' => $prontuario->id]);
    }
}
