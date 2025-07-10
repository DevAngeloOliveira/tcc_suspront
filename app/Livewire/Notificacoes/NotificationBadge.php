<?php

namespace App\Livewire\Notificacoes;

use App\Models\Notificacao;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBadge extends Component
{
    public $contadorNotificacoes = 0;
    public $mostrarDropdown = false;

    protected $listeners = [
        'novaNotificacao' => 'atualizarContador',
        'echo:notificacoes,NotificacaoRecebida' => 'atualizarContador'
    ];

    public function mount()
    {
        $this->atualizarContador();
    }

    public function atualizarContador()
    {
        if (Auth::check()) {
            $this->contadorNotificacoes = Notificacao::where('user_id', Auth::id())
                ->where('lida', false)
                ->count();
        }
    }

    public function toggleDropdown()
    {
        $this->mostrarDropdown = !$this->mostrarDropdown;
    }

    public function fecharDropdown()
    {
        $this->mostrarDropdown = false;
    }

    public function render()
    {
        return view('livewire.notificacoes.notification-badge');
    }
}
