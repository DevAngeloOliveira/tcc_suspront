<?php

namespace App\Http\Controllers;

use App\Models\Prontuario;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProntuarioController extends Controller
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
        // Busca avançada
        $query = Prontuario::with('paciente');

        // Filtra por termo de pesquisa (nome ou cartão SUS do paciente)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('paciente', function ($q) use ($search) {
                $q->where('nome', 'like', '%' . $search . '%')
                    ->orWhere('cartao_sus', 'like', '%' . $search . '%');
            });
        }

        // Se o usuário for médico, mostrar apenas prontuários de pacientes que ele atendeu
        if (Auth::user()->tipo == 'medico') {
            $medicoId = Auth::user()->medico->id;
            $query->whereHas('consultas', function ($q) use ($medicoId) {
                $q->where('medico_id', $medicoId);
            });
        }

        $prontuarios = $query->orderBy('updated_at', 'desc')->paginate(10);
        return view('prontuarios.index', compact('prontuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verifica permissão - apenas admin pode criar prontuários manualmente
        if (Auth::user()->tipo != 'admin') {
            return redirect()->route('prontuarios.index')
                ->with('error', 'Você não tem permissão para criar prontuários manualmente.');
        }

        // Listar pacientes sem prontuário
        $pacientes = Paciente::whereDoesntHave('prontuario')->get();

        // Se não houver pacientes sem prontuário, redireciona com mensagem
        if ($pacientes->isEmpty()) {
            return redirect()->route('prontuarios.index')
                ->with('info', 'Não há pacientes sem prontuário no sistema.');
        }

        return view('prontuarios.create', compact('pacientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verifica permissão
        if (Auth::user()->tipo != 'admin') {
            return redirect()->route('prontuarios.index')
                ->with('error', 'Você não tem permissão para criar prontuários manualmente.');
        }

        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id|unique:prontuarios,paciente_id',
            'historico_medico' => 'nullable|string',
            'medicamentos_atuais' => 'nullable|string',
            'observacoes' => 'nullable|string',
        ]);

        try {
            Prontuario::create($validated);
            return redirect()->route('prontuarios.index')
                ->with('success', 'Prontuário criado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao criar prontuário: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prontuario = Prontuario::with(['paciente', 'consultas.medico', 'paciente.exames.medico'])->findOrFail($id);

        // Verificar permissões de acesso ao prontuário
        if (Auth::user()->tipo == 'medico') {
            // Médicos só podem ver prontuários de pacientes que atenderam
            $medicoId = Auth::user()->medico->id;
            $atendeuPaciente = $prontuario->consultas()->where('medico_id', $medicoId)->exists();

            if (!$atendeuPaciente) {
                return redirect()->route('prontuarios.index')
                    ->with('error', 'Você não tem permissão para visualizar este prontuário.');
            }
        }

        return view('prontuarios.show', compact('prontuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prontuario = Prontuario::with('paciente')->findOrFail($id);

        // Verificar permissões de acesso ao prontuário
        if (Auth::user()->tipo == 'atendente') {
            return redirect()->route('prontuarios.index')
                ->with('error', 'Atendentes não podem editar prontuários.');
        }

        if (Auth::user()->tipo == 'medico') {
            // Médicos só podem editar prontuários de pacientes que atenderam
            $medicoId = Auth::user()->medico->id;
            $atendeuPaciente = $prontuario->consultas()->where('medico_id', $medicoId)->exists();

            if (!$atendeuPaciente) {
                return redirect()->route('prontuarios.index')
                    ->with('error', 'Você não tem permissão para editar este prontuário.');
            }
        }

        return view('prontuarios.edit', compact('prontuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $prontuario = Prontuario::findOrFail($id);

        // Verificar permissões
        if (Auth::user()->tipo == 'atendente') {
            return redirect()->route('prontuarios.show', $prontuario->id)
                ->with('error', 'Atendentes não podem editar prontuários.');
        }

        if (Auth::user()->tipo == 'medico') {
            // Médicos só podem editar prontuários de pacientes que atenderam
            $medicoId = Auth::user()->medico->id;
            $atendeuPaciente = $prontuario->consultas()->where('medico_id', $medicoId)->exists();

            if (!$atendeuPaciente) {
                return redirect()->route('prontuarios.show', $prontuario->id)
                    ->with('error', 'Você não tem permissão para editar este prontuário.');
            }
        }

        $validated = $request->validate([
            'historico_medico' => 'nullable|string',
            'medicamentos_atuais' => 'nullable|string',
            'observacoes' => 'nullable|string',
        ]);

        try {
            $prontuario->update($validated);
            return redirect()->route('prontuarios.show', $prontuario->id)
                ->with('success', 'Prontuário atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao atualizar prontuário: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Prontuários não devem ser excluídos no contexto médico, apenas em situações excepcionais
        // Apenas administradores podem excluir prontuários
        if (Auth::user()->tipo != 'admin') {
            return redirect()->route('prontuarios.index')
                ->with('error', 'Você não tem permissão para excluir prontuários.');
        }

        try {
            $prontuario = Prontuario::findOrFail($id);

            // Verificar se há consultas associadas
            if ($prontuario->consultas()->count() > 0) {
                return back()->with('error', 'Este prontuário possui consultas associadas e não pode ser excluído.');
            }

            // Armazenar o id do paciente para redirecionamento
            $pacienteId = $prontuario->paciente_id;

            $prontuario->delete();

            return redirect()->route('pacientes.show', $pacienteId)
                ->with('success', 'Prontuário excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir prontuário: ' . $e->getMessage());
        }
    }
}
