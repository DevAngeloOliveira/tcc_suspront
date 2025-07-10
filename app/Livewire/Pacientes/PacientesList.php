<?php

namespace App\Livewire\Pacientes;

use App\Models\Paciente;
use Livewire\Component;
use Livewire\WithPagination;

class PacientesList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filtros
    public $search = '';

    public function mount()
    {
        // No Livewire 3, podemos ouvir eventos aqui
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Paciente::query();

        // Busca por nome, CPF ou cartão SUS
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nome', 'like', '%' . $this->search . '%')
                    ->orWhere('cpf', 'like', '%' . $this->search . '%')
                    ->orWhere('cartao_sus', 'like', '%' . $this->search . '%');
            });
        }

        $pacientes = $query->orderBy('nome')->paginate(10);
        return view('livewire.pacientes.pacientes-list', compact('pacientes'));
    }

    public function excluirPaciente($pacienteId)
    {
        $paciente = Paciente::with(['consultas', 'exames'])->findOrFail($pacienteId);

        // Verificar se o paciente tem consultas ou exames
        if ($paciente->consultas->count() > 0 || $paciente->exames->count() > 0) {
            session()->flash('error', 'Não é possível excluir este paciente, pois ele possui consultas ou exames registrados.');
            return;
        }

        try {
            // Excluir prontuário associado
            if ($paciente->prontuario) {
                $paciente->prontuario->delete();
            }

            // Excluir paciente
            $paciente->delete();

            session()->flash('success', 'Paciente excluído com sucesso!');

            // No Livewire 3, podemos atualizar a lista diretamente aqui
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao excluir paciente: ' . $e->getMessage());
        }
    }
}
