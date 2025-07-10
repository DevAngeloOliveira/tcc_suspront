<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Medico;
use App\Models\Notificacao;
use App\Services\NotificacaoService;
use Carbon\Carbon;

class NotificacaoControllerTest extends TestCase
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

        // Criar notificações
        $notificacoes = [];

        // Notificação não lida
        $notificacoes[] = Notificacao::create([
            'user_id' => $userMedico->id,
            'tipo' => 'nova_consulta',
            'titulo' => 'Nova consulta agendada',
            'mensagem' => 'Uma nova consulta foi agendada para 25/05/2025 às 14:00 com o paciente Teste.',
            'dados_extras' => [
                'consulta_id' => 1,
                'paciente_id' => 1,
                'data_hora' => '2025-05-25 14:00:00'
            ],
            'lida' => false
        ]);

        // Notificação já lida
        $notificacoes[] = Notificacao::create([
            'user_id' => $userMedico->id,
            'tipo' => 'confirmacao_consulta',
            'titulo' => 'Consulta confirmada',
            'mensagem' => 'A consulta agendada para 23/05/2025 às 10:00 foi confirmada.',
            'dados_extras' => [
                'consulta_id' => 2,
                'paciente_id' => 1,
                'data_hora' => '2025-05-23 10:00:00'
            ],
            'lida' => true,
            'lida_em' => Carbon::now()->subDay()
        ]);

        return [
            'userMedico' => $userMedico,
            'medico' => $medico,
            'notificacoes' => $notificacoes,
        ];
    }

    /**
     * Teste de listagem de notificações
     */
    public function test_index_displays_notificacoes()
    {
        $data = $this->setupTestData();

        $response = $this->actingAs($data['userMedico'])
            ->get(route('notificacoes.index'));

        $response->assertStatus(200);
        $response->assertSee('Nova consulta agendada');
        $response->assertSee('Consulta confirmada');
    }

    /**
     * Teste de marcação de notificação como lida
     */
    public function test_marcar_como_lida()
    {
        $data = $this->setupTestData();
        $notificacao = $data['notificacoes'][0]; // Notificação não lida

        $response = $this->actingAs($data['userMedico'])
            ->put(route('notificacoes.marcar-como-lida', $notificacao->id));

        $response->assertRedirect();

        // Verificar se a notificação foi marcada como lida
        $this->assertDatabaseHas('notificacoes', [
            'id' => $notificacao->id,
            'lida' => true
        ]);
    }

    /**
     * Teste de marcação de todas notificações como lidas
     */
    public function test_marcar_todas_como_lidas()
    {
        $data = $this->setupTestData();

        $response = $this->actingAs($data['userMedico'])
            ->put(route('notificacoes.marcar-todas-como-lidas'));

        $response->assertRedirect();

        // Verificar se todas as notificações foram marcadas como lidas
        $notificacoes = Notificacao::where('user_id', $data['userMedico']->id)->get();
        foreach ($notificacoes as $notificacao) {
            $this->assertTrue($notificacao->lida);
            $this->assertNotNull($notificacao->lida_em);
        }
    }

    /**
     * Teste de acesso não autorizado
     */
    public function test_unauthorized_access()
    {
        $data = $this->setupTestData();
        $outroUser = User::factory()->create(['tipo' => 'medico']);
        $notificacao = $data['notificacoes'][0];

        // Outro usuário tenta marcar como lida uma notificação que não é sua
        $response = $this->actingAs($outroUser)
            ->put(route('notificacoes.marcar-como-lida', $notificacao->id));

        $response->assertStatus(404); // Deve retornar 404 pois a notificação não é encontrada para este usuário
    }

    /**
     * Testa o serviço de notificação
     */
    public function test_notificacao_service_criar_notificacao()
    {
        $data = $this->setupTestData();

        $notificacaoService = new NotificacaoService();
        $novaNotificacao = $notificacaoService->criarNotificacao(
            $data['userMedico']->id,
            'cancelamento_consulta',
            'Consulta cancelada',
            'A consulta foi cancelada pelo paciente',
            ['consulta_id' => 3]
        );

        $this->assertInstanceOf(Notificacao::class, $novaNotificacao);
        $this->assertEquals('Consulta cancelada', $novaNotificacao->titulo);
        $this->assertEquals($data['userMedico']->id, $novaNotificacao->user_id);
        $this->assertFalse($novaNotificacao->lida);
    }
}
