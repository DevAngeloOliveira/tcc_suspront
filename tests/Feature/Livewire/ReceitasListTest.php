<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Receitas\ReceitasList;
use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Receita;
use App\Models\Prontuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Carbon\Carbon;

class ReceitasListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de listagem de receitas como médico.
     */
    public function test_medico_can_view_receitas_list()
    {
        // Criar um usuário médico
        $userMedico = User::factory()->create(['tipo' => 'medico']);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. João Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 91111-2222',
            'email' => 'joao.silva@example.com',
            'user_id' => $userMedico->id,
        ]);

        // Criar um paciente
        $userPaciente = User::factory()->create(['tipo' => 'admin']); // Usando admin como tipo válido
        $paciente = Paciente::create([
            'nome' => 'Maria Souza',
            'cpf' => '222.333.444-55',
            'data_nascimento' => '1990-01-01',
            'telefone' => '(11) 92222-3333',
            'email' => 'maria.souza@example.com',
            'user_id' => $userPaciente->id,
            'cartao_sus' => '123456789012345',
        ]);

        // Criar prontuário para o paciente
        $prontuario = Prontuario::create([
            'paciente_id' => $paciente->id,
            'data_criacao' => Carbon::now(),
            'alergias' => 'Nenhuma',
            'medicacoes' => 'Nenhuma',
            'historico_medico' => 'Sem histórico relevante',
        ]);

        // Criar receita de teste
        $receita = Receita::create([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
            'prontuario_id' => $prontuario->id,
            'data' => Carbon::now(),
            'medicamentos' => 'Dipirona 500mg',
            'posologia' => '1 comprimido a cada 6 horas se necessário',
            'observacoes' => 'Tomar com bastante água',
        ]);

        // Testar o componente Livewire
        Livewire::actingAs($userMedico)
            ->test(ReceitasList::class)
            ->assertSee('Maria Souza')
            ->assertSee('Dipirona 500mg');

        // Acessar a página pela rota
        $response = $this->actingAs($userMedico)->get(route('receitas.index'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);
    }

    /**
     * Teste de listagem de receitas filtrada por paciente.
     */
    public function test_can_filter_receitas_by_paciente()
    {
        // Criar um usuário médico
        $userMedico = User::factory()->create(['tipo' => 'medico']);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. João Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 91111-2222',
            'email' => 'joao.silva@example.com',
            'user_id' => $userMedico->id,
        ]);

        // Criar pacientes
        $userPaciente1 = User::factory()->create(['tipo' => 'admin']); // Usando admin como tipo válido
        $paciente1 = Paciente::create([
            'nome' => 'Maria Souza',
            'cpf' => '222.333.444-55',
            'data_nascimento' => '1990-01-01',
            'telefone' => '(11) 92222-3333',
            'email' => 'maria.souza@example.com',
            'user_id' => $userPaciente1->id,
            'cartao_sus' => '123456789012345',
        ]);

        $userPaciente2 = User::factory()->create(['tipo' => 'admin']); // Usando admin como tipo válido
        $paciente2 = Paciente::create([
            'nome' => 'Carlos Santos',
            'cpf' => '333.444.555-66',
            'data_nascimento' => '1985-05-15',
            'telefone' => '(11) 93333-4444',
            'email' => 'carlos.santos@example.com',
            'user_id' => $userPaciente2->id,
            'cartao_sus' => '987654321098765',
        ]);

        // Criar prontuários para os pacientes
        $prontuario1 = Prontuario::create([
            'paciente_id' => $paciente1->id,
            'data_criacao' => Carbon::now(),
            'alergias' => 'Nenhuma',
            'medicacoes' => 'Nenhuma',
            'historico_medico' => 'Sem histórico relevante',
        ]);

        $prontuario2 = Prontuario::create([
            'paciente_id' => $paciente2->id,
            'data_criacao' => Carbon::now(),
            'alergias' => 'Nenhuma',
            'medicacoes' => 'Nenhuma',
            'historico_medico' => 'Sem histórico relevante',
        ]);

        // Criar receitas para diferentes pacientes
        $receita1 = Receita::create([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente1->id,
            'prontuario_id' => $prontuario1->id,
            'data' => Carbon::now(),
            'medicamentos' => 'Dipirona 500mg',
            'posologia' => '1 comprimido a cada 6 horas se necessário',
            'observacoes' => 'Tomar com bastante água',
        ]);

        $receita2 = Receita::create([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente2->id,
            'prontuario_id' => $prontuario2->id,
            'data' => Carbon::now(),
            'medicamentos' => 'Amoxicilina 500mg',
            'posologia' => '1 cápsula de 8 em 8 horas',
            'observacoes' => 'Tomar com bastante água',
        ]);

        // Testar o componente Livewire com filtro por paciente
        Livewire::actingAs($userMedico)
            ->test(ReceitasList::class, ['paciente_id' => $paciente1->id])
            ->assertSee('Maria Souza')
            ->assertSee('Dipirona 500mg')
            ->assertDontSee('Carlos Santos')
            ->assertDontSee('Amoxicilina 500mg');

        Livewire::actingAs($userMedico)
            ->test(ReceitasList::class, ['paciente_id' => $paciente2->id])
            ->assertSee('Carlos Santos')
            ->assertSee('Amoxicilina 500mg')
            ->assertDontSee('Maria Souza')
            ->assertDontSee('Dipirona 500mg');
    }

    /**
     * Teste de listagem de receitas filtrada por prontuário.
     */
    public function test_can_filter_receitas_by_prontuario()
    {
        // Criar um usuário médico
        $userMedico = User::factory()->create(['tipo' => 'medico']);

        // Criar um médico
        $medico = Medico::create([
            'nome' => 'Dr. João Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 91111-2222',
            'email' => 'joao.silva@example.com',
            'user_id' => $userMedico->id,
        ]);

        // Criar paciente
        $userPaciente = User::factory()->create(['tipo' => 'admin']); // Usando admin como tipo válido
        $paciente = Paciente::create([
            'nome' => 'Maria Souza',
            'cpf' => '222.333.444-55',
            'data_nascimento' => '1990-01-01',
            'telefone' => '(11) 92222-3333',
            'email' => 'maria.souza@example.com',
            'user_id' => $userPaciente->id,
            'cartao_sus' => '123456789012345',
        ]);

        // Criar dois prontuários para o mesmo paciente
        $prontuario1 = Prontuario::create([
            'paciente_id' => $paciente->id,
            'data_criacao' => Carbon::now()->subMonth(),
            'alergias' => 'Nenhuma',
            'medicacoes' => 'Nenhuma',
            'historico_medico' => 'Primeiro prontuário',
        ]);

        $prontuario2 = Prontuario::create([
            'paciente_id' => $paciente->id,
            'data_criacao' => Carbon::now(),
            'alergias' => 'Nenhuma',
            'medicacoes' => 'Nenhuma',
            'historico_medico' => 'Segundo prontuário',
        ]);

        // Criar receitas para diferentes prontuários
        $receita1 = Receita::create([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
            'prontuario_id' => $prontuario1->id,
            'data' => Carbon::now()->subMonth(),
            'medicamentos' => 'Dipirona 500mg',
            'posologia' => '1 comprimido a cada 6 horas se necessário',
            'observacoes' => 'Primeira receita',
        ]);

        $receita2 = Receita::create([
            'medico_id' => $medico->id,
            'paciente_id' => $paciente->id,
            'prontuario_id' => $prontuario2->id,
            'data' => Carbon::now(),
            'medicamentos' => 'Amoxicilina 500mg',
            'posologia' => '1 cápsula de 8 em 8 horas',
            'observacoes' => 'Segunda receita',
        ]);

        // Testar o componente Livewire com filtro por prontuário
        Livewire::actingAs($userMedico)
            ->test(ReceitasList::class, ['prontuario_id' => $prontuario1->id])
            ->assertSee('Dipirona 500mg')
            ->assertSee('Primeira receita')
            ->assertDontSee('Amoxicilina 500mg')
            ->assertDontSee('Segunda receita');

        Livewire::actingAs($userMedico)
            ->test(ReceitasList::class, ['prontuario_id' => $prontuario2->id])
            ->assertSee('Amoxicilina 500mg')
            ->assertSee('Segunda receita')
            ->assertDontSee('Dipirona 500mg')
            ->assertDontSee('Primeira receita');
    }
}
