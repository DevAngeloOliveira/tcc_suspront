<?php

namespace App\Livewire\Exames;

use App\Models\Exame;
use App\Models\Medico;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class ExamesList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $statusFiltro = '';
    public $medicoFiltro = '';
    public $dataFiltro = '';

    #[Layout('layouts.app')]
    #[Title('Exames')]
    public function render()
    {
        $query = Exame::with(['paciente', 'medico']);

        // Filtro por status
        if (!empty($this->statusFiltro)) {
            $query->where('status', $this->statusFiltro);
        }

        // Filtro por data de solicitação
        if (!empty($this->dataFiltro)) {
            $query->whereDate('data_solicitacao', $this->dataFiltro);
        }

        // Filtro por médico
        if (!empty($this->medicoFiltro)) {
            $query->where('medico_id', $this->medicoFiltro);
        } elseif (Auth::user()->tipo == 'medico') {
            // Se for médico, mostrar apenas seus exames
            $query->where('medico_id', Auth::user()->medico->id);
        }

        // Busca por termo (nome do paciente ou tipo de exame)
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('paciente', function ($pacienteQuery) {
                    $pacienteQuery->where('nome', 'like', '%' . $this->search . '%');
                })->orWhere('tipo_exame', 'like', '%' . $this->search . '%');
            });
        }

        $exames = $query->orderBy('created_at', 'desc')->paginate(10);

        $medicos = [];
        if (Auth::user()->tipo != 'medico') {
            $medicos = Medico::orderBy('nome')->get();
        }

        return view('livewire.exames.exames-list', [
            'exames' => $exames,
            'medicos' => $medicos
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFiltro()
    {
        $this->resetPage();
    }

    public function updatingMedicoFiltro()
    {
        $this->resetPage();
    }

    public function updatingDataFiltro()
    {
        $this->resetPage();
    }

    public function visualizarResultado($exameId)
    {
        return redirect()->route('exames.show', $exameId);
    }
}
