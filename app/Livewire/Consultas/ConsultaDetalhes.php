<?php

namespace App\Livewire\Consultas;

use App\Models\Consulta;
use App\Models\Prontuario;
use App\Models\Exame;
use App\Services\NotificacaoService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ConsultaDetalhes extends Component
{
    public $consulta;
    public $prontuario;
    public $exames = [];

    // Para formulário de evolução
    public $evolucaoText;
    public $mostrarFormularioEvolucao = false;

    // Para formulário de exame
    public $novoExame = [
        'tipo' => '',
        'descricao' => '',
        'data_solicitacao' => '',
        'resultado' => '',
        'data_resultado' => '',
        'observacoes' => ''
    ];
    public $mostrarFormularioExame = false;

    // Para confirmação
    public $confirmandoAcao = false;
    public $acaoConfirmada = '';
    public $mensagemConfirmacao = '';

    public function mount($consultaId)
    {
        $this->consulta = Consulta::with(['paciente', 'medico', 'prontuario', 'receitas'])->findOrFail($consultaId);

        if ($this->consulta->prontuario) {
            $this->prontuario = $this->consulta->prontuario;
            $this->exames = Exame::where('prontuario_id', $this->prontuario->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $this->novoExame['data_solicitacao'] = Carbon::today()->format('Y-m-d');
    }

    public function atualizarStatus($status)
    {
        // Verificar permissões
        if (($status === 'em_andamento' || $status === 'concluida') &&
            !(Auth::user()->tipo === 'medico' && Auth::user()->medico->id === $this->consulta->medico_id)
        ) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Você não tem permissão para esta ação.']);
            return;
        }

        $statusAnterior = $this->consulta->status;
        $this->consulta->status = $status;
        $this->consulta->save();

        // Enviar notificação adequada
        $notificacaoService = app(NotificacaoService::class);

        if ($status === 'cancelada') {
            $notificacaoService->notificarCancelamentoConsulta($this->consulta);
        } elseif ($status === 'confirmada') {
            $notificacaoService->notificarConfirmacaoConsulta($this->consulta);
        }

        // Criar prontuário se ainda não existir e consulta for iniciada
        if ($status === 'em_andamento' && !$this->consulta->prontuario) {
            $prontuario = new Prontuario();
            $prontuario->paciente_id = $this->consulta->paciente_id;
            $prontuario->medico_id = $this->consulta->medico_id;
            $prontuario->consulta_id = $this->consulta->id;
            $prontuario->data_criacao = Carbon::now();
            $prontuario->evolucao = 'Consulta iniciada em ' . Carbon::now()->format('d/m/Y H:i');
            $prontuario->save();

            $this->consulta->refresh();
            $this->prontuario = $this->consulta->prontuario;
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Status da consulta atualizado para ' .
                str_replace('_', ' ', ucfirst($status)) . '!'
        ]);
    }

    public function confirmarAcao($acao, $mensagem)
    {
        $this->acaoConfirmada = $acao;
        $this->mensagemConfirmacao = $mensagem;
        $this->confirmandoAcao = true;
    }

    public function cancelarConfirmacao()
    {
        $this->confirmandoAcao = false;
        $this->acaoConfirmada = '';
        $this->mensagemConfirmacao = '';
    }

    public function executarAcaoConfirmada()
    {
        if ($this->acaoConfirmada === 'cancelar') {
            $this->atualizarStatus('cancelada');
        }

        $this->confirmandoAcao = false;
        $this->acaoConfirmada = '';
        $this->mensagemConfirmacao = '';
    }

    public function toggleFormularioEvolucao()
    {
        $this->mostrarFormularioEvolucao = !$this->mostrarFormularioEvolucao;
        $this->evolucaoText = '';
    }

    public function salvarEvolucao()
    {
        if (!$this->prontuario) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Nenhum prontuário associado a esta consulta.']);
            return;
        }

        $this->validate([
            'evolucaoText' => 'required|min:5',
        ]);

        $dataHora = Carbon::now()->format('d/m/Y H:i');
        $novaEvolucao = "[{$dataHora}] - {$this->evolucaoText}";

        if (empty($this->prontuario->evolucao)) {
            $this->prontuario->evolucao = $novaEvolucao;
        } else {
            $this->prontuario->evolucao .= "\n\n" . $novaEvolucao;
        }

        $this->prontuario->save();

        $this->mostrarFormularioEvolucao = false;
        $this->evolucaoText = '';

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Evolução adicionada com sucesso!']);
    }

    public function toggleFormularioExame()
    {
        $this->mostrarFormularioExame = !$this->mostrarFormularioExame;
        $this->resetNovoExame();
    }

    public function resetNovoExame()
    {
        $this->novoExame = [
            'tipo' => '',
            'descricao' => '',
            'data_solicitacao' => Carbon::today()->format('Y-m-d'),
            'resultado' => '',
            'data_resultado' => '',
            'observacoes' => ''
        ];
    }

    public function salvarExame()
    {
        if (!$this->prontuario) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Nenhum prontuário associado a esta consulta.']);
            return;
        }

        $this->validate([
            'novoExame.tipo' => 'required',
            'novoExame.descricao' => 'required|min:5',
            'novoExame.data_solicitacao' => 'required|date',
        ]);

        $exame = new Exame();
        $exame->prontuario_id = $this->prontuario->id;
        $exame->tipo = $this->novoExame['tipo'];
        $exame->descricao = $this->novoExame['descricao'];
        $exame->data_solicitacao = $this->novoExame['data_solicitacao'];
        $exame->resultado = $this->novoExame['resultado'];
        $exame->data_resultado = $this->novoExame['data_resultado'];
        $exame->observacoes = $this->novoExame['observacoes'];
        $exame->status = empty($this->novoExame['resultado']) ? 'pendente' : 'concluido';

        $exame->save();

        $this->exames = Exame::where('prontuario_id', $this->prontuario->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->mostrarFormularioExame = false;
        $this->resetNovoExame();

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Exame adicionado com sucesso!']);
    }

    public function render()
    {
        return view('livewire.consultas.consulta-detalhes');
    }
}
