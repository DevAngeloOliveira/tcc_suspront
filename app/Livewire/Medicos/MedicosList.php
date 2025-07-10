<?php

namespace App\Livewire\Medicos;

use App\Models\Medico;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class MedicosList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $especialidadeFiltro = '';

    #[Layout('layouts.app')]
    #[Title('Médicos')]
    public function render()
    {
        $query = Medico::query();

        // Busca por nome, CRM ou especialidade
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nome', 'like', '%' . $this->search . '%')
                    ->orWhere('crm', 'like', '%' . $this->search . '%')
                    ->orWhere('especialidade', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por especialidade
        if (!empty($this->especialidadeFiltro)) {
            $query->where('especialidade', $this->especialidadeFiltro);
        }

        $medicos = $query->orderBy('nome')->paginate(10);
        $especialidades = Medico::distinct('especialidade')->pluck('especialidade')->toArray();

        return view('livewire.medicos.medicos-list', [
            'medicos' => $medicos,
            'especialidades' => $especialidades
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmarExclusao($id)
    {
        $this->dispatch('openDeleteModal', medicoId: $id);
    }

    public function deletarMedico($id)
    {
        try {
            $medico = Medico::findOrFail($id);
            $medico->delete();
            session()->flash('success', 'Médico excluído com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao excluir médico. Verifique se não existem registros associados.');
        }
    }
}
