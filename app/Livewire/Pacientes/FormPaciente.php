<?php

namespace App\Livewire\Pacientes;

use App\Models\Paciente;
use App\Models\Prontuario;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class FormPaciente extends Component
{
    public $pacienteId;
    public $nome;
    public $cpf;
    public $rg;
    public $cartao_sus;
    public $data_nascimento;
    public $sexo;
    public $endereco;
    public $telefone;
    public $email;
    public $alergias;
    public $condicoes_preexistentes;

    // Flag para indicar se é edição
    public $isEdit = false;

    // Regras de validação
    protected function rules()
    {
        $cpfRule = 'required|string|max:14';
        $cartaoSusRule = 'required|string|max:20';

        // Adiciona a regra de unicidade apenas se o campo tiver mudado
        if ($this->isEdit) {
            $cpfRule .= '|unique:pacientes,cpf,' . $this->pacienteId;
            $cartaoSusRule .= '|unique:pacientes,cartao_sus,' . $this->pacienteId;
        } else {
            $cpfRule .= '|unique:pacientes,cpf';
            $cartaoSusRule .= '|unique:pacientes,cartao_sus';
        }

        return [
            'nome' => 'required|string|max:255',
            'cpf' => $cpfRule,
            'rg' => 'nullable|string|max:20',
            'cartao_sus' => $cartaoSusRule,
            'data_nascimento' => 'required|date',
            'sexo' => 'required|string|max:1',
            'endereco' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alergias' => 'nullable|string',
            'condicoes_preexistentes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nome.required' => 'O nome é obrigatório.',
        'cpf.required' => 'O CPF é obrigatório.',
        'cpf.unique' => 'Este CPF já está cadastrado.',
        'cartao_sus.required' => 'O número do cartão SUS é obrigatório.',
        'cartao_sus.unique' => 'Este cartão SUS já está cadastrado.',
        'data_nascimento.required' => 'A data de nascimento é obrigatória.',
        'sexo.required' => 'O sexo é obrigatório.',
    ];

    public function mount($pacienteId = null)
    {
        if ($pacienteId) {
            $this->pacienteId = $pacienteId;
            $this->isEdit = true;
            $paciente = Paciente::findOrFail($pacienteId);
            $this->fill($paciente->toArray());
        }
    }

    public function salvar()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            if ($this->isEdit) {
                $paciente = Paciente::findOrFail($this->pacienteId);
                $paciente->update([
                    'nome' => $this->nome,
                    'cpf' => $this->cpf,
                    'rg' => $this->rg,
                    'cartao_sus' => $this->cartao_sus,
                    'data_nascimento' => $this->data_nascimento,
                    'sexo' => $this->sexo,
                    'endereco' => $this->endereco,
                    'telefone' => $this->telefone,
                    'email' => $this->email,
                    'alergias' => $this->alergias,
                    'condicoes_preexistentes' => $this->condicoes_preexistentes,
                ]);

                $mensagem = 'Paciente atualizado com sucesso!';
            } else {
                $paciente = Paciente::create([
                    'nome' => $this->nome,
                    'cpf' => $this->cpf,
                    'rg' => $this->rg,
                    'cartao_sus' => $this->cartao_sus,
                    'data_nascimento' => $this->data_nascimento,
                    'sexo' => $this->sexo,
                    'endereco' => $this->endereco,
                    'telefone' => $this->telefone,
                    'email' => $this->email,
                    'alergias' => $this->alergias,
                    'condicoes_preexistentes' => $this->condicoes_preexistentes,
                ]);

                // Cria automaticamente um prontuário vazio para o paciente
                Prontuario::create([
                    'paciente_id' => $paciente->id,
                ]);

                $mensagem = 'Paciente cadastrado com sucesso!';
            }

            DB::commit();

            session()->flash('success', $mensagem);
            return redirect()->route('pacientes.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao salvar paciente: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pacientes.form-paciente');
    }
}
