<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Consultas\ConsultaList;
use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Consulta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Carbon\Carbon;

class ConsultasListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de listagem de consultas como administrador.
     */
    public function test_admin_can_view_consultas_list()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. João Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 91111-2222',
            'email' => 'joao.silva@example.com',
            'user_id' => $user->id,
        ]);

        // Criar um paciente
        $userPaciente = User::factory()->create(['tipo' => 'admin']);
        $paciente = Paciente::create([
            'nome' => 'Maria Souza',
            'cpf' => '222.333.444-55',
            'data_nascimento' => '1990-01-01',
            'telefone' => '(11) 92222-3333',
            'email' => 'maria.souza@example.com',
            'user_id' => $userPaciente->id,
            'cartao_sus' => '123456789012345',
        ]);

        // Criar consulta de teste
        $consulta = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'data_hora' => Carbon::now()->addDays(1),
            'status' => 'agendada',
            'motivo' => 'Consulta de rotina',
            'tipo_consulta' => 'rotina', // Campo obrigatório
        ]);

        // Testar o componente Livewire
        Livewire::actingAs($user)
            ->test(ConsultaList::class)
            ->assertSee('Maria Souza')
            ->assertSee('Dr. João Silva')
            ->assertSee('agendada')
            ->assertStatus(200);

        // Acessar a página pela rota
        $response = $this->actingAs($user)->get(route('consultas.index'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);
    }

    /**
     * Teste de filtro por status de consulta.
     */
    public function test_can_filter_by_status()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. João Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 91111-2222',
            'email' => 'joao.silva@example.com',
            'user_id' => $user->id,
        ]);

        // Criar pacientes
        $userPaciente1 = User::factory()->create(['tipo' => 'admin']);
        $paciente1 = Paciente::create([
            'nome' => 'Maria Souza',
            'cpf' => '222.333.444-55',
            'data_nascimento' => '1990-01-01',
            'telefone' => '(11) 92222-3333',
            'email' => 'maria.souza@example.com',
            'user_id' => $userPaciente1->id,
            'cartao_sus' => '123456789012345',
        ]);

        $userPaciente2 = User::factory()->create(['tipo' => 'admin']);
        $paciente2 = Paciente::create([
            'nome' => 'José Santos',
            'cpf' => '333.444.555-66',
            'data_nascimento' => '1985-05-15',
            'telefone' => '(11) 93333-4444',
            'email' => 'jose.santos@example.com',
            'user_id' => $userPaciente2->id,
            'cartao_sus' => '234567890123456',
        ]);

        // Criar consultas com status diferentes
        $consultaAgendada = Consulta::create([
            'paciente_id' => $paciente1->id,
            'medico_id' => $medico->id,
            'data_hora' => Carbon::now()->addDays(1),
            'status' => 'agendada',
            'motivo' => 'Consulta de rotina',
            'tipo_consulta' => 'rotina', // Campo obrigatório
        ]);

        $consultaConcluida = Consulta::create([
            'paciente_id' => $paciente2->id,
            'medico_id' => $medico->id,
            'data_hora' => Carbon::now()->subDays(2),
            'status' => 'concluida',
            'motivo' => 'Consulta de rotina',
            'tipo_consulta' => 'retorno', // Campo obrigatório
        ]);

        // Testar o filtro por status
        Livewire::actingAs($user)
            ->test(ConsultaList::class)
            ->set('statusFiltro', 'agendada')
            ->assertSee('Maria Souza')
            ->assertDontSee('José Santos');

        Livewire::actingAs($user)
            ->test(ConsultaList::class)
            ->set('statusFiltro', 'concluida')
            ->assertSee('José Santos')
            ->assertDontSee('Maria Souza');
    }

    /**
     * Teste de busca por nome do paciente.
     */
    public function test_can_search_by_patient_name()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. João Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 91111-2222',
            'email' => 'joao.silva@example.com',
            'user_id' => $user->id,
        ]);

        // Criar pacientes
        $userPaciente1 = User::factory()->create(['tipo' => 'admin']);
        $paciente1 = Paciente::create([
            'nome' => 'Maria Oliveira',
            'cpf' => '222.333.444-55',
            'data_nascimento' => '1990-01-01',
            'telefone' => '(11) 92222-3333',
            'email' => 'maria.oliveira@example.com',
            'user_id' => $userPaciente1->id,
            'cartao_sus' => '123456789012345',
        ]);

        $userPaciente2 = User::factory()->create(['tipo' => 'admin']);
        $paciente2 = Paciente::create([
            'nome' => 'Paulo Santos',
            'cpf' => '333.444.555-66',
            'data_nascimento' => '1985-05-15',
            'telefone' => '(11) 93333-4444',
            'email' => 'paulo.santos@example.com',
            'user_id' => $userPaciente2->id,
            'cartao_sus' => '234567890123456',
        ]);

        // Criar consultas para os pacientes
        $consulta1 = Consulta::create([
            'paciente_id' => $paciente1->id,
            'medico_id' => $medico->id,
            'data_hora' => Carbon::now()->addDays(1),
            'status' => 'agendada',
            'motivo' => 'Consulta de rotina',
            'tipo_consulta' => 'rotina', // Campo obrigatório
        ]);

        $consulta2 = Consulta::create([
            'paciente_id' => $paciente2->id,
            'medico_id' => $medico->id,
            'data_hora' => Carbon::now()->addDays(2),
            'status' => 'agendada',
            'motivo' => 'Consulta de rotina',
            'tipo_consulta' => 'rotina', // Campo obrigatório
        ]);

        // Testar a busca por nome do paciente
        Livewire::actingAs($user)
            ->test(ConsultaList::class)
            ->set('search', 'Maria')
            ->assertSee('Maria Oliveira')
            ->assertDontSee('Paulo Santos');
    }

    /**
     * Teste de filtro por médico.
     */
    public function test_can_filter_by_medico()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar médicos
        $medico1 = Medico::create([
            'nome' => 'Dr. João Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 91111-2222',
            'email' => 'joao.silva@example.com',
            'user_id' => $user->id,
        ]);

        $userMedico2 = User::factory()->create(['tipo' => 'medico']);
        $medico2 = Medico::create([
            'nome' => 'Dra. Ana Martins',
            'crm' => '67890SP',
            'especialidade' => 'Dermatologia',
            'cpf' => '444.555.666-77',
            'telefone' => '(11) 94444-5555',
            'email' => 'ana.martins@example.com',
            'user_id' => $userMedico2->id,
        ]);

        // Criar paciente
        $userPaciente = User::factory()->create(['tipo' => 'admin']);
        $paciente = Paciente::create([
            'nome' => 'Carlos Pereira',
            'cpf' => '555.666.777-88',
            'data_nascimento' => '1980-10-15',
            'telefone' => '(11) 95555-6666',
            'email' => 'carlos.pereira@example.com',
            'user_id' => $userPaciente->id,
            'cartao_sus' => '345678901234567',
        ]);

        // Criar consultas para diferentes médicos
        $consulta1 = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico1->id,
            'data_hora' => Carbon::now()->addDays(1)->setTime(10, 0),
            'status' => 'agendada',
            'motivo' => 'Consulta cardiológica',
            'tipo_consulta' => 'especializada', // Campo obrigatório
        ]);

        $consulta2 = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico2->id,
            'data_hora' => Carbon::now()->addDays(2)->setTime(14, 30),
            'status' => 'agendada',
            'motivo' => 'Consulta dermatológica',
            'tipo_consulta' => 'especializada', // Campo obrigatório
        ]);

        // Testar o filtro por médico
        Livewire::actingAs($user)
            ->test(ConsultaList::class)
            ->set('medicoFiltro', $medico1->id)
            ->assertSee('Consulta cardiológica')
            ->assertDontSee('Consulta dermatológica');

        Livewire::actingAs($user)
            ->test(ConsultaList::class)
            ->set('medicoFiltro', $medico2->id)
            ->assertSee('Consulta dermatológica')
            ->assertDontSee('Consulta cardiológica');
    }

    /**
     * Teste de filtragem por data.
     */
    public function test_can_filter_by_date()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. João Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 91111-2222',
            'email' => 'joao.silva@example.com',
            'user_id' => $user->id,
        ]);

        // Criar paciente
        $userPaciente = User::factory()->create(['tipo' => 'admin']);
        $paciente = Paciente::create([
            'nome' => 'Carlos Pereira',
            'cpf' => '555.666.777-88',
            'data_nascimento' => '1980-10-15',
            'telefone' => '(11) 95555-6666',
            'email' => 'carlos.pereira@example.com',
            'user_id' => $userPaciente->id,
            'cartao_sus' => '345678901234567',
        ]);

        // Criar consultas para datas diferentes
        $dataHoje = Carbon::now();
        $dataAmanha = Carbon::now()->addDay();

        $consultaHoje = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'data_hora' => $dataHoje->setTime(10, 0),
            'status' => 'agendada',
            'motivo' => 'Consulta de hoje',
            'tipo_consulta' => 'rotina', // Campo obrigatório
        ]);

        $consultaAmanha = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'data_hora' => $dataAmanha->setTime(14, 30),
            'status' => 'agendada',
            'motivo' => 'Consulta de amanhã',
            'tipo_consulta' => 'retorno', // Campo obrigatório
        ]);

        // Testar o filtro por data
        Livewire::actingAs($user)
            ->test(ConsultaList::class)
            ->set('dataFiltro', $dataHoje->format('Y-m-d'))
            ->assertSee('Consulta de hoje')
            ->assertDontSee('Consulta de amanhã');

        Livewire::actingAs($user)
            ->test(ConsultaList::class)
            ->set('dataFiltro', $dataAmanha->format('Y-m-d'))
            ->assertSee('Consulta de amanhã')
            ->assertDontSee('Consulta de hoje');
    }

    /**
     * Teste de atualização de status da consulta.
     */
    public function test_can_update_consulta_status()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. João Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 91111-2222',
            'email' => 'joao.silva@example.com',
            'user_id' => $user->id,
        ]);

        // Criar paciente
        $userPaciente = User::factory()->create(['tipo' => 'admin']);
        $paciente = Paciente::create([
            'nome' => 'Carlos Pereira',
            'cpf' => '555.666.777-88',
            'data_nascimento' => '1980-10-15',
            'telefone' => '(11) 95555-6666',
            'email' => 'carlos.pereira@example.com',
            'user_id' => $userPaciente->id,
            'cartao_sus' => '345678901234567',
        ]);

        // Criar consulta com status inicial
        $consulta = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'data_hora' => Carbon::now()->addDay(),
            'status' => 'agendada',
            'motivo' => 'Consulta de rotina',
            'tipo_consulta' => 'rotina', // Campo obrigatório
        ]);

        // Testar a atualização de status
        Livewire::actingAs($user)
            ->test(ConsultaList::class)
            ->call('updateStatus', $consulta->id, 'confirmada')
            ->assertDispatched('alert', function ($e) {
                return $e['type'] === 'success' &&
                    str_contains($e['message'], 'Status da consulta atualizado com sucesso');
            });

        // Verificar se o status foi realmente atualizado no banco de dados
        $this->assertEquals('confirmada', Consulta::find($consulta->id)->status);
    }

    /**
     * Teste de médico vendo apenas suas consultas.
     */
    public function test_medico_sees_only_own_consultas()
    {
        // Criar usuários
        $userMedico1 = User::factory()->create(['tipo' => 'medico']);
        $userMedico2 = User::factory()->create(['tipo' => 'medico']);

        // Criar médicos
        $medico1 = Medico::create([
            'nome' => 'Dr. João Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 91111-2222',
            'email' => 'joao.silva@example.com',
            'user_id' => $userMedico1->id,
        ]);

        $medico2 = Medico::create([
            'nome' => 'Dr. Roberto Gomes',
            'crm' => '67890SP',
            'especialidade' => 'Ortopedia',
            'cpf' => '444.555.666-77',
            'telefone' => '(11) 94444-5555',
            'email' => 'roberto.gomes@example.com',
            'user_id' => $userMedico2->id,
        ]);

        // Criar paciente
        $userPaciente = User::factory()->create(['tipo' => 'admin']);
        $paciente = Paciente::create([
            'nome' => 'Carlos Pereira',
            'cpf' => '555.666.777-88',
            'data_nascimento' => '1980-10-15',
            'telefone' => '(11) 95555-6666',
            'email' => 'carlos.pereira@example.com',
            'user_id' => $userPaciente->id,
            'cartao_sus' => '345678901234567',
        ]);

        // Criar consultas para diferentes médicos
        $consultaMedico1 = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico1->id,
            'data_hora' => Carbon::now()->addDays(1),
            'status' => 'agendada',
            'motivo' => 'Consulta cardiológica',
            'tipo_consulta' => 'especializada', // Campo obrigatório
        ]);

        $consultaMedico2 = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico2->id,
            'data_hora' => Carbon::now()->addDays(2),
            'status' => 'agendada',
            'motivo' => 'Consulta ortopédica',
            'tipo_consulta' => 'especializada', // Campo obrigatório
        ]);

        // Testar que o médico 1 só vê suas próprias consultas
        Livewire::actingAs($userMedico1)
            ->test(ConsultaList::class)
            ->assertSee('Consulta cardiológica')
            ->assertDontSee('Consulta ortopédica');

        // Testar que o médico 2 só vê suas próprias consultas
        Livewire::actingAs($userMedico2)
            ->test(ConsultaList::class)
            ->assertSee('Consulta ortopédica')
            ->assertDontSee('Consulta cardiológica');
    }
}
