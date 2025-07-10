<?php

namespace App\Livewire\Atendentes;

use App\Models\Atendente;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class AtendentesList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    #[Layout('layouts.app')]
    #[Title('Atendentes')]
    public function render()
    {
        $query = Atendente::query();

        // Busca por nome, CPF ou email
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nome', 'like', '%' . $this->search . '%')
                    ->orWhere('cpf', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $atendentes = $query->orderBy('nome')->paginate(10);

        return view('livewire.atendentes.atendentes-list', [
            'atendentes' => $atendentes
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmarExclusao($id)
    {
        $this->dispatch('openDeleteModal', atendenteId: $id);
    }

    public function deletarAtendente($id)
    {
        try {
            $atendente = Atendente::findOrFail($id);
            $atendente->delete();
            session()->flash('success', 'Atendente excluído com sucesso!');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao excluir atendente. Verifique se não existem registros associados.');
        }
    }
}
