<?php

namespace App\Livewire\Receitas;

use App\Models\Consulta;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Receita;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormReceita extends Component
{
    public $receita_id;
    public $prontuario_id;
    public $medico_id;
    public $consulta_id;
    public $paciente_id;
    public $descricao;
    public $medicamentos;
    public $posologia;
    public $observacoes;
    public $validade;

    public $consulta;
    public $paciente;
    public $medicos;

    public $isEdit = false;
    public $showSuccess = false;
    public $successMessage = '';

    protected $rules = [
        'medico_id' => 'required|exists:medicos,id',
        'medicamentos' => 'required|string',
        'posologia' => 'required|string',
        'validade' => 'required|date|after_or_equal:today',
    ];

    protected $messages = [
        'medico_id.required' => 'O médico é obrigatório',
        'medico_id.exists' => 'O médico selecionado não é válido',
        'medicamentos.required' => 'Os medicamentos são obrigatórios',
        'posologia.required' => 'A posologia é obrigatória',
        'validade.required' => 'A validade é obrigatória',
        'validade.date' => 'A validade deve ser uma data válida',
        'validade.after_or_equal' => 'A validade deve ser a partir de hoje',
    ];

    public function mount($consultaId = null, $pacienteId = null, $receitaId = null)
    {
        // Caso padrão: validade de 30 dias
        $this->validade = Carbon::now()->addDays(30)->format('Y-m-d');

        // Se o usuário é médico, já pré-seleciona
        if (Auth::check() && Auth::user()->tipo === 'medico' && Auth::user()->medico) {
            $this->medico_id = Auth::user()->medico->id;
        }

        // Carrega médicos para o select
        $this->medicos = Medico::orderBy('nome')->get();

        if ($receitaId) {
            // Modo edição
            $this->isEdit = true;
            $this->receita_id = $receitaId;
            $this->carregarReceita();
        } elseif ($consultaId) {
            // Modo criação a partir de uma consulta
            $this->consulta_id = $consultaId;
            $this->carregarDadosConsulta();
        } elseif ($pacienteId) {
            // Modo criação a partir de um paciente
            $this->paciente_id = $pacienteId;
            $this->carregarDadosPaciente();
        }
    }

    public function carregarReceita()
    {
        $receita = Receita::findOrFail($this->receita_id);
        $this->prontuario_id = $receita->prontuario_id;
        $this->medico_id = $receita->medico_id;
        $this->consulta_id = $receita->consulta_id;
        $this->descricao = $receita->descricao;
        $this->medicamentos = $receita->medicamentos;
        $this->posologia = $receita->posologia;
        $this->observacoes = $receita->observacoes;
        $this->validade = Carbon::parse($receita->validade)->format('Y-m-d');

        if ($receita->consulta) {
            $this->consulta = $receita->consulta;
            $this->paciente = $receita->consulta->paciente;
            $this->paciente_id = $this->paciente->id;
        } else {
            $this->paciente = Paciente::whereHas('prontuario', function ($query) {
                $query->where('id', $this->prontuario_id);
            })->first();

            if ($this->paciente) {
                $this->paciente_id = $this->paciente->id;
            }
        }
    }

    public function carregarDadosConsulta()
    {
        $this->consulta = Consulta::with('paciente.prontuario', 'medico')->findOrFail($this->consulta_id);
        $this->paciente = $this->consulta->paciente;
        $this->paciente_id = $this->paciente->id;
        $this->prontuario_id = $this->consulta->paciente->prontuario->id;
        $this->medico_id = $this->consulta->medico_id;

        // Se houver um diagnóstico na consulta, usa como descrição
        if ($this->consulta->diagnostico) {
            $this->descricao = $this->consulta->diagnostico;
        }

        // Se houver prescrição na consulta, usa como medicamentos
        if ($this->consulta->prescricao) {
            $this->medicamentos = $this->consulta->prescricao;
        }
    }

    public function carregarDadosPaciente()
    {
        $this->paciente = Paciente::with('prontuario')->findOrFail($this->paciente_id);
        $this->prontuario_id = $this->paciente->prontuario->id;
    }

    public function salvar()
    {
        $this->validate();

        if ($this->isEdit) {
            $receita = Receita::findOrFail($this->receita_id);
            $receita->update([
                'medico_id' => $this->medico_id,
                'descricao' => $this->descricao,
                'medicamentos' => $this->medicamentos,
                'posologia' => $this->posologia,
                'observacoes' => $this->observacoes,
                'validade' => $this->validade,
            ]);
            $this->successMessage = 'Receita atualizada com sucesso!';
        } else {
            Receita::create([
                'prontuario_id' => $this->prontuario_id,
                'medico_id' => $this->medico_id,
                'consulta_id' => $this->consulta_id,
                'descricao' => $this->descricao,
                'medicamentos' => $this->medicamentos,
                'posologia' => $this->posologia,
                'observacoes' => $this->observacoes,
                'validade' => $this->validade,
            ]);
            $this->successMessage = 'Receita criada com sucesso!';
        }

        $this->showSuccess = true;
        $this->dispatch('receitaSalva');

        if (!$this->isEdit) {
            $this->reset(['medicamentos', 'posologia', 'observacoes', 'descricao']);
        }
    }

    public function render()
    {
        return view('livewire.receitas.form-receita');
    }
}
