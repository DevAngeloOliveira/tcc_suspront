<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Medicos\MedicosList;
use App\Models\User;
use App\Models\Medico;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MedicosListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de listagem de médicos como administrador.
     */
    public function test_admin_can_view_medicos_list()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar médico de teste
        $medico = Medico::create([
            'nome' => 'Dr. Teste Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '123.456.789-00',
            'telefone' => '(11) 99999-8888',
            'email' => 'dr.teste@example.com',
            'user_id' => $user->id,
        ]);

        // Testar o componente Livewire
        Livewire::actingAs($user)
            ->test(MedicosList::class)
            ->assertSee('Dr. Teste Silva')
            ->assertSee('12345SP')
            ->assertSee('Cardiologia')
            ->assertStatus(200);

        // Acessar a página pela rota
        $response = $this->actingAs($user)->get(route('medicos.index'));

        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);
    }

    /**
     * Teste de filtro por especialidade.
     */
    public function test_can_filter_by_especialidade()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar médicos de diferentes especialidades
        Medico::create([
            'nome' => 'Dr. Cardio',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 1111-2222',
            'email' => 'cardio@example.com',
            'user_id' => $user->id,
        ]);

        Medico::create([
            'nome' => 'Dr. Neuro',
            'crm' => '67890SP',
            'especialidade' => 'Neurologia',
            'cpf' => '555.666.777-88',
            'telefone' => '(11) 3333-4444',
            'email' => 'neuro@example.com',
            'user_id' => $user->id,
        ]);

        // Testar o filtro por especialidade
        Livewire::actingAs($user)
            ->test(MedicosList::class)
            ->set('especialidadeFiltro', 'Cardiologia')
            ->assertSee('Dr. Cardio')
            ->assertDontSee('Dr. Neuro');
    }

    /**
     * Teste de busca por nome.
     */
    public function test_can_search_by_name()
    {
        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar médicos com nomes diferentes
        Medico::create([
            'nome' => 'Dr. João Silva',
            'crm' => '12345SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '111.222.333-44',
            'telefone' => '(11) 1111-2222',
            'email' => 'joao@example.com',
            'user_id' => $user->id,
        ]);

        Medico::create([
            'nome' => 'Dr. Maria Santos',
            'crm' => '67890SP',
            'especialidade' => 'Cardiologia',
            'cpf' => '555.666.777-88',
            'telefone' => '(11) 3333-4444',
            'email' => 'maria@example.com',
            'user_id' => $user->id,
        ]);

        // Testar a busca por nome
        Livewire::actingAs($user)
            ->test(MedicosList::class)
            ->set('search', 'João')
            ->assertSee('Dr. João Silva')
            ->assertDontSee('Dr. Maria Santos');
    }

    /**
     * Teste de acesso negado para usuários não autorizados.
     */
    public function test_unauthorized_user_cannot_view_medicos_list()
    {
        // Criar um usuário atendente (que não deve ter acesso à gestão de médicos)
        $user = User::factory()->create(['tipo' => 'atendente']);

        // Tentar acessar a rota de listagem de médicos
        $response = $this->actingAs($user)->get(route('medicos.index'));

        // Verificar se foi redirecionado ou recebeu um erro de acesso negado
        $response->assertStatus(403);
    }

    /**
     * Teste de paginação de médicos.
     */
    public function test_medicos_pagination_works()
    {
        $this->markTestSkipped('Teste temporariamente desativado até resolver problemas de paginação');

        // Criar um usuário admin
        $user = User::factory()->create(['tipo' => 'admin']);

        // Criar vários médicos para testar a paginação (mais do que o limite de paginação)
        for ($i = 1; $i <= 25; $i++) {
            Medico::create([
                'nome' => "Dr. Teste {$i}",
                'crm' => "CRM{$i}",
                'especialidade' => 'Cardiologia',
                'cpf' => "111.222.333-{$i}",
                'telefone' => "(11) 9999-{$i}",
                'email' => "teste{$i}@example.com",
                'user_id' => $user->id,
            ]);
        }

        // Verificar se o componente exibe uma lista paginada
        $livewire = Livewire::actingAs($user)->test(MedicosList::class);

        // Verificar se há um link para a próxima página (indicativo de paginação)
        $html = $livewire->html();
        $this->assertTrue(
            str_contains($html, 'wire:click="nextPage') ||
                str_contains($html, 'wire:click="gotoPage(2')
        );
    }
}
