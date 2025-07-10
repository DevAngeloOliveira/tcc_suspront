<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\MedicoPlantao;
use App\Services\NotificacaoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConsultaRemarcacaoController extends Controller
{
    /**
     * Mostrar formulário para remarcação de consulta
     */
    public function edit($id)
    {
        $consulta = Consulta::findOrFail($id);

        // Verificar se a consulta já foi cancelada ou concluída
        if (in_array($consulta->status, ['cancelada', 'concluida'])) {
            return back()->with('error', 'Esta consulta não pode ser remarcada.');
        }

        // Verificar permissões para remarcar
        if (Auth::user()->tipo === 'medico' && Auth::user()->medico->id != $consulta->medico_id) {
            return back()->with('error', 'Você não tem permissão para remarcar esta consulta.');
        }

        // Para a view, precisamos das datas disponíveis do médico
        $plantoes = MedicoPlantao::where('medico_id', $consulta->medico_id)
            ->where('status', 'ativo')
            ->where(function ($query) {
                $query->where(function ($q) {
                    // Plantões não recorrentes a partir de hoje
                    $q->whereDate('data_inicio', '>=', Carbon::today())
                        ->where('recorrente', false);
                })->orWhere(function ($q) {
                    // Plantões recorrentes
                    $q->where('recorrente', true)
                        ->where(function ($subq) {
                            $subq->whereNull('data_fim')
                                ->orWhereDate('data_fim', '>=', Carbon::today());
                        });
                });
            })
            ->orderBy('data_inicio')
            ->get();

        return view('consultas.remarcacao', compact('consulta', 'plantoes'));
    }

    /**
     * Processar a remarcação de consulta
     */
    public function update(Request $request, $id)
    {
        $consulta = Consulta::findOrFail($id);

        // Verificar se a consulta já foi cancelada ou concluída
        if (in_array($consulta->status, ['cancelada', 'concluida'])) {
            return back()->with('error', 'Esta consulta não pode ser remarcada.');
        }

        // Verificar permissões para remarcar
        if (Auth::user()->tipo === 'medico' && Auth::user()->medico->id != $consulta->medico_id) {
            return back()->with('error', 'Você não tem permissão para remarcar esta consulta.');
        }

        // Validar dados
        $request->validate([
            'data' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'plantao_id' => 'required|exists:medico_plantoes,id',
            'observacoes' => 'nullable|string',
        ]);

        // Registrar motivo da remarcação
        if ($request->filled('observacoes')) {
            $observacoes = $consulta->observacoes ?? '';
            $dataNova = Carbon::parse($request->data . ' ' . $request->hora)->format('d/m/Y H:i');
            $observacoes .= "\n\n[REMARCAÇÃO EM " . now()->format('d/m/Y H:i') . "]: ";
            $observacoes .= "Consulta remarcada para $dataNova. ";
            $observacoes .= $request->observacoes;

            $consulta->observacoes = $observacoes;
        }

        try {
            DB::beginTransaction();

            // Atualizar consulta
            $dataHora = Carbon::parse($request->data . ' ' . $request->hora);
            $consulta->data_hora = $dataHora;
            $consulta->plantao_id = $request->plantao_id;
            $consulta->status = 'agendada'; // Volta para o status agendada
            $consulta->save();

            // Enviar notificação para o médico
            if ($consulta->medico && $consulta->medico->user) {
                $notificacaoService = app(NotificacaoService::class);
                $notificacaoService->notificarAlteracaoConsulta($consulta);
            }

            DB::commit();

            return redirect()->route('consultas.show', $consulta->id)
                ->with('success', 'Consulta remarcada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao remarcar consulta: ' . $e->getMessage());
        }
    }

    /**
     * API para verificar disponibilidade de horários
     */
    public function verificarDisponibilidade(Request $request)
    {
        $request->validate([
            'medico_id' => 'required|exists:medicos,id',
            'data' => 'required|date',
            'consulta_id' => 'nullable|exists:consultas,id',
        ]);

        $medicoId = $request->medico_id;
        $data = $request->data;
        $consultaId = $request->consulta_id;

        // Horários base
        $horaInicio = 8;
        $horaFim = 19;
        $intervalo = 30; // minutos

        // Buscar plantões do médico para esta data
        $dataConsulta = Carbon::parse($data);
        $diaSemana = $dataConsulta->dayOfWeek;

        $plantoes = MedicoPlantao::where('medico_id', $medicoId)
            ->where('status', 'ativo')
            ->where(function ($query) use ($dataConsulta, $diaSemana) {
                $query->where(function ($q) use ($dataConsulta) {
                    // Plantões específicos para a data
                    $q->whereDate('data_inicio', '<=', $dataConsulta)
                        ->whereDate('data_fim', '>=', $dataConsulta)
                        ->where('recorrente', false);
                })->orWhere(function ($q) use ($dataConsulta, $diaSemana) {
                    // Plantões recorrentes para o dia da semana
                    $q->whereDate('data_inicio', '<=', $dataConsulta)
                        ->where(function ($subq) use ($dataConsulta) {
                            $subq->whereNull('data_fim')
                                ->orWhereDate('data_fim', '>=', $dataConsulta);
                        })
                        ->where('recorrente', true)
                        ->where('dia_semana', $diaSemana);
                });
            })
            ->get();

        // Se não houver plantões, retornar lista vazia
        if ($plantoes->isEmpty()) {
            return response()->json([
                'message' => 'O médico não tem plantões disponíveis nesta data',
                'horarios' => [],
                'plantoes' => []
            ]);
        }

        // Verificar horários ocupados
        $consultas = Consulta::where('medico_id', $medicoId)
            ->whereDate('data_hora', $data)
            ->whereNotIn('status', ['cancelada']);

        // Se estiver editando uma consulta, ignorar a própria consulta
        if ($consultaId) {
            $consultas->where('id', '!=', $consultaId);
        }

        $consultasExistentes = $consultas->get();

        // Mapear horários ocupados
        $horariosOcupados = [];
        foreach ($consultasExistentes as $consulta) {
            $hora = Carbon::parse($consulta->data_hora)->format('H:i');
            $horariosOcupados[] = $hora;
        }

        // Para cada plantão, mapear horários disponíveis
        $resultado = [];

        foreach ($plantoes as $plantao) {
            $horariosDisponiveis = [];
            $horaInicioPlantao = Carbon::parse($plantao->hora_inicio)->hour;
            $horaFimPlantao = Carbon::parse($plantao->hora_fim)->hour;
            $minutoInicioPlantao = Carbon::parse($plantao->hora_inicio)->minute;
            $minutoFimPlantao = Carbon::parse($plantao->hora_fim)->minute;

            // Verificar capacidade do plantão
            $consultasNestePlantao = $consultasExistentes->filter(function ($consulta) use ($plantao) {
                $consultaHora = Carbon::parse($consulta->data_hora);
                return $plantao->contemDataHora($consultaHora);
            })->count();

            // Se o plantão já estiver lotado, pular
            if ($consultasNestePlantao >= $plantao->capacidade_consultas) {
                continue;
            }

            // Gerar horários no intervalo do plantão
            for ($hora = $horaInicioPlantao; $hora <= $horaFimPlantao; $hora++) {
                $minutoInicial = ($hora == $horaInicioPlantao) ? $minutoInicioPlantao : 0;
                $minutoFinal = ($hora == $horaFimPlantao) ? $minutoFimPlantao : 59;

                for ($minuto = $minutoInicial; $minuto <= $minutoFinal; $minuto += $intervalo) {
                    if ($minuto >= 60) continue;

                    $horario = sprintf('%02d:%02d', $hora, $minuto);

                    // Verificar se este horário já está ocupado
                    if (!in_array($horario, $horariosOcupados)) {
                        $horariosDisponiveis[] = [
                            'hora' => $horario,
                            'plantao_id' => $plantao->id
                        ];
                    }
                }
            }

            // Adicionar ao resultado final
            $resultado[] = [
                'plantao' => $plantao,
                'horarios_disponiveis' => $horariosDisponiveis,
                'vagas_restantes' => $plantao->capacidade_consultas - $consultasNestePlantao
            ];
        }

        return response()->json([
            'message' => count($resultado) > 0 ? 'Horários disponíveis encontrados' : 'Não há horários disponíveis',
            'plantoes' => $resultado
        ]);
    }
}
