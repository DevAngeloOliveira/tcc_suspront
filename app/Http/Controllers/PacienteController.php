<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Prontuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PacienteController extends Controller
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
        $query = Paciente::query();

        // Busca por nome, CPF ou cartão SUS
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', '%' . $search . '%')
                    ->orWhere('cpf', 'like', '%' . $search . '%')
                    ->orWhere('cartao_sus', 'like', '%' . $search . '%');
            });
        }

        $pacientes = $query->orderBy('nome')->paginate(10);
        return view('pacientes.index', compact('pacientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pacientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:pacientes',
            'rg' => 'nullable|string|max:20',
            'cartao_sus' => 'required|string|max:20|unique:pacientes',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|string|max:1',
            'endereco' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alergias' => 'nullable|string',
            'condicoes_preexistentes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $paciente = Paciente::create($validated);

            // Cria automaticamente um prontuário vazio para o paciente
            Prontuario::create([
                'paciente_id' => $paciente->id,
            ]);

            DB::commit();
            return redirect()->route('pacientes.index')
                ->with('success', 'Paciente cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao cadastrar paciente: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paciente = Paciente::with('prontuario', 'consultas.medico', 'exames.medico')->findOrFail($id);
        return view('pacientes.show', compact('paciente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('pacientes.edit', compact('paciente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $paciente = Paciente::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:pacientes,cpf,' . $id,
            'rg' => 'nullable|string|max:20',
            'cartao_sus' => 'required|string|max:20|unique:pacientes,cartao_sus,' . $id,
            'data_nascimento' => 'required|date',
            'sexo' => 'required|string|max:1',
            'endereco' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alergias' => 'nullable|string',
            'condicoes_preexistentes' => 'nullable|string',
        ]);

        try {
            $paciente->update($validated);
            return redirect()->route('pacientes.index')
                ->with('success', 'Paciente atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao atualizar paciente: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Apenas admin e atendentes podem excluir pacientes
        if (Auth::user()->tipo !== 'admin' && Auth::user()->tipo !== 'atendente') {
            return redirect()->route('pacientes.index')
                ->with('error', 'Você não tem permissão para excluir pacientes.');
        }

        $paciente = Paciente::with(['consultas', 'exames'])->findOrFail($id);

        // Verificar se o paciente tem consultas ou exames
        if ($paciente->consultas->count() > 0 || $paciente->exames->count() > 0) {
            return redirect()->route('pacientes.index')
                ->with('error', 'Não é possível excluir este paciente, pois ele possui consultas ou exames registrados.');
        }

        DB::beginTransaction();

        try {
            // Excluir prontuário associado
            $prontuario = Prontuario::where('paciente_id', $id)->first();
            if ($prontuario) {
                $prontuario->delete();
            }

            // Excluir paciente
            $paciente->delete();

            DB::commit();

            return redirect()->route('pacientes.index')
                ->with('success', 'Paciente excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Erro ao excluir paciente: ' . $e->getMessage());
        }
    }

    /**
     * API: Obter consultas de um paciente específico
     */
    public function apiConsultas(string $id)
    {
        $paciente = Paciente::findOrFail($id);

        // Verificar permissões
        if (Auth::user()->tipo == 'medico') {
            // Médicos só podem ver consultas de pacientes que atendem
            $medicoId = Auth::user()->medico->id;
            $temConsulta = $paciente->consultas()->where('medico_id', $medicoId)->exists();

            if (!$temConsulta) {
                return response()->json(['error' => 'Você não tem permissão para acessar as consultas deste paciente.'], 403);
            }
        }

        // Obter consultas ordenadas por data
        $consultas = $paciente->consultas()
            ->with('medico')
            ->orderBy('data_hora', 'desc')
            ->get();

        return response()->json($consultas);
    }
}
