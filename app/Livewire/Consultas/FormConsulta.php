<?php

namespace App\Livewire\Consultas;

use App\Models\Consulta;
use App\Models\Medico;
use App\Models\MedicoPlantao;
use App\Models\Paciente;
use App\Models\Prontuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FormConsulta extends Component
{
    public $consultaId;
    public $pacienteId;
    public $medicoId;
    public $data;
    public $hora;
    public $tipoConsulta;
    public $queixaPrincipal;
    public $observacoes;
    public $status = 'agendada';
    public $plantaoId;

    // Variáveis para controle de UI
    public $modo = 'criar'; // criar ou editar
    public $pacienteSelecionado = null;
    public $horariosDisponiveis = [];
    public $errorMessage = '';
    public $showError = false;

    protected $rules = [
        'pacienteId' => 'required|exists:pacientes,id',
        'medicoId' => 'required|exists:medicos,id',
        'data' => 'required|date|after_or_equal:today',
        'hora' => 'required',
        'tipoConsulta' => 'required',
        'queixaPrincipal' => 'required|min:5',
        'observacoes' => 'nullable',
    ];

    protected $validationAttributes = [
        'pacienteId' => 'paciente',
        'medicoId' => 'médico',
        'tipoConsulta' => 'tipo de consulta',
        'queixaPrincipal' => 'queixa principal',
        'plantaoId' => 'plantão',
    ];

    protected $listeners = [
        'horariosAtualizados' => 'atualizarHorarios'
    ];

    public function mount($consultaId = null, $pacienteId = null)
    {
        if ($consultaId) {
            $this->modo = 'editar';
            $this->consultaId = $consultaId;
            $this->carregarConsulta();
        }

        if ($pacienteId) {
            $this->pacienteId = $pacienteId;
            $this->pacienteSelecionado = Paciente::find($pacienteId);
        }

        // Se for médico, pré-selecionar o próprio médico
        if (Auth::user()->tipo === 'medico') {
            $this->medicoId = Auth::user()->medico->id;
            $this->carregarPlantoesDisponiveis();
        }

        // Definir data padrão se estiver criando uma nova consulta
        if ($this->modo === 'criar' && !$this->data) {
            $this->data = Carbon::now()->format('Y-m-d');
        }
    }

    public function carregarConsulta()
    {
        $consulta = Consulta::findOrFail($this->consultaId);

        $this->pacienteId = $consulta->paciente_id;
        $this->medicoId = $consulta->medico_id;
        $this->plantaoId = $consulta->plantao_id;

        // Separar data e hora
        $dataHora = Carbon::parse($consulta->data_hora);
        $this->data = $dataHora->format('Y-m-d');
        $this->hora = $dataHora->format('H:i');

        $this->tipoConsulta = $consulta->tipo_consulta;
        $this->queixaPrincipal = $consulta->queixa_principal;
        $this->observacoes = $consulta->observacoes;
        $this->status = $consulta->status;

        $this->pacienteSelecionado = $consulta->paciente;

        // Carregar horários disponíveis
        $this->carregarPlantoesDisponiveis();
    }

    public function updatedPacienteId($value)
    {
        if ($value) {
            $this->pacienteSelecionado = Paciente::find($value);
        } else {
            $this->pacienteSelecionado = null;
        }
    }

    public function updatedMedicoId()
    {
        $this->carregarPlantoesDisponiveis();
    }

    public function updatedData()
    {
        $this->carregarPlantoesDisponiveis();
    }

    public function carregarPlantoesDisponiveis()
    {
        if (empty($this->medicoId) || empty($this->data)) {
            return;
        }

        $dataConsulta = Carbon::parse($this->data);
        $diaSemana = $dataConsulta->dayOfWeek;

        // Buscar plantões do médico na data selecionada ou plantões recorrentes
        $plantoes = MedicoPlantao::where('medico_id', $this->medicoId)
            ->where(function ($query) use ($dataConsulta, $diaSemana) {
                $query->where(function ($q) use ($dataConsulta) {
                    // Plantões não recorrentes para a data específica
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
            ->where('status', 'ativo')
            ->get();

        // Se não houver plantões disponíveis, mostrar mensagem
        if ($plantoes->isEmpty()) {
            $this->errorMessage = 'O médico selecionado não tem plantões disponíveis para esta data.';
            $this->showError = true;
            $this->horariosDisponiveis = [];
            return;
        }

        $this->showError = false;
        $this->dispatch('plantoesCarregados', ['plantoes' => $plantoes]);
    }

    public function atualizarHorarios($horarios)
    {
        $this->horariosDisponiveis = $horarios;
    }

    public function verificarDisponibilidade()
    {
        if (empty($this->medicoId) || empty($this->data) || empty($this->hora)) {
            $this->errorMessage = 'Por favor, selecione médico, data e hora.';
            $this->showError = true;
            return false;
        }

        $dataHoraConsulta = Carbon::parse($this->data . ' ' . $this->hora);

        // Buscar plantão que corresponde ao horário selecionado
        $plantao = MedicoPlantao::where('medico_id', $this->medicoId)
            ->where('status', 'ativo')
            ->get()
            ->first(function ($p) use ($dataHoraConsulta) {
                return $p->contemDataHora($dataHoraConsulta);
            });

        if (!$plantao) {
            $this->errorMessage = 'O médico selecionado não está de plantão no horário escolhido.';
            $this->showError = true;
            return false;
        }

        // Verificar se há vagas disponíveis no plantão
        $slotsDisponiveis = $plantao->slotsDisponiveis($this->data);
        if ($slotsDisponiveis <= 0) {
            $this->errorMessage = 'Não há vagas disponíveis para este plantão. Por favor, escolha outro horário.';
            $this->showError = true;
            return false;
        }

        // Verificar se o horário não está ocupado por outra consulta
        $consultaExistente = Consulta::where('medico_id', $this->medicoId)
            ->whereDate('data_hora', $this->data)
            ->whereTime('data_hora', $this->hora . ':00')
            ->whereNotIn('status', ['cancelada'])
            ->where(function ($query) {
                // Se estiver editando, excluir a consulta atual da verificação
                if ($this->modo === 'editar') {
                    $query->where('id', '!=', $this->consultaId);
                }
            })
            ->exists();

        if ($consultaExistente) {
            $this->errorMessage = 'Já existe uma consulta agendada para este horário. Por favor, escolha outro horário.';
            $this->showError = true;
            return false;
        }

        $this->showError = false;
        $this->plantaoId = $plantao->id;
        return true;
    }

    public function salvar()
    {
        $this->validate();

        // Verificar disponibilidade do médico
        if (!$this->verificarDisponibilidade()) {
            return;
        }

        // Combinar data e hora
        $dataHora = Carbon::parse($this->data . ' ' . $this->hora);

        DB::beginTransaction();

        try {
            if ($this->modo === 'editar') {
                // Atualizar consulta existente
                $consulta = Consulta::find($this->consultaId);
            } else {
                // Criar nova consulta
                $consulta = new Consulta();

                // Garantir que o paciente tenha prontuário
                $prontuario = Prontuario::where('paciente_id', $this->pacienteId)->first();

                if (!$prontuario) {
                    $prontuario = Prontuario::create([
                        'paciente_id' => $this->pacienteId,
                        'data_criacao' => now(),
                    ]);
                }

                $consulta->prontuario_id = $prontuario->id;
            }

            $consulta->paciente_id = $this->pacienteId;
            $consulta->medico_id = $this->medicoId;
            $consulta->plantao_id = $this->plantaoId;
            $consulta->data_hora = $dataHora;
            $consulta->tipo_consulta = $this->tipoConsulta;
            $consulta->queixa_principal = $this->queixaPrincipal;
            $consulta->observacoes = $this->observacoes;
            $consulta->status = $this->status;

            $consulta->save();

            // Enviar notificação para o médico
            $this->enviarNotificacaoMedico($consulta);

            DB::commit();

            if ($this->modo === 'editar') {
                session()->flash('success', 'Consulta atualizada com sucesso!');
                return redirect()->route('consultas.show', $consulta->id);
            } else {
                session()->flash('success', 'Consulta agendada com sucesso!');
                return redirect()->route('consultas.index');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = 'Erro ao salvar a consulta: ' . $e->getMessage();
            $this->showError = true;
        }
    }

    protected function enviarNotificacaoMedico($consulta)
    {
        $medico = $consulta->medico;
        $paciente = $consulta->paciente;

        if (!$medico || !$medico->user_id) {
            return;
        }

        // Utiliza o serviço de notificações
        $notificacaoService = app(App\Services\NotificacaoService::class);

        // Caso de edição ou criação
        if ($this->modo === 'editar') {
            $notificacaoService->notificarAlteracaoConsulta($consulta);
        } else {
            $notificacaoService->notificarNovaConsulta($consulta);
        }

        // Atualizar campo na consulta para registrar notificação
        $consulta->notificado_em = now();
        $consulta->save();
    }

    public function render()
    {
        return view('livewire.consultas.form-consulta', [
            'pacientes' => Paciente::orderBy('nome')->get(),
            'medicos' => Medico::orderBy('nome')->get(),
        ]);
    }
}
