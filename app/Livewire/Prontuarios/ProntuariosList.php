<?php

namespace App\Livewire\Prontuarios;

use App\Models\Prontuario;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class ProntuariosList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    #[Layout('layouts.app')]
    #[Title('Prontuários')]
    public function render()
    {
        $query = Prontuario::with('paciente');

        // Filtra por termo de pesquisa (nome ou cartão SUS do paciente)
        if (!empty($this->search)) {
            $query->whereHas('paciente', function ($q) {
                $q->where('nome', 'like', '%' . $this->search . '%')
                    ->orWhere('cartao_sus', 'like', '%' . $this->search . '%');
            });
        }

        // Se o usuário for médico, mostrar apenas prontuários de pacientes que ele atendeu
        if (Auth::user()->tipo == 'medico') {
            $medicoId = Auth::user()->medico->id;
            $query->whereHas('consultas', function ($q) use ($medicoId) {
                $q->where('medico_id', $medicoId);
            });
        }

        $prontuarios = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('livewire.prontuarios.prontuarios-list', [
            'prontuarios' => $prontuarios
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
