<?php

namespace App\Livewire\Pacientes;

use App\Models\Paciente;
use App\Models\Consulta;
use App\Models\Exame;
use Livewire\Component;
use Livewire\WithPagination;

class PacienteDetalhes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $paciente;
    public $pacienteId;
    public $tabAtiva = 'info';
    public $tipoLista = 'consultas';
    public $novaEvolucao = '';
    public $medicoId;

    public function mount($pacienteId)
    {
        $this->pacienteId = $pacienteId;
        $this->carregarPaciente();
    }

    public function carregarPaciente()
    {
        $this->paciente = Paciente::with(['prontuario.evolucoes.medico'])->findOrFail($this->pacienteId);
    }

    public function setTab($tab)
    {
        $this->tabAtiva = $tab;
        $this->resetPage();
    }

    public function setTipoLista($tipo)
    {
        $this->tipoLista = $tipo;
        $this->resetPage();
    }

    public function salvarEvolucao()
    {
        // Validação
        $this->validate([
            'novaEvolucao' => 'required|string|min:5',
            'medicoId' => 'required|exists:medicos,id',
        ], [
            'novaEvolucao.required' => 'A descrição da evolução é obrigatória',
            'novaEvolucao.min' => 'A descrição da evolução deve ter pelo menos 5 caracteres',
            'medicoId.required' => 'É necessário selecionar um médico',
            'medicoId.exists' => 'O médico selecionado não é válido'
        ]);

        // Criar nova evolução
        $evolucao = new \App\Models\Evolucao([
            'prontuario_id' => $this->paciente->prontuario->id,
            'medico_id' => $this->medicoId,
            'descricao' => $this->novaEvolucao
        ]);
        $evolucao->save();

        // Limpar campos
        $this->novaEvolucao = '';

        // Recarregar paciente para atualizar as evoluções
        $this->carregarPaciente();

        // Notificar usuário
        session()->flash('success', 'Evolução adicionada com sucesso!');
    }

    public function getConsultasProperty()
    {
        return Consulta::where('paciente_id', $this->pacienteId)
            ->with('medico')
            ->orderBy('data_hora', 'desc')
            ->paginate(5);
    }

    public function getExamesProperty()
    {
        return Exame::where('paciente_id', $this->pacienteId)
            ->with('medico')
            ->orderBy('data_solicitacao', 'desc')
            ->paginate(5);
    }

    public function render()
    {
        return view('livewire.pacientes.paciente-detalhes', [
            'consultas' => $this->tabAtiva === 'historico' && $this->tipoLista === 'consultas' ? $this->consultas : null,
            'exames' => $this->tabAtiva === 'historico' && $this->tipoLista === 'exames' ? $this->exames : null,
        ]);
    }
}
