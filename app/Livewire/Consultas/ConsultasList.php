<?php

namespace App\Livewire\Consultas;

use App\Models\Consulta;
use App\Models\Medico;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ConsultasList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $statusFiltro = '';
    public $medicoFiltro = '';
    public $dataFiltro = '';

    #[Layout('layouts.app')]
    #[Title('Consultas')]
    public function render()
    {
        $query = Consulta::with(['paciente', 'medico']);

        // Filtro por status
        if (!empty($this->statusFiltro)) {
            $query->where('status', $this->statusFiltro);
        }

        // Filtro por data
        if (!empty($this->dataFiltro)) {
            $query->whereDate('data_hora', $this->dataFiltro);
        }

        // Filtro por médico
        if (!empty($this->medicoFiltro)) {
            $query->where('medico_id', $this->medicoFiltro);
        } elseif (Auth::user()->tipo == 'medico') {
            // Se for médico, mostrar apenas suas consultas
            $query->where('medico_id', Auth::user()->medico->id);
        }

        // Busca por termo (nome do paciente ou médico)
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('paciente', function ($pacienteQuery) {
                    $pacienteQuery->where('nome', 'like', '%' . $this->search . '%');
                })->orWhereHas('medico', function ($medicoQuery) {
                    $medicoQuery->where('nome', 'like', '%' . $this->search . '%');
                });
            });
        }

        $consultas = $query->orderBy('data_hora', 'desc')->paginate(10);

        $medicos = [];
        if (Auth::user()->tipo != 'medico') {
            $medicos = Medico::orderBy('nome')->get();
        }

        return view('livewire.consultas.consultas-list', [
            'consultas' => $consultas,
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

    public function updateStatus($consultaId, $newStatus)
    {
        try {
            $consulta = Consulta::findOrFail($consultaId);
            $consulta->status = $newStatus;
            $consulta->save();

            $this->dispatch(
                'alert',
                type: 'success',
                message: 'Status da consulta atualizado com sucesso!'
            );
        } catch (\Exception $e) {
            $this->dispatch(
                'alert',
                type: 'error',
                message: 'Erro ao atualizar status da consulta: ' . $e->getMessage()
            );
        }
    }
}
