<?php

namespace App\Livewire\Receitas;

use App\Models\Paciente;
use App\Models\Receita;
use Livewire\Component;
use Livewire\WithPagination;

class ReceitasList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $paciente_id;
    public $prontuario_id;

    public $modalReceita = null;
    public $showDeleteModal = false;
    public $receitaIdToDelete = null;

    protected $listeners = ['receitaSalva' => '$refresh'];

    public function mount($pacienteId = null, $prontuarioId = null)
    {
        if ($pacienteId) {
            $this->paciente_id = $pacienteId;
            $paciente = Paciente::with('prontuario')->findOrFail($pacienteId);
            $this->prontuario_id = $paciente->prontuario->id;
        } elseif ($prontuarioId) {
            $this->prontuario_id = $prontuarioId;
        }
    }

    public function getReceitasProperty()
    {
        return Receita::where('prontuario_id', $this->prontuario_id)
            ->with(['medico', 'consulta'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);
    }

    public function visualizarReceita($id)
    {
        $this->modalReceita = Receita::with(['medico', 'consulta.paciente', 'prontuario.paciente'])
            ->findOrFail($id);
    }

    public function fecharModalReceita()
    {
        $this->modalReceita = null;
    }

    public function confirmarExclusao($id)
    {
        $this->receitaIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function cancelarExclusao()
    {
        $this->receitaIdToDelete = null;
        $this->showDeleteModal = false;
    }

    public function excluirReceita()
    {
        if ($this->receitaIdToDelete) {
            $receita = Receita::findOrFail($this->receitaIdToDelete);
            $receita->delete();

            $this->showDeleteModal = false;
            $this->receitaIdToDelete = null;
            session()->flash('success', 'Receita excluída com sucesso!');
        }
    }

    public function render()
    {
        return view('livewire.receitas.receitas-list', [
            'receitas' => $this->receitas
        ]);
    }
}
