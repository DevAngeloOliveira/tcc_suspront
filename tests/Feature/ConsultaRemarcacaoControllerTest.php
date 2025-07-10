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
use App\Models\MedicoPlantao;
use App\Services\NotificacaoService;
use Carbon\Carbon;
use Mockery;
use Illuminate\Support\Facades\DB;

class ConsultaRemarcacaoControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Configuração inicial para os testes
     */
    private function setupTestData()
    {
        // Criar usuário médico
        $userMedico = User::factory()->create(['tipo' => 'medico']);

        // Criar médico
        $medico = Medico::create([
            'nome' => 'Dr. Teste',
            'crm' => '12345SP',
            'especialidade' => 'Clínico Geral',
            'cpf' => '123.456.789-00',
            'telefone' => '11999998888',
            'email' => 'dr.teste@example.com',
            'user_id' => $userMedico->id
        ]);

        // Criar usuário admin
        $userAdmin = User::factory()->create(['tipo' => 'admin']);

        // Criar paciente
        $paciente = Paciente::create([
            'nome' => 'Paciente Teste',
            'cpf' => '987.654.321-00',
            'cartao_sus' => '987654321098765',
            'data_nascimento' => '1990-01-01',
            'sexo' => 'F',
            'endereco' => 'Rua Teste, 123',
            'telefone' => '11988887777',
            'email' => 'paciente@example.com'
        ]);

        // Criar prontuário
        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
            'historico_medico' => 'Histórico de teste',
        ]);

        // Criar consulta
        $consulta = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'data_hora' => Carbon::now()->addDays(2),
            'tipo_consulta' => 'primeira_consulta',
            'queixa_principal' => 'Dor de cabeça',
            'status' => 'agendada',
            'prontuario_id' => $prontuario->id,
        ]);

        // Criar plantão
        $plantao = MedicoPlantao::create([
            'medico_id' => $medico->id,
            'data_inicio' => Carbon::now()->addDays(5),
            'data_fim' => Carbon::now()->addDays(5),
            'hora_inicio' => '08:00',
            'hora_fim' => '18:00',
            'recorrente' => false,
            'dia_semana' => null,
            'status' => 'ativo',
            'capacidade_consultas' => 10,
            'notas' => 'Plantão de teste',
        ]);

        return [
            'medico' => $medico,
            'userMedico' => $userMedico,
            'userAdmin' => $userAdmin,
            'paciente' => $paciente,
            'prontuario' => $prontuario,
            'consulta' => $consulta,
            'plantao' => $plantao,
        ];
    }

    /**
     * Testa o carregamento do formulário de remarcação
     */
    public function test_edit_shows_remarcacao_form()
    {
        $data = $this->setupTestData();

        // Acessa formulário como admin
        $response = $this->actingAs($data['userAdmin'])
            ->get(route('consultas.remarcacao.edit', $data['consulta']->id));

        $response->assertStatus(200);
        $response->assertSee($data['paciente']->nome);
        $response->assertSee($data['medico']->nome);
    }

    /**
     * Testa acesso não autorizado à remarcação
     */
    public function test_edit_unauthorized_access()
    {
        $data = $this->setupTestData();

        // Cria outro médico que não tem relação com esta consulta
        $outroMedico = Medico::create([
            'nome' => 'Dr. Outro',
            'crm' => '54321SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '987.654.321-00',
            'telefone' => '11988887777',
            'email' => 'outro.medico@example.com',
        ]);

        $outroUser = User::factory()->create(['tipo' => 'medico']);
        $outroMedico->user_id = $outroUser->id;
        $outroMedico->save();

        // Tenta acessar como médico não autorizado
        $response = $this->actingAs($outroUser)
            ->get(route('consultas.remarcacao.edit', $data['consulta']->id));

        $response->assertRedirect();
    }

    /**
     * Testa a remarcação de uma consulta
     */
    public function test_update_remarca_consulta()
    {
        $data = $this->setupTestData();

        // Mock do serviço de notificação
        $mockNotificacaoService = Mockery::mock(NotificacaoService::class);
        $mockNotificacaoService->shouldReceive('notificarAlteracaoConsulta')->once()->andReturn(true);
        $this->app->instance(NotificacaoService::class, $mockNotificacaoService);

        // Dados para remarcação
        $remarcacaoData = [
            'data' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'hora' => '14:00',
            'plantao_id' => $data['plantao']->id,
            'observacoes' => 'Consulta remarcada a pedido do paciente',
        ];

        // Enviar requisição para remarcar consulta
        $response = $this->actingAs($data['userAdmin'])
            ->put(route('consultas.remarcacao.update', $data['consulta']->id), $remarcacaoData);

        // Verificar redirecionamento
        $response->assertRedirect(route('consultas.show', $data['consulta']->id));

        // Verificar consulta atualizada no banco de dados
        $this->assertDatabaseHas('consultas', [
            'id' => $data['consulta']->id,
            'status' => 'agendada',
            'plantao_id' => $data['plantao']->id
        ]);
    }

    /**
     * Testa a API de verificação de disponibilidade
     */
    public function test_verificar_disponibilidade()
    {
        $data = $this->setupTestData();

        // Dados para requisição
        $requestData = [
            'medico_id' => $data['medico']->id,
            'data' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'consulta_id' => $data['consulta']->id,
        ];

        // Enviar requisição para verificar disponibilidade
        $response = $this->actingAs($data['userAdmin'])
            ->post('/api/consultas/verificar-disponibilidade', $requestData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'plantoes'
        ]);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
