<?php

namespace App\Livewire\Dashboard;

use App\Models\Paciente;
use App\Models\Consulta;
use App\Models\Medico;
use App\Models\Exame;
use App\Models\Atendente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StatisticsCards extends Component
{
    public $period = 'today';
    public $totalPacientes;
    public $totalConsultas;
    public $totalMedicos;
    public $consultasPendentes;
    public $refreshInterval = 30000; // 30 segundos

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        // Total de pacientes
        $this->totalPacientes = Paciente::count();

        // Total de médicos
        $this->totalMedicos = Medico::count();

        // Consultas baseadas no período selecionado
        $consultasQuery = Consulta::query();

        switch ($this->period) {
            case 'today':
                $consultasQuery->whereDate('data_hora', Carbon::today());
                break;
            case 'week':
                $consultasQuery->whereBetween('data_hora', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $consultasQuery->whereMonth('data_hora', Carbon::now()->month)
                    ->whereYear('data_hora', Carbon::now()->year);
                break;
        }

        // Se for médico, mostrar apenas suas consultas
        if (Auth::user()->tipo === 'medico') {
            $consultasQuery->where('medico_id', Auth::user()->medico->id);
        }

        $this->totalConsultas = $consultasQuery->count();

        // Consultas pendentes
        $consultasPendentesQuery = Consulta::whereIn('status', ['agendada', 'confirmada']);

        // Se for médico, mostrar apenas suas consultas pendentes
        if (Auth::user()->tipo === 'medico') {
            $consultasPendentesQuery->where('medico_id', Auth::user()->medico->id);
        }

        $this->consultasPendentes = $consultasPendentesQuery->count();
    }

    public function setPeriod($period)
    {
        $this->period = $period;
        $this->loadStats();
    }

    public function render()
    {
        return view('livewire.dashboard.statistics-cards');
    }
}
