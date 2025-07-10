<?php

namespace App\Livewire\Dashboard;

use App\Models\Consulta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ConsultasChart extends Component
{
    public $chartData = [];
    public $meses = [];
    public $consultasPorMes = [];
    public $chartType = 'bar'; // bar, line, pie
    public $timeRange = 6; // meses
    public $autoRefresh = false;
    public $refreshInterval = 60000; // 60 segundos

    // Para gráfico de especialidades
    public $especialidades = [];
    public $showEspecialidades = false;

    protected $listeners = ['refreshChart' => 'loadChartData'];

    public function mount()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $this->meses = [
            'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        ];

        try {
            // Dependendo do tipo de gráfico, carregamos dados diferentes
            if ($this->showEspecialidades) {
                $this->loadEspecialidadesData();
            } else {
                $this->loadConsultasPorMesData();
            }
        } catch (\Exception $e) {
            // Log do erro
            \Log::error('Erro ao carregar dados do gráfico: ' . $e->getMessage());

            // Fornecer dados padrão para evitar quebrar o gráfico
            $this->chartData = [
                'labels' => ['Sem dados disponíveis'],
                'datasets' => [
                    [
                        'label' => 'Dados não disponíveis',
                        'data' => [0],
                        'backgroundColor' => '#858796',
                    ]
                ]
            ];

            // Notificar o frontend
            $this->dispatch('chartError', 'Erro ao carregar dados do gráfico');
        }
    }

    public function loadConsultasPorMesData()
    {
        // Consultas por mês
        $periodStart = Carbon::now()->subMonths($this->timeRange);

        // Detectar o tipo de banco de dados
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        // Consultas por mês - SQLite usa strftime, MySQL usa MONTH()
        if ($driver === 'sqlite') {
            $consultasPorMes = DB::table('consultas')
                ->select(DB::raw("strftime('%m', data_hora) as mes"), DB::raw('COUNT(*) as total'))
                ->where('data_hora', '>=', $periodStart->format('Y-m-d H:i:s'));
        } else {
            // Assumindo MySQL ou outro banco que tenha a função MONTH()
            $consultasPorMes = DB::table('consultas')
                ->select(DB::raw("MONTH(data_hora) as mes"), DB::raw('COUNT(*) as total'))
                ->where('data_hora', '>=', $periodStart->format('Y-m-d H:i:s'));
        }

        // Se for médico, filtrar apenas suas consultas
        if (Auth::user()->tipo === 'medico') {
            $consultasPorMes->where('medico_id', Auth::user()->medico->id);
        }

        $consultasPorMes = $consultasPorMes->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Converter para array para o chart.js
        $data = array_fill(0, 12, 0); // Inicializa com zeros

        foreach ($consultasPorMes as $consulta) {
            if (isset($consulta->mes)) {
                $mesIndex = (int)$consulta->mes - 1; // O mês é 1-indexed, mas o array é 0-indexed
                if ($mesIndex >= 0 && $mesIndex < 12) {
                    $data[$mesIndex] = (int)$consulta->total;
                }
            }
        }

        // Manter apenas os últimos N meses
        $today = Carbon::today();
        $currentMonth = $today->month;

        $filteredData = [];
        $filteredLabels = [];

        for ($i = $this->timeRange - 1; $i >= 0; $i--) {
            $monthIndex = ($currentMonth - $i - 1 + 12) % 12;
            $filteredLabels[] = $this->meses[$monthIndex];
            $filteredData[] = $data[$monthIndex];
        }

        // Cores para o gráfico
        $primaryColor = '#4e73df';
        $primaryColorTransparent = 'rgba(78, 115, 223, 0.2)';

        $this->chartData = [
            'labels' => $filteredLabels,
            'datasets' => [
                [
                    'label' => 'Consultas',
                    'data' => $filteredData,
                    'backgroundColor' => $this->chartType === 'line' ? $primaryColorTransparent : $primaryColor,
                    'borderColor' => $primaryColor,
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => $this->chartType === 'line',
                    'pointBackgroundColor' => $primaryColor,
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => $primaryColor
                ]
            ]
        ];

        // Emitir evento para atualizar o gráfico
        $this->dispatch('chartDataUpdated');
    }

    public function loadEspecialidadesData()
    {
        // Consultas por especialidade
        $especialidades = DB::table('medicos')
            ->select('especialidade as nome', DB::raw('COUNT(*) as total'))
            ->join('consultas', 'medicos.id', '=', 'consultas.medico_id')
            ->groupBy('especialidade')
            ->orderBy('total', 'desc')
            ->get();

        // Paleta de cores para os gráficos
        $backgroundColors = [
            '#4e73df',
            '#1cc88a',
            '#36b9cc',
            '#f6c23e',
            '#e74a3b',
            '#858796',
            '#5a5c69',
            '#2e59d9',
            '#17a673',
            '#2c9faf'
        ];

        $hoverBackgroundColors = [
            '#2e59d9',
            '#17a673',
            '#2c9faf',
            '#dda20a',
            '#c81e1e',
            '#6b6d7d',
            '#3c3c43',
            '#1c3ebb',
            '#128b5e',
            '#1e7d8a'
        ];

        // Assegura que temos cores suficientes
        while (count($backgroundColors) < count($especialidades)) {
            $backgroundColors = array_merge($backgroundColors, $backgroundColors);
            $hoverBackgroundColors = array_merge($hoverBackgroundColors, $hoverBackgroundColors);
        }

        $this->chartData = [
            'labels' => $especialidades->pluck('nome')->toArray(),
            'datasets' => [
                [
                    'label' => 'Consultas por Especialidade',
                    'data' => $especialidades->pluck('total')->toArray(),
                    'backgroundColor' => array_slice($backgroundColors, 0, count($especialidades)),
                    'hoverBackgroundColor' => array_slice($hoverBackgroundColors, 0, count($especialidades)),
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                    'hoverOffset' => 10
                ]
            ]
        ];

        $this->especialidades = $especialidades;

        // Emitir evento para atualizar o gráfico
        $this->dispatch('chartDataUpdated');
    }

    public function toggleChartType($type)
    {
        $this->chartType = $type;
        $this->dispatch('chartTypeChanged', $type);
    }

    public function toggleDataType($showEspecialidades)
    {
        $this->showEspecialidades = $showEspecialidades;
        $this->loadChartData();
        $this->dispatch('dataTypeChanged', $showEspecialidades);
    }

    public function setTimeRange($months)
    {
        $this->timeRange = $months;
        $this->loadChartData();
    }

    public function toggleAutoRefresh()
    {
        $this->autoRefresh = !$this->autoRefresh;
    }

    public function render()
    {
        return view('livewire.dashboard.consultas-chart');
    }
}
