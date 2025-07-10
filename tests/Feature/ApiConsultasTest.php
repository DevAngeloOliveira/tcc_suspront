<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Medico;
use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Prontuario;
use Carbon\Carbon;

class ApiConsultasTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa a rota de obtenção de horários disponíveis
     */
    public function test_horarios_disponiveis_route()
    {
        // Criar um usuário admin para autenticação
        $user = User::factory()->create([
            'tipo' => 'admin'
        ]);

        // Criar um médico para o teste
        $medico = Medico::create([
            'nome' => 'Dr. Teste',
            'crm' => '12345SP',
            'especialidade' => 'Clínico Geral',
            'cpf' => '123.456.789-00',
            'telefone' => '11999998888',
            'email' => 'dr.teste@example.com',
            'user_id' => $user->id
        ]);

        // Criar paciente e prontuário
        $paciente = Paciente::create([
            'nome' => 'Paciente API',
            'cpf' => '123.456.789-10',
            'cartao_sus' => '123456789101112',
            'data_nascimento' => '1990-01-01',
            'sexo' => 'M',
            'endereco' => 'Rua API, 123',
            'telefone' => '(11) 99999-8888',
            'email' => 'paciente.api@example.com'
        ]);

        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
        ]);

        // Criar uma consulta para este médico
        $consulta = Consulta::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id,
            'prontuario_id' => $prontuario->id,
            'data_hora' => Carbon::today()->setHour(10)->setMinute(0)->setSecond(0),
            'tipo_consulta' => 'Rotina',
            'queixa_principal' => 'Teste',
            'status' => 'agendada'
        ]);

        $response = $this->actingAs($user)
            ->get('/api/consultas/horarios-disponiveis?data=' .
                Carbon::today()->format('Y-m-d') .
                '&medico_id=' . $medico->id);

        $response->assertStatus(200)
            ->assertJsonStructure(['horarios']);

        // Verificar que o horário da consulta existente não está disponível
        $horariosDisponiveis = $response->json('horarios');
        $this->assertNotContains('10:00', $horariosDisponiveis);
    }

    /**
     * Testa a rota de obtenção de médicos por especialidade
     */
    public function test_medicos_por_especialidade_route()
    {
        // Criar um usuário admin para autenticação
        $user = User::factory()->create([
            'tipo' => 'admin'
        ]);

        // Criar médicos de diferentes especialidades
        $medico1 = Medico::create([
            'nome' => 'Dr. Cardiologista',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '11999998888',
            'email' => 'dr.cardio@example.com',
            'user_id' => $user->id
        ]);

        $medico2 = Medico::create([
            'nome' => 'Dr. Ortopedista',
            'crm' => '54321SP',
            'especialidade' => 'Ortopedia',
            'cpf' => '222.333.444-55',
            'telefone' => '11988887777',
            'email' => 'dr.ortopedia@example.com',
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)
            ->get('/api/medicos/especialidade/Cardiologia');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'nome' => 'Dr. Cardiologista',
                'especialidade' => 'Cardiologia'
            ]);
    }
}
