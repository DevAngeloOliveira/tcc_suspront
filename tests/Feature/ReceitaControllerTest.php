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
use App\Models\Receita;
use Carbon\Carbon;

class ReceitaControllerTest extends TestCase
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

        return [
            'medico' => $medico,
            'userMedico' => $userMedico,
            'paciente' => $paciente,
            'prontuario' => $prontuario,
            'consulta' => $consulta,
        ];
    }

    /**
     * Teste de listagem de receitas
     */
    public function test_index_displays_receitas()
    {
        $data = $this->setupTestData();

        // Criar uma receita
        $receita = Receita::create([
            'prontuario_id' => $data['prontuario']->id,
            'medico_id' => $data['medico']->id,
            'consulta_id' => $data['consulta']->id,
            'descricao' => 'Receita de teste',
            'medicamentos' => 'Paracetamol 500mg',
            'posologia' => '1 comprimido de 8 em 8 horas',
            'observacoes' => 'Tomar após as refeições',
            'validade' => Carbon::now()->addMonths(6),
        ]);

        // Testar acesso como médico
        $response = $this->actingAs($data['userMedico'])
            ->get(route('receitas.index'));

        $response->assertStatus(200);
        $response->assertSee('Receita de teste');
        $response->assertSee('Paracetamol 500mg');
    }

    /**
     * Teste de criação de receita
     */
    public function test_store_creates_receita()
    {
        $data = $this->setupTestData();

        // Dados para criação da receita
        $receitaData = [
            'prontuario_id' => $data['prontuario']->id,
            'medico_id' => $data['medico']->id,
            'consulta_id' => $data['consulta']->id,
            'descricao' => 'Nova receita de teste',
            'medicamentos' => 'Dipirona 500mg',
            'posologia' => '1 comprimido de 6 em 6 horas',
            'observacoes' => 'Em caso de febre ou dor',
            'validade' => Carbon::now()->addMonths(3)->format('Y-m-d'),
        ];

        // Enviar requisição para criar receita
        $response = $this->actingAs($data['userMedico'])
            ->post(route('receitas.store'), $receitaData);

        // Verificar redirecionamento e dados no banco
        $response->assertRedirect();
        $this->assertDatabaseHas('receitas', [
            'descricao' => 'Nova receita de teste',
            'medicamentos' => 'Dipirona 500mg',
            'prontuario_id' => $data['prontuario']->id
        ]);
    }

    /**
     * Teste de visualização de receita
     */
    public function test_show_displays_receita()
    {
        $data = $this->setupTestData();

        // Criar uma receita
        $receita = Receita::create([
            'prontuario_id' => $data['prontuario']->id,
            'medico_id' => $data['medico']->id,
            'consulta_id' => $data['consulta']->id,
            'descricao' => 'Receita para visualização',
            'medicamentos' => 'Ibuprofeno 400mg',
            'posologia' => '1 comprimido de 8 em 8 horas',
            'observacoes' => 'Não tomar em jejum',
            'validade' => Carbon::now()->addMonths(6),
        ]);

        // Acessar página de visualização da receita
        $response = $this->actingAs($data['userMedico'])
            ->get(route('receitas.show', $receita->id));

        $response->assertStatus(200);
        $response->assertSee('Receita para visualização');
        $response->assertSee('Ibuprofeno 400mg');
    }

    /**
     * Teste de impressão de receita
     */
    public function test_imprimir_receita()
    {
        $data = $this->setupTestData();

        // Criar uma receita
        $receita = Receita::create([
            'prontuario_id' => $data['prontuario']->id,
            'medico_id' => $data['medico']->id,
            'consulta_id' => $data['consulta']->id,
            'descricao' => 'Receita para impressão',
            'medicamentos' => 'Amoxicilina 500mg',
            'posologia' => '1 comprimido de 8 em 8 horas',
            'observacoes' => 'Tomar o tratamento completo',
            'validade' => Carbon::now()->addMonths(1),
        ]);

        // Acessar rota de impressão
        $response = $this->actingAs($data['userMedico'])
            ->get(route('receitas.imprimir', $receita->id));

        $response->assertStatus(200);
        // Verificamos apenas o status pois o retorno é um PDF
    }
}
