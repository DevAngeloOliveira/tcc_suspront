<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Dashboard;
use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Consulta;
use App\Models\Prontuario;
use App\Models\Exame;
use App\Models\Atendente;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
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

        // Testar o componente Dashboard
        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertSee('Dashboard')
            ->assertStatus(200);

        // Acessar o dashboard pela rota
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);
    }

    /**
     * Teste de acesso ao dashboard como médico.
     */
    public function test_medico_can_access_dashboard()
    {
        // Criar um usuário médico
        $user = User::factory()->create([
            'name' => 'Dr. Teste',
            'email' => 'medico@example.com',
            'tipo' => 'medico',
        ]);

        // Criar um médico vinculado ao usuário
        $medico = Medico::create([
            'nome' => 'Dr. Teste',
            'crm' => '12345SP',
            'especialidade' => 'Clínico Geral',
            'cpf' => '987.654.321-00',
            'email' => 'medico@example.com',
            'telefone' => '(11) 99999-7777',
            'user_id' => $user->id,
        ]);

        // Criar alguns dados para o dashboard
        $this->criarDadosParaDashboard();

        // Testar o componente Dashboard
        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertSee('Dashboard')
            ->assertStatus(200);

        // Acessar o dashboard pela rota
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);
    }

    /**
     * Teste de acesso ao dashboard como atendente.
     */
    public function test_atendente_can_access_dashboard()
    {
        // Criar um usuário atendente
        $user = User::factory()->create([
            'name' => 'Atendente Teste',
            'email' => 'atendente@example.com',
            'tipo' => 'atendente',
        ]);

        // Criar um atendente vinculado ao usuário
        $atendente = Atendente::create([
            'nome' => 'Atendente Teste',
            'cpf' => '111.222.333-44',
            'email' => 'atendente@example.com',
            'telefone' => '(11) 99999-6666',
            'user_id' => $user->id,
        ]);

        // Criar alguns dados para o dashboard
        $this->criarDadosParaDashboard();

        // Testar o componente Dashboard
        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertSee('Dashboard')
            ->assertStatus(200);

        // Acessar o dashboard pela rota
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);
    }

    /**
     * Teste de redirecionamento para login quando não autenticado.
     */
    public function test_unauthenticated_users_are_redirected_to_login()
    {
        // Acessar o dashboard sem autenticação
        $response = $this->get(route('dashboard'));

        // Verificar se foi redirecionado para a tela de login
        $response->assertRedirect(route('login'));
    }

    /**
     * Criar dados para testar o dashboard.
     */
    private function criarDadosParaDashboard()
    {
        // Criar usuários para pacientes (como admin, já que 'paciente' não é um tipo válido)
        $userPaciente1 = User::factory()->create(['tipo' => 'admin']);
        $userPaciente2 = User::factory()->create(['tipo' => 'admin']);

        // Criar pacientes
        $paciente1 = Paciente::create([
            'nome' => 'Paciente Teste 1',
            'cpf' => '123.456.789-00',
            'cartao_sus' => '123456789012345', // Campo obrigatório
            'data_nascimento' => '1990-01-01',
            'sexo' => 'M',
            'endereco' => 'Rua Teste, 123',
            'telefone' => '(11) 99999-8888',
            'email' => 'paciente1@example.com',
            'user_id' => $userPaciente1->id,
        ]);

        $paciente2 = Paciente::create([
            'nome' => 'Paciente Teste 2',
            'cpf' => '234.567.890-11',
            'cartao_sus' => '234567890123456', // Campo obrigatório
            'data_nascimento' => '1985-05-15',
            'sexo' => 'F',
            'endereco' => 'Rua Exemplo, 456',
            'telefone' => '(11) 99999-7777',
            'email' => 'paciente2@example.com',
            'user_id' => $userPaciente2->id,
        ]);

        // Criar prontuários
        $prontuario1 = Prontuario::create([
            'paciente_id' => $paciente1->id,
            'historico_medico' => 'Histórico médico do paciente 1',
            'data_criacao' => Carbon::now(), // Campo adicional que pode ser necessário
            'alergias' => 'Nenhuma',
            'medicacoes' => 'Nenhuma',
        ]);

        $prontuario2 = Prontuario::create([
            'paciente_id' => $paciente2->id,
            'historico_medico' => 'Histórico médico do paciente 2',
            'data_criacao' => Carbon::now(), // Campo adicional que pode ser necessário
            'alergias' => 'Nenhuma',
            'medicacoes' => 'Nenhuma',
        ]);

        // Criar usuários para médicos
        $userMedico1 = User::factory()->create(['tipo' => 'medico']);
        $userMedico2 = User::factory()->create(['tipo' => 'medico']);

        // Criar médicos
        $medico1 = Medico::create([
            'nome' => 'Dr. Exemplo 1',
            'crm' => '12345SP',
            'especialidade' => 'Clínico Geral',
            'cpf' => '345.678.901-22',
            'email' => 'medico1@example.com',
            'telefone' => '(11) 99999-6666',
            'user_id' => $userMedico1->id, // Atribuir o ID do usuário criado
        ]);

        $medico2 = Medico::create([
            'nome' => 'Dra. Exemplo 2',
            'crm' => '67890SP',
            'especialidade' => 'Cardiologista',
            'cpf' => '456.789.012-33',
            'email' => 'medico2@example.com',
            'telefone' => '(11) 99999-5555',
            'user_id' => $userMedico2->id, // Atribuir o ID do usuário criado
        ]);

        // Criar consultas para hoje
        Consulta::create([
            'prontuario_id' => $prontuario1->id,
            'medico_id' => $medico1->id,
            'paciente_id' => $paciente1->id,
            'data_hora' => Carbon::today()->addHours(10),
            'tipo_consulta' => 'rotina', // Campo obrigatório
            'status' => 'agendada',
            'observacoes' => 'Consulta de rotina',
            'motivo' => 'Consulta de rotina',
        ]);

        Consulta::create([
            'prontuario_id' => $prontuario2->id,
            'medico_id' => $medico2->id,
            'paciente_id' => $paciente2->id,
            'data_hora' => Carbon::today()->addHours(14),
            'tipo_consulta' => 'retorno', // Campo obrigatório
            'status' => 'confirmada',
            'observacoes' => 'Retorno para revisão',
            'motivo' => 'Retorno para revisão',
        ]);

        // Criar consultas para amanhã
        Consulta::create([
            'prontuario_id' => $prontuario1->id,
            'medico_id' => $medico2->id,
            'paciente_id' => $paciente1->id,
            'data_hora' => Carbon::tomorrow()->addHours(11),
            'tipo_consulta' => 'especializada', // Campo obrigatório
            'status' => 'agendada',
            'observacoes' => 'Consulta especializada',
            'motivo' => 'Consulta especializada',
        ]);

        // Criar exames
        Exame::create([
            'paciente_id' => $paciente1->id,
            'medico_id' => $medico1->id,
            'prontuario_id' => $prontuario1->id,
            'tipo_exame' => 'Hemograma',
            'status' => 'solicitado',
            'data_solicitacao' => Carbon::today(),
        ]);

        Exame::create([
            'paciente_id' => $paciente2->id,
            'medico_id' => $medico2->id,
            'prontuario_id' => $prontuario2->id,
            'tipo_exame' => 'Eletrocardiograma',
            'status' => 'agendado',
            'data_solicitacao' => Carbon::today()->subDays(2),
            'data_agendada' => Carbon::tomorrow(),
        ]);
    }
}
