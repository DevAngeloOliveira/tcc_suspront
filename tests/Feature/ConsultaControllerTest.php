<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Consulta;
use App\Models\Prontuario;
use Carbon\Carbon;

class ConsultaControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de listagem de consultas.
     */
    public function test_index_displays_consultas()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um paciente
        $paciente = Paciente::create([
            'nome' => 'Paciente Teste',
            'cpf' => '123.456.789-00',
            'cartao_sus' => '123456789012345',
            'data_nascimento' => '1990-01-01',
            'sexo' => 'M',
            'endereco' => 'Rua Teste, 123',
            'telefone' => '(11) 99999-8888',
            'email' => 'paciente@example.com'
        ]);

        // Criar um prontuário
        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Histórico de teste',
        ]);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. Teste',
            'crm' => '12345SP',
            'especialidade' => 'Clínico Geral',
            'cpf' => '987.654.321-00',
            'telefone' => '(11) 88888-7777',
            'email' => 'medico@example.com',
            'user_id' => $user->id,
        ]);

        // Criar uma consulta
        $consulta = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'prontuario_id' => $prontuario->id,
            'data_hora' => Carbon::tomorrow()->setHour(10)->setMinute(0),
            'tipo_consulta' => 'Rotina',
            'queixa_principal' => 'Dor de cabeça',
            'status' => 'agendada'
        ]);

        // Acessar a página de listagem de consultas
        $response = $this->actingAs($user)->get(route('consultas.index'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se o título da lista de consultas está presente (mais robusto para encoding)
        $response->assertSee('Lista de Consultas');
    }

    /**
     * Teste de criação de consulta.
     */
    public function test_store_creates_new_consulta()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um paciente
        $paciente = Paciente::create([
            'nome' => 'Paciente Consulta',
            'cpf' => '111.222.333-44',
            'cartao_sus' => '111222333444555',
            'data_nascimento' => '1985-05-15',
            'sexo' => 'F',
            'endereco' => 'Rua Consulta, 456',
            'telefone' => '(11) 97777-6666',
            'email' => 'paciente.consulta@example.com'
        ]);

        // Criar prontuário para o paciente
        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Histórico do paciente',
        ]);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. Consulta',
            'crm' => '67890SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '555.666.777-88',
            'telefone' => '(11) 96666-5555',
            'email' => 'dr.consulta@example.com',
            'user_id' => $user->id,
        ]);

        // Amanhã às 14:30
        $dataHora = Carbon::tomorrow()->setHour(14)->setMinute(30);

        // Dados para criar uma nova consulta
        $consultaData = [
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'data' => $dataHora->format('Y-m-d'),
            'hora' => $dataHora->format('H:i'),
            'tipo_consulta' => 'Especializada',
            'queixa_principal' => 'Dores no peito',
        ];

        // Enviar requisição POST para criar consulta
        $response = $this->actingAs($user)->post(route('consultas.store'), $consultaData);

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect();

        // Verificar se a consulta foi criada no banco de dados
        $this->assertDatabaseHas('consultas', [
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'tipo_consulta' => 'Especializada',
            'queixa_principal' => 'Dores no peito',
            'status' => 'agendada'
        ]);
    }

    /**
     * Teste de exibição de detalhes de uma consulta.
     */
    public function test_show_displays_consulta()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente, prontuário, médico e consulta
        $paciente = Paciente::create([
            'nome' => 'Paciente Detalhes',
            'cpf' => '123.123.123-12',
            'cartao_sus' => '123123123123123',
            'data_nascimento' => '1975-10-25',
            'sexo' => 'M',
            'endereco' => 'Rua Detalhes, 789',
            'telefone' => '(11) 95555-4444',
            'email' => 'paciente.detalhes@example.com'
        ]);

        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Histórico detalhado',
        ]);

        $medico = Medico::create([
            'nome' => 'Dr. Detalhes',
            'crm' => '13579SP',
            'especialidade' => 'Neurologia',
            'cpf' => '321.321.321-32',
            'telefone' => '(11) 94444-3333',
            'email' => 'dr.detalhes@example.com',
            'user_id' => $user->id,
        ]);

        $consulta = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'prontuario_id' => $prontuario->id,
            'data_hora' => Carbon::tomorrow()->setHour(16)->setMinute(0),
            'tipo_consulta' => 'Rotina',
            'queixa_principal' => 'Dores de cabeça frequentes',
            'status' => 'agendada'
        ]);

        // Acessar página de detalhes da consulta
        $response = $this->actingAs($user)->get(route('consultas.show', $consulta->id));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se os detalhes da consulta são exibidos
        $response->assertSee('Paciente Detalhes');
        $response->assertSee('Dr. Detalhes');
        $response->assertSee('Dores de cabeça frequentes');
    }

    /**
     * Teste de atualização de status da consulta.
     */
    public function test_update_status()
    {
        // Criar usuário médico
        $medicoUser = User::factory()->create(['tipo' => 'medico']);

        // Criar paciente, prontuário
        $paciente = Paciente::create([
            'nome' => 'Paciente Status',
            'cpf' => '444.444.444-44',
            'cartao_sus' => '444444444444444',
            'data_nascimento' => '1980-07-20',
            'sexo' => 'F',
            'endereco' => 'Rua Status, 100',
            'telefone' => '(11) 93333-2222',
            'email' => 'paciente.status@example.com'
        ]);

        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Histórico básico',
        ]);

        // Criar médico associado ao usuário
        $medico = Medico::create([
            'nome' => 'Dr. Status',
            'crm' => '24680SP',
            'especialidade' => 'Clínica Geral',
            'cpf' => '444.333.222-11',
            'telefone' => '(11) 92222-1111',
            'email' => 'dr.status@example.com',
            'user_id' => $medicoUser->id,
        ]);

        // Criar consulta
        $consulta = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'prontuario_id' => $prontuario->id,
            'data_hora' => Carbon::now()->addHour(),
            'tipo_consulta' => 'Retorno',
            'queixa_principal' => 'Verificar resultados',
            'status' => 'agendada'
        ]);

        // Dados de atualização
        $updateData = [
            'status' => 'em_andamento'
        ];

        // Enviar requisição PUT para atualizar status (via API)
        $response = $this->actingAs($medicoUser)->putJson("/api/consultas/{$consulta->id}/status", $updateData);

        // Verificar resposta
        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'em_andamento']);

        // Verificar se o banco de dados foi atualizado
        $this->assertDatabaseHas('consultas', [
            'id' => $consulta->id,
            'status' => 'em_andamento'
        ]);
    }

    /**
     * Teste de exclusão de consulta.
     */
    public function test_delete_consulta()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente, prontuário, médico e consulta
        $paciente = Paciente::create([
            'nome' => 'Paciente Excluir',
            'cpf' => '999.888.777-66',
            'cartao_sus' => '999888777666555',
            'data_nascimento' => '1995-12-25',
            'sexo' => 'F',
            'endereco' => 'Rua Excluir, 300',
            'telefone' => '(11) 91111-0000',
            'email' => 'excluir@example.com'
        ]);

        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id
        ]);

        $medico = Medico::create([
            'nome' => 'Dr. Excluir',
            'crm' => '99999SP',
            'especialidade' => 'Dermatologia',
            'cpf' => '111.222.333-00',
            'telefone' => '(11) 90000-0000',
            'email' => 'dr.excluir@example.com',
            'user_id' => $user->id,
        ]);

        $consulta = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'prontuario_id' => $prontuario->id,
            'data_hora' => Carbon::tomorrow()->setHour(9)->setMinute(0),
            'tipo_consulta' => 'Retorno',
            'queixa_principal' => 'Acompanhamento',
            'status' => 'agendada'
        ]);

        // Enviar requisição DELETE para excluir a consulta
        $response = $this->actingAs($user)->delete(route('consultas.destroy', $consulta->id));

        // Verificar se o redirecionamento ocorreu corretamente
        $response->assertRedirect();

        // Verificar se a consulta foi marcada como cancelada (soft delete lógico via status)
        $this->assertDatabaseHas('consultas', [
            'id' => $consulta->id,
            'status' => 'cancelada'
        ]);
    }

    /**
     * Teste de API para listar horários disponíveis.
     */
    public function test_api_horarios_disponiveis()
    {
        // Criar um usuário
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. Horarios',
            'crm' => '54545SP',
            'especialidade' => 'Clínica Geral',
            'cpf' => '555.555.555-55',
            'telefone' => '(11) 95555-5555',
            'email' => 'dr.horarios@example.com',
            'user_id' => $user->id,
        ]);

        // Criar paciente e prontuário
        $paciente = Paciente::create([
            'nome' => 'Paciente Horário',
            'cpf' => '666.666.666-66',
            'cartao_sus' => '666666666666666',
            'data_nascimento' => '1990-06-15',
            'sexo' => 'M',
            'endereco' => 'Rua Horário, 500',
            'telefone' => '(11) 96666-6666',
            'email' => 'paciente.horario@example.com'
        ]);

        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id
        ]);

        // Amanhã às 10:00
        $amanha = Carbon::tomorrow();
        $dataStr = $amanha->format('Y-m-d');

        // Criar uma consulta às 10h
        Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'prontuario_id' => $prontuario->id,
            'data_hora' => $amanha->copy()->setHour(10)->setMinute(0),
            'tipo_consulta' => 'Rotina',
            'queixa_principal' => 'Consulta ocupando horário',
            'status' => 'agendada'
        ]);

        // Verificar horários disponíveis via API
        $response = $this->actingAs($user)->getJson("/api/consultas/horarios-disponiveis?data={$dataStr}&medico_id={$medico->id}");

        // Verificar resposta
        $response->assertStatus(200);
        $response->assertJsonStructure(['horarios']);

        // Verificar que o horário da consulta existente não está disponível
        $horariosDisponiveis = $response->json('horarios');
        $this->assertNotContains('10:00', $horariosDisponiveis);

        // Verificar que outros horários estão disponíveis
        $this->assertContains('09:00', $horariosDisponiveis);
        $this->assertContains('11:00', $horariosDisponiveis);
    }
}
