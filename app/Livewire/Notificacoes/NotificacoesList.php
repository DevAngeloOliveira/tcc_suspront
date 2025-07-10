<?php

namespace App\Livewire\Notificacoes;

use App\Models\Notificacao;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificacoesList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $mostrarDetalhes = false;
    public $notificacaoSelecionada;
    public $mostrarTodasNotificacoes = false;

    protected $listeners = ['novaNotificacao' => '$refresh'];

    public function mount()
    {
        // Inicializar propriedades
    }

    public function getNotificacoesProperty()
    {
        $query = Notificacao::where('user_id', Auth::id());

        if (!$this->mostrarTodasNotificacoes) {
            $query->naoLidas();
        }

        return $query->orderBy('created_at', 'desc')->paginate(5);
    }

    public function getTotalNaoLidasProperty()
    {
        return Notificacao::where('user_id', Auth::id())->naoLidas()->count();
    }

    public function verDetalhes(int $id)
    {
        $this->notificacaoSelecionada = Notificacao::findOrFail($id);
        $this->mostrarDetalhes = true;

        // Marcar como lida
        if (!$this->notificacaoSelecionada->lida) {
            $this->notificacaoSelecionada->marcarComoLida();
        }
    }

    public function fecharDetalhes()
    {
        $this->mostrarDetalhes = false;
        $this->notificacaoSelecionada = null;
    }

    public function marcarComoLida($id)
    {
        $notificacao = Notificacao::findOrFail($id);
        $notificacao->marcarComoLida();
    }

    public function marcarTodasComoLidas()
    {
        Notificacao::where('user_id', Auth::id())
            ->naoLidas()
            ->update([
                'lida' => true,
                'lida_em' => now()
            ]);
    }

    public function toggleMostrarTodas()
    {
        $this->mostrarTodasNotificacoes = !$this->mostrarTodasNotificacoes;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.notificacoes.notificacoes-list', [
            'notificacoes' => $this->notificacoes,
            'totalNaoLidas' => $this->totalNaoLidas
        ]);
    }
}
