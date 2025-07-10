<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Prontuario;
use App\Models\Exame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConsultaController extends Controller
{
    /**
     * Construtor com middleware de autenticação.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Consulta::with(['paciente', 'medico']);

        // Filtro por status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filtro por data
        if ($request->has('data') && !empty($request->data)) {
            $query->whereDate('data_hora', $request->data);
        }

        // Filtro por médico
        if ($request->has('medico_id') && !empty($request->medico_id)) {
            $query->where('medico_id', $request->medico_id);
        } elseif (Auth::user()->tipo == 'medico') {
            // Se for médico, mostrar apenas suas consultas
            $query->where('medico_id', Auth::user()->medico->id);
        }

        // Busca por termo (nome do paciente ou médico)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('paciente', function ($q2) use ($search) {
                    $q2->where('nome', 'like', '%' . $search . '%');
                })->orWhereHas('medico', function ($q2) use ($search) {
                    $q2->where('nome', 'like', '%' . $search . '%');
                });
            });
        }

        $consultas = $query->orderBy('data_hora', 'desc')->paginate(10);

        // Lista de médicos para o filtro (apenas para admin e atendentes)
        $medicos = [];
        if (Auth::user()->tipo != 'medico') {
            $medicos = Medico::orderBy('nome')->get();
        }

        return view('consultas.index', compact('consultas', 'medicos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Se o paciente for especificado pela URL
        $paciente = null;
        if ($request->has('paciente_id')) {
            $paciente = Paciente::findOrFail($request->paciente_id);
        }

        // Listar todos os pacientes caso não tenha sido especificado
        $pacientes = Paciente::orderBy('nome')->get();

        // Listar médicos para seleção
        $medicos = Medico::orderBy('nome')->get();

        return view('consultas.create', compact('paciente', 'pacientes', 'medicos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'data' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'tipo_consulta' => 'required|string|max:255',
            'queixa_principal' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Preparar data e hora
            $dataHora = $validated['data'] . ' ' . $validated['hora'] . ':00';

            // Buscar o prontuário do paciente
            $paciente = Paciente::with('prontuario')->lockForUpdate()->findOrFail($validated['paciente_id']);

            $prontuario = $paciente->prontuario;
            if (!$prontuario) {
                // Criar prontuário automaticamente se não existir
                $prontuario = Prontuario::create([
                    'paciente_id' => $paciente->id,
                ]);
                if (!$prontuario) {
                    throw new \Exception('Erro ao criar prontuário do paciente');
                }
            }

            // Criar a consulta
            $consulta = Consulta::create([
                'paciente_id' => $validated['paciente_id'],
                'medico_id' => $validated['medico_id'],
                'prontuario_id' => $prontuario->id,
                'data_hora' => $dataHora,
                'tipo_consulta' => $validated['tipo_consulta'],
                'queixa_principal' => $validated['queixa_principal'],
                'status' => 'agendada',
            ]);

            if (!$consulta) {
                throw new \Exception('Erro ao criar consulta');
            }

            DB::commit();
            return redirect()->route('consultas.show', $consulta->id)
                ->with('success', 'Consulta agendada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao agendar consulta: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $consulta = Consulta::with(['paciente', 'medico', 'prontuario'])->findOrFail($id);
        // Buscar exames relacionados a esta consulta
        $exames = Exame::where('consulta_id', $id)->orderBy('created_at', 'desc')->get();
        // Buscar histórico de consultas do paciente
        $historicoConsultas = Consulta::where('paciente_id', $consulta->paciente_id)
            ->where('id', '!=', $id)
            ->with('medico')
            ->orderBy('data_hora', 'desc')
            ->get();
        return view('consultas.show', compact('consulta', 'exames', 'historicoConsultas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $consulta = Consulta::with(['paciente', 'medico'])->findOrFail($id);

        // Não permitir edição de consultas concluídas ou canceladas
        if ($consulta->status == 'concluida' || $consulta->status == 'cancelada') {
            return redirect()->route('consultas.show', $consulta->id)
                ->with('error', 'Não é possível editar consultas concluídas ou canceladas.');
        }

        // Listar médicos disponíveis
        $medicos = Medico::orderBy('nome')->get();

        return view('consultas.edit', compact('consulta', 'medicos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $consulta = Consulta::findOrFail($id);

        // Não permitir edição de consultas concluídas ou canceladas
        if ($consulta->status == 'concluida' || $consulta->status == 'cancelada') {
            return redirect()->route('consultas.show', $consulta->id)
                ->with('error', 'Não é possível editar consultas concluídas ou canceladas.');
        }

        // Validação depende do tipo de atualização
        if ($consulta->status == 'em_andamento' && Auth::user()->tipo == 'medico') {
            // Médico atualizando dados clínicos da consulta
            $validated = $request->validate([
                'diagnostico' => 'required|string',
                'prescricao' => 'nullable|string',
                'observacoes' => 'nullable|string',
            ]);

            try {
                $consulta->update($validated);
                return redirect()->route('consultas.show', $consulta->id)
                    ->with('success', 'Dados da consulta atualizados com sucesso!');
            } catch (\Exception $e) {
                return back()->withInput()
                    ->with('error', 'Erro ao atualizar consulta: ' . $e->getMessage());
            }
        } else {
            // Atualização normal dos dados da consulta
            $validated = $request->validate([
                'medico_id' => 'required|exists:medicos,id',
                'data' => 'required|date',
                'hora' => 'required|date_format:H:i',
                'tipo_consulta' => 'required|string|max:255',
                'queixa_principal' => 'required|string',
                'status' => 'required|in:agendada,confirmada,em_andamento,concluida,cancelada',
            ]);

            try {
                // Preparar data e hora
                $dataHora = $validated['data'] . ' ' . $validated['hora'] . ':00';

                // Atualizar dados
                $consulta->update([
                    'medico_id' => $validated['medico_id'],
                    'data_hora' => $dataHora,
                    'tipo_consulta' => $validated['tipo_consulta'],
                    'queixa_principal' => $validated['queixa_principal'],
                    'status' => $validated['status'],
                ]);

                return redirect()->route('consultas.show', $consulta->id)
                    ->with('success', 'Consulta atualizada com sucesso!');
            } catch (\Exception $e) {
                return back()->withInput()
                    ->with('error', 'Erro ao atualizar consulta: ' . $e->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $consulta = Consulta::findOrFail($id);

            // Apenas administradores ou o médico responsável podem cancelar consultas
            if (
                Auth::user()->tipo != 'admin' &&
                (Auth::user()->tipo != 'medico' || Auth::user()->medico->id != $consulta->medico_id)
            ) {
                return back()->with('error', 'Você não tem permissão para cancelar esta consulta.');
            }

            // Verificar se a consulta pode ser cancelada
            if ($consulta->status == 'concluida') {
                return back()->with('error', 'Não é possível cancelar consultas já concluídas.');
            }

            // Cancelar a consulta em vez de excluí-la efetivamente
            $consulta->update(['status' => 'cancelada']);

            return redirect()->route('consultas.index')
                ->with('success', 'Consulta cancelada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao cancelar consulta: ' . $e->getMessage());
        }
    }

    /**
     * Concluir uma consulta.
     */
    public function concluir(string $id)
    {
        try {
            $consulta = Consulta::findOrFail($id);

            // Verificar se o usuário logado é o médico desta consulta
            if (Auth::user()->tipo == 'medico' && Auth::user()->medico->id != $consulta->medico_id) {
                return back()->with('error', 'Você não tem permissão para concluir esta consulta.');
            }

            // Verificar se a consulta está em andamento
            if ($consulta->status != 'em_andamento') {
                return back()->with('error', 'Apenas consultas em andamento podem ser concluídas.');
            }

            // Atualizar status
            $consulta->update([
                'status' => 'concluida'
            ]);

            return redirect()->route('consultas.show', $consulta->id)
                ->with('success', 'Consulta concluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao concluir consulta: ' . $e->getMessage());
        }
    }

    /**
     * Registrar atendimento médico durante uma consulta
     */
    public function registrarAtendimento(Request $request, string $id)
    {
        try {
            $consulta = Consulta::findOrFail($id);

            // Verificar se o usuário logado é o médico desta consulta
            if (Auth::user()->tipo == 'medico' && Auth::user()->medico->id != $consulta->medico_id) {
                return back()->with('error', 'Você não tem permissão para registrar atendimento nesta consulta.');
            }

            // Verificar se a consulta está em andamento
            if ($consulta->status != 'em_andamento') {
                return back()->with('error', 'Apenas consultas em andamento podem ser atualizadas.');
            }

            $validated = $request->validate([
                'queixa_principal' => 'required|string',
                'historia_doenca_atual' => 'nullable|string',
                'exame_fisico' => 'nullable|string',
                'diagnostico' => 'required|string',
                'conduta' => 'required|string',
                'prescricao' => 'nullable|string',
                'observacoes' => 'nullable|string',
            ]);

            DB::beginTransaction();

            // Atualizar os dados da consulta
            $consulta->update($validated);

            DB::commit();

            return redirect()->route('consultas.show', $consulta->id)
                ->with('success', 'Atendimento registrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao registrar atendimento: ' . $e->getMessage());
        }
    }

    /**
     * Atualizar status de uma consulta.
     */
    public function updateStatus(Request $request, string $id)
    {
        try {
            $consulta = Consulta::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:agendada,confirmada,em_andamento,concluida,cancelada',
            ]);

            // Verificar permissões
            if ($validated['status'] == 'em_andamento' || $validated['status'] == 'concluida') {
                // Apenas o médico responsável pode iniciar ou concluir a consulta
                if (Auth::user()->tipo != 'medico' || Auth::user()->medico->id != $consulta->medico_id) {
                    return back()->with('error', 'Você não tem permissão para atualizar o status desta consulta.');
                }
            }

            // Atualizar o status
            $consulta->update(['status' => $validated['status']]);

            return redirect()->route('consultas.show', $consulta->id)
                ->with('success', 'Status da consulta atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar status: ' . $e->getMessage());
        }
    }

    /**
     * API: Listar todas as consultas (formato para calendário)
     */
    public function apiListar(Request $request)
    {
        // Obtém consultas com base nas permissões do usuário
        $query = Consulta::with(['paciente', 'medico']);

        // Se for médico, mostrar apenas suas consultas
        if (Auth::user()->tipo == 'medico') {
            $query->where('medico_id', Auth::user()->medico->id);
        }

        // Filtrar por intervalo de datas se fornecido
        if ($request->has('start') && $request->has('end')) {
            $query->whereBetween('data_hora', [$request->start, $request->end]);
        }

        $consultas = $query->get();

        // Formatar para o FullCalendar
        $eventos = $consultas->map(function ($consulta) {
            $cor = '';

            switch ($consulta->status) {
                case 'agendada':
                    $cor = '#ffc107'; // Amarelo
                    break;
                case 'confirmada':
                    $cor = '#0d6efd'; // Azul
                    break;
                case 'em_andamento':
                    $cor = '#0dcaf0'; // Ciano
                    break;
                case 'concluida':
                    $cor = '#198754'; // Verde
                    break;
                case 'cancelada':
                    $cor = '#dc3545'; // Vermelho
                    break;
            }

            return [
                'id' => $consulta->id,
                'title' => $consulta->paciente->nome . ' - Dr(a). ' . $consulta->medico->nome,
                'start' => $consulta->data_hora,
                'end' => Carbon::parse($consulta->data_hora)->addHours(1)->format('Y-m-d H:i:s'),
                'backgroundColor' => $cor,
                'borderColor' => $cor,
                'extendedProps' => [
                    'status' => $consulta->status,
                    'paciente' => $consulta->paciente->nome,
                    'medico' => $consulta->medico->nome,
                    'tipo' => $consulta->tipo_consulta,
                    'motivo' => $consulta->queixa_principal
                ]
            ];
        });

        return response()->json($eventos);
    }

    /**
     * API: Obter detalhes de uma consulta específica
     */
    public function apiObter(string $id)
    {
        $consulta = Consulta::with(['paciente', 'medico', 'prontuario'])->findOrFail($id);

        // Verificar permissões
        if (Auth::user()->tipo == 'medico' && Auth::user()->medico->id != $consulta->medico_id) {
            return response()->json(['error' => 'Acesso não autorizado'], 403);
        }

        return response()->json($consulta);
    }

    /**
     * API: Verificar horários disponíveis para um médico em uma data
     */
    public function apiHorariosDisponiveis(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required|date',
            'medico_id' => 'required|exists:medicos,id'
        ]);

        $data = $validated['data'];
        $medicoId = $validated['medico_id'];

        // Define horário de funcionamento padrão (7h às 19h)
        $horaInicio = 7;
        $horaFim = 19;
        $intervalo = 30; // minutos

        // Array de horários disponíveis
        $horariosDisponiveis = [];

        // Gerar todos os horários possíveis
        for ($hora = $horaInicio; $hora < $horaFim; $hora++) {
            for ($minuto = 0; $minuto < 60; $minuto += $intervalo) {
                $horario = sprintf("%02d:%02d", $hora, $minuto);
                $horariosDisponiveis[] = $horario;
            }
        }

        // Buscar consultas existentes para este médico nesta data
        $consultas = Consulta::where('medico_id', $medicoId)
            ->whereDate('data_hora', $data)
            ->whereNotIn('status', ['cancelada'])
            ->get();

        // Remover horários ocupados
        foreach ($consultas as $consulta) {
            $horaConsulta = Carbon::parse($consulta->data_hora)->format('H:i');
            $key = array_search($horaConsulta, $horariosDisponiveis);

            if ($key !== false) {
                unset($horariosDisponiveis[$key]);
            }
        }

        // Reindexar o array
        $horariosDisponiveis = array_values($horariosDisponiveis);

        return response()->json(['horarios' => $horariosDisponiveis]);
    }

    /**
     * API: Atualizar status de uma consulta
     */
    public function apiAtualizarStatus(Request $request, string $id)
    {
        try {
            $consulta = Consulta::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:agendada,confirmada,em_andamento,concluida,cancelada',
            ]);

            // Verificar permissões
            if ($validated['status'] == 'em_andamento' || $validated['status'] == 'concluida') {
                // Apenas o médico responsável pode iniciar ou concluir a consulta
                if (Auth::user()->tipo != 'medico' || Auth::user()->medico->id != $consulta->medico_id) {
                    return response()->json(['error' => 'Você não tem permissão para atualizar o status desta consulta.'], 403);
                }
            }

            // Atualizar o status
            $consulta->update(['status' => $validated['status']]);

            return response()->json(['message' => 'Status atualizado com sucesso', 'consulta' => $consulta]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar status: ' . $e->getMessage()], 500);
        }
    }
}
