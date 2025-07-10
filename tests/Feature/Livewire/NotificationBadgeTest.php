<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Notificacoes\NotificationBadge;
use App\Models\Notificacao;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NotificationBadgeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa o funcionamento do badge de notificação
     */
    public function test_notification_badge_shows_correct_count()
    {
        // Criar um usuário
        $user = User::factory()->create(['tipo' => 'medico']);

        // Criar algumas notificações não lidas
        Notificacao::create([
            'user_id' => $user->id,
            'tipo' => 'nova_consulta',
            'titulo' => 'Nova consulta 1',
            'mensagem' => 'Nova consulta agendada 1',
            'lida' => false
        ]);

        Notificacao::create([
            'user_id' => $user->id,
            'tipo' => 'nova_consulta',
            'titulo' => 'Nova consulta 2',
            'mensagem' => 'Nova consulta agendada 2',
            'lida' => false
        ]);

        // Criar uma notificação lida
        Notificacao::create([
            'user_id' => $user->id,
            'tipo' => 'confirmacao_consulta',
            'titulo' => 'Consulta confirmada',
            'mensagem' => 'Consulta confirmada pelo paciente',
            'lida' => true,
            'lida_em' => Carbon::now()->subHour(),
        ]);

        // Testar o componente
        Livewire::actingAs($user)
            ->test(NotificationBadge::class)
            ->assertSet('contadorNotificacoes', 2) // Deve ter 2 notificações não lidas
            ->assertSeeHtml('badge') // Deve mostrar o badge
            ->call('toggleDropdown')
            ->assertSet('mostrarDropdown', true) // Dropdown deve estar aberto após clicar
            ->call('fecharDropdown')
            ->assertSet('mostrarDropdown', false); // Dropdown deve estar fechado
    }

    /**
     * Testa a atualização do contador de notificações
     */
    public function test_notification_badge_updates_counter()
    {
        // Criar um usuário
        $user = User::factory()->create(['tipo' => 'medico']);

        // Criar uma notificação não lida
        Notificacao::create([
            'user_id' => $user->id,
            'tipo' => 'nova_consulta',
            'titulo' => 'Nova consulta',
            'mensagem' => 'Nova consulta agendada',
            'lida' => false
        ]);

        // Testar o componente
        $component = Livewire::actingAs($user)
            ->test(NotificationBadge::class)
            ->assertSet('contadorNotificacoes', 1); // Inicialmente tem 1 notificação

        // Criar mais uma notificação
        Notificacao::create([
            'user_id' => $user->id,
            'tipo' => 'alteracao_consulta',
            'titulo' => 'Consulta alterada',
            'mensagem' => 'Uma consulta foi remarcada',
            'lida' => false
        ]);

        // Atualizar o contador
        $component->call('atualizarContador')
            ->assertSet('contadorNotificacoes', 2); // Agora deve ter 2 notificações
    }

    /**
     * Testa que o badge não aparece quando não há notificações
     */
    public function test_notification_badge_hidden_when_no_notifications()
    {
        // Criar um usuário
        $user = User::factory()->create(['tipo' => 'medico']);

        // Testar o componente sem notificações
        Livewire::actingAs($user)
            ->test(NotificationBadge::class)
            ->assertSet('contadorNotificacoes', 0) // Não deve ter notificações
            ->assertDontSeeHtml('<span class="badge rounded-pill bg-danger">'); // Badge não deve aparecer
    }
}
