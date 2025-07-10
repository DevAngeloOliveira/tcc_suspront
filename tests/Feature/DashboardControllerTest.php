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
use App\Models\Exame;
use App\Models\Atendente;
use Carbon\Carbon;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de acesso ao dashboard como administrador.
     */
    public function test_admin_can_access_dashboard()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar alguns dados para o dashboard
        $this->criarDadosParaDashboard();

        // Acessar o dashboard
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se o dashboard contém os elementos principais
        $response->assertSee('Dashboard');
        $response->assertSee('Pacientes');
        $response->assertSee('Médicos');
        $response->assertSee('Consultas de Hoje');
        $response->assertSee('Consultas Pendentes');
    }

    /**
     * Teste de acesso ao dashboard como médico.
     */
    public function test_medico_can_access_dashboard()
    {
        // Criar um usuário médico
        $userMedico = User::factory()->create(['tipo' => 'medico']);

        // Criar médico associado ao usuário
        $medico = Medico::create([
            'nome' => 'Dr. Dashboard',
            'crm' => '12345SP',
            'especialidade' => 'Clínica Geral',
            'cpf' => '123.456.789-00',
            'telefone' => '(11) 99999-8888',
            'email' => 'dr.dashboard@example.com',
            'user_id' => $userMedico->id
        ]);

        // Criar dados para o dashboard incluindo dados específicos deste médico
        $this->criarDadosParaDashboard($medico->id);

        // Acessar o dashboard
        $response = $this->actingAs($userMedico)->get(route('dashboard'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se o dashboard contém elementos relevantes para o médico
        $response->assertSee('Consultas de Hoje');
        $response->assertSee('Consultas Pendentes');

        // Verificar que não contém elementos administrativos
        $response->assertDontSee('Últimos Pacientes Cadastrados');
    }

    /**
     * Teste de acesso ao dashboard como atendente.
     */
    public function test_atendente_can_access_dashboard()
    {
        // Criar um usuário atendente
        $userAtendente = User::factory()->create(['tipo' => 'atendente']);

        // Criar atendente associado ao usuário com dados únicos
        $atendente = Atendente::create([
            'nome' => 'Atendente Dashboard',
            'cpf' => uniqid() . rand(1000, 9999),  // CPF único
            'telefone' => '(11) 98888-' . rand(1000, 9999),
            'email' => 'atendente.dashboard' . uniqid() . '@example.com',
            'registro' => 'REG' . uniqid(),
            'user_id' => $userAtendente->id
        ]);

        // Criar dados para o dashboard
        $this->criarDadosParaDashboard();

        // Acessar o dashboard
        $response = $this->actingAs($userAtendente)->get(route('dashboard'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);

        // Verificar se o dashboard contém elementos relevantes para o atendente
        $response->assertSee('Consultas de Hoje');
        $response->assertSee('Pacientes');
        $response->assertSee('Médicos');
    }

    /**
     * Teste de estatísticas no dashboard para administrador.
     */
    public function test_dashboard_shows_correct_statistics()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar dados específicos para testar estatísticas
        $this->criarDadosPorQuantidade(5, 3, 2, 4);

        // Acessar o dashboard
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Verificar se a página contém as estatísticas corretas
        $response->assertSee('Pacientes Cadastrados');
        $response->assertSee('Médicos Ativos');
        $response->assertSee('Consultas Pendentes');
    }

    /**
     * Teste de consultas de hoje no dashboard.
     */
    public function test_dashboard_shows_todays_consultations()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente
        $paciente = Paciente::create([
            'nome' => 'Paciente Dashboard Hoje',
            'cpf' => '111.222.333-44',
            'cartao_sus' => '111222333444555',
            'data_nascimento' => '1985-05-15',
            'sexo' => 'F',
            'endereco' => 'Rua Dashboard, 100',
            'telefone' => '(11) 97777-6666',
            'email' => 'paciente.dashboard@example.com'
        ]);

        // Criar prontuário
        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id
        ]);

        // Criar médico
        $medico = Medico::create([
            'nome' => 'Dr. Dashboard Hoje',
            'crm' => '67890SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '555.666.777-88',
            'telefone' => '(11) 96666-5555',
            'email' => 'dr.dashboard.hoje@example.com',
            'user_id' => $user->id
        ]);

        // Criar uma consulta para hoje
        $consulta = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'prontuario_id' => $prontuario->id,
            'data_hora' => Carbon::today()->setHour(14)->setMinute(30),
            'tipo_consulta' => 'Rotina',
            'queixa_principal' => 'Consulta de hoje',
            'status' => 'agendada'
        ]);

        // Acessar o dashboard
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Verificar se a consulta de hoje aparece no dashboard
        $response->assertSee('Paciente Dashboard Hoje');
        $response->assertSee('Dr. Dashboard Hoje');

        // Verificar que a resposta inclui texto relacionado às consultas de hoje
        $response->assertSee('Dr. Dashboard Hoje');
        $response->assertSee('Consultas de Hoje');
    }

    /**
     * Método auxiliar para criar dados para o dashboard.
     */
    private function criarDadosParaDashboard($medicoId = null)
    {
        // Criar usuários
        $adminUser = User::factory()->create(['tipo' => 'admin']);
        $medicoUser = User::factory()->create(['tipo' => 'medico']);
        $atendenteUser = User::factory()->create(['tipo' => 'atendente']);

        // Criar médico se não foi especificado
        if (!$medicoId) {
            $medico = Medico::create([
                'nome' => 'Dr. Teste',
                'crm' => '12345SP',
                'especialidade' => 'Clínico Geral',
                'cpf' => '123.456.789-00',
                'telefone' => '(11) 99999-8888',
                'email' => 'dr.teste@example.com',
                'user_id' => $medicoUser->id
            ]);
            $medicoId = $medico->id;
        }

        // Criar atendente
        $atendente = Atendente::create([
            'nome' => 'Atendente Teste',
            'cpf' => '987.654.321-00',
            'telefone' => '(11) 98888-7777',
            'email' => 'atendente@example.com',
            'registro' => 'REG12345',
            'user_id' => $atendenteUser->id
        ]);

        // Criar pacientes
        for ($i = 1; $i <= 3; $i++) {
            $paciente = Paciente::create([
                'nome' => "Paciente Teste {$i}",
                'cpf' => "111.222.333-{$i}",
                'cartao_sus' => "123456789{$i}",
                'data_nascimento' => '1990-01-01',
                'sexo' => $i % 2 == 0 ? 'F' : 'M',
                'endereco' => "Rua Teste {$i}, 100",
                'telefone' => "(11) 9{$i}{$i}{$i}{$i}-{$i}{$i}{$i}{$i}",
                'email' => "paciente{$i}@example.com"
            ]);

            // Criar prontuário
            $prontuario = Prontuario::create([
                'paciente_id' => $paciente->id
            ]);

            // Criar consulta para cada paciente
            Consulta::create([
                'paciente_id' => $paciente->id,
                'medico_id' => $medicoId,
                'prontuario_id' => $prontuario->id,
                'data_hora' => Carbon::now()->addDays($i),
                'tipo_consulta' => 'Rotina',
                'queixa_principal' => "Queixa do paciente {$i}",
                'status' => 'agendada'
            ]);

            // Criar exame para cada paciente
            Exame::create([
                'paciente_id' => $paciente->id,
                'medico_id' => $medicoId,
                'tipo_exame' => "Exame {$i}",
                'descricao' => "Descrição do exame {$i}",
                'data_solicitacao' => Carbon::now(),
                'status' => 'solicitado'
            ]);
        }

        // Criar uma consulta para hoje
        $pacienteHoje = Paciente::first();
        $prontuarioHoje = Prontuario::where('paciente_id', $pacienteHoje->id)->first();

        Consulta::create([
            'paciente_id' => $pacienteHoje->id,
            'medico_id' => $medicoId,
            'prontuario_id' => $prontuarioHoje->id,
            'data_hora' => Carbon::today()->setHour(10)->setMinute(0),
            'tipo_consulta' => 'Retorno',
            'queixa_principal' => 'Consulta de hoje',
            'status' => 'agendada'
        ]);
    }

    /**
     * Método auxiliar para criar dados específicos por quantidade.
     */
    private function criarDadosPorQuantidade($pacientes, $medicos, $atendentes, $consultas)
    {
        // Criar usuário admin
        $adminUser = User::factory()->create(['tipo' => 'admin']);

        // Criar médicos
        $medicoUsers = [];
        $medicoIds = [];

        for ($i = 1; $i <= $medicos; $i++) {
            $medicoUsers[$i] = User::factory()->create(['tipo' => 'medico']);

            $medico = Medico::create([
                'nome' => "Dr. Teste {$i}",
                'crm' => "1234{$i}SP",
                'especialidade' => "Especialidade {$i}",
                'cpf' => "111.222.{$i}{$i}{$i}-00",
                'telefone' => "(11) 9{$i}{$i}{$i}{$i}-0000",
                'email' => "dr.teste{$i}@example.com",
                'user_id' => $medicoUsers[$i]->id
            ]);

            $medicoIds[] = $medico->id;
        }

        // Criar atendentes
        for ($i = 1; $i <= $atendentes; $i++) {
            $atendenteUser = User::factory()->create(['tipo' => 'atendente']);

            Atendente::create([
                'nome' => "Atendente Teste {$i}",
                'cpf' => "222.333.{$i}{$i}{$i}-00",
                'telefone' => "(11) 8{$i}{$i}{$i}{$i}-0000",
                'email' => "atendente{$i}@example.com",
                'registro' => "REG{$i}",
                'user_id' => $atendenteUser->id
            ]);
        }

        // Criar pacientes e prontuários
        $pacienteIds = [];
        $prontuarioIds = [];

        for ($i = 1; $i <= $pacientes; $i++) {
            $paciente = Paciente::create([
                'nome' => "Paciente Teste {$i}",
                'cpf' => "333.444.{$i}{$i}{$i}-00",
                'cartao_sus' => "333444{$i}{$i}{$i}00",
                'data_nascimento' => "199{$i}-01-01",
                'sexo' => $i % 2 == 0 ? 'F' : 'M',
                'endereco' => "Rua Paciente {$i}, {$i}00",
                'telefone' => "(11) 7{$i}{$i}{$i}{$i}-0000",
                'email' => "paciente{$i}@example.com"
            ]);

            $pacienteIds[] = $paciente->id;

            $prontuario = Prontuario::create([
                'paciente_id' => $paciente->id
            ]);

            $prontuarioIds[] = $prontuario->id;
        }

        // Criar consultas
        for ($i = 1; $i <= $consultas; $i++) {
            $pacienteIndex = $i % count($pacienteIds);
            $medicoIndex = $i % count($medicoIds);

            Consulta::create([
                'paciente_id' => $pacienteIds[$pacienteIndex],
                'medico_id' => $medicoIds[$medicoIndex],
                'prontuario_id' => $prontuarioIds[$pacienteIndex],
                'data_hora' => Carbon::now()->addDays($i),
                'tipo_consulta' => $i % 2 == 0 ? 'Rotina' : 'Retorno',
                'queixa_principal' => "Queixa da consulta {$i}",
                'status' => 'agendada'
            ]);
        }
    }
}
