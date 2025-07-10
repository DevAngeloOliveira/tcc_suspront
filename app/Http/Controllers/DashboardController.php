<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Consulta;
use App\Models\Medico;
use App\Models\Exame;
use App\Models\Atendente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Construtor com middleware de autenticação.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe o dashboard com estatísticas do sistema.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Total de pacientes
        $totalPacientes = Paciente::count();

        // Total de médicos
        $totalMedicos = Medico::count();

        // Total de atendentes
        $totalAtendentes = Atendente::count();

        // Consultas para hoje
        $consultasHoje = Consulta::with(['paciente', 'medico'])
            ->whereDate('data_hora', today());

        // Se for médico, mostrar apenas suas consultas
        if (Auth::user()->tipo === 'medico') {
            $consultasHoje->where('medico_id', Auth::user()->medico->id);
        }

        $consultasHoje = $consultasHoje->orderBy('data_hora')->get();

        // Total de consultas pendentes
        $consultasPendentes = Consulta::whereIn('status', ['agendada', 'confirmada']);

        // Se for médico, mostrar apenas suas consultas pendentes
        if (Auth::user()->tipo === 'medico') {
            $consultasPendentes->where('medico_id', Auth::user()->medico->id);
        }

        $consultasPendentes = $consultasPendentes->count();

        // Exames pendentes
        $examesPendentes = Exame::whereIn('status', ['solicitado', 'agendado']);

        // Se for médico, mostrar apenas os exames que ele solicitou
        if (Auth::user()->tipo === 'medico') {
            $examesPendentes->where('medico_id', Auth::user()->medico->id);
        }

        $examesPendentes = $examesPendentes->count();

        // Estatísticas de consultas por mês (últimos 6 meses)
        // Usando funções SQL compatíveis com SQLite para testes
        $sixMonthsAgo = now()->subMonths(6)->format('Y-m-d');
        $consultasPorMes = DB::table('consultas')
            ->select(DB::raw("strftime('%m', data_hora) as mes"), DB::raw('COUNT(*) as total'))
            ->where('data_hora', '>=', $sixMonthsAgo);

        // Se for médico, mostrar apenas suas estatísticas
        if (Auth::user()->tipo === 'medico') {
            $consultasPorMes->where('medico_id', Auth::user()->medico->id);
        }

        $consultasPorMes = $consultasPorMes->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Últimos pacientes cadastrados (para admin e atendente)
        $ultimosPacientes = [];
        if (Auth::user()->tipo === 'admin' || Auth::user()->tipo === 'atendente') {
            $ultimosPacientes = Paciente::latest()->take(5)->get();
        }

        // Consultas por especialidade (para admin)
        $especialidades = [];
        if (Auth::user()->tipo === 'admin') {
            $especialidades = DB::table('medicos')
                ->select('especialidade as nome', DB::raw('COUNT(*) as total'))
                ->groupBy('especialidade')
                ->get();
        }

        // Array de nomes dos meses para gráficos
        $meses = [
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

        // Dados para o gráfico de atendimentos mensais
        $atendimentosPorMes = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($consultasPorMes as $consulta) {
            if (isset($consulta->mes)) {
                $mesIndex = (int)$consulta->mes - 1; // O mês é 1-indexed, mas o array é 0-indexed
                if ($mesIndex >= 0 && $mesIndex < 12) {
                    $atendimentosPorMes[$mesIndex] = (int)$consulta->total;
                }
            }
        }

        return view('dashboard.index', compact(
            'totalPacientes',
            'totalMedicos',
            'totalAtendentes',
            'consultasHoje',
            'consultasPendentes',
            'examesPendentes',
            'consultasPorMes',
            'ultimosPacientes',
            'especialidades',
            'meses',
            'atendimentosPorMes'
        ));
    }
}
