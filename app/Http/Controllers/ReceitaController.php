<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\Paciente;
use App\Models\Consulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceitaController extends Controller
{
    /**
     * Construtor com middleware de autenticação
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe a lista de receitas
     */
    public function index(Request $request)
    {
        // Se for médico, mostrar apenas suas receitas
        if (Auth::user()->tipo === 'medico') {
            $medicoId = Auth::user()->medico->id;
            $receitas = Receita::where('medico_id', $medicoId)
                ->with(['prontuario.paciente', 'medico'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Para admin/atendente, mostrar todas as receitas
            $receitas = Receita::with(['prontuario.paciente', 'medico'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('receitas.index', compact('receitas'));
    }

    /**
     * Mostra o formulário para criar nova receita
     */
    public function create(Request $request)
    {
        $pacienteId = $request->query('paciente_id');
        $consultaId = $request->query('consulta_id');

        return view('receitas.create', compact('pacienteId', 'consultaId'));
    }

    /**
     * Armazena uma nova receita
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'prontuario_id' => 'required|exists:prontuarios,id',
            'medico_id' => 'required|exists:medicos,id',
            'consulta_id' => 'nullable|exists:consultas,id',
            'descricao' => 'nullable|string',
            'medicamentos' => 'required|string',
            'posologia' => 'required|string',
            'observacoes' => 'nullable|string',
            'validade' => 'required|date',
        ]);

        $receita = Receita::create($data);

        return redirect()->route('receitas.index')->with('success', 'Receita criada com sucesso!');
    }

    /**
     * Exibe uma receita específica
     */
    public function show($id)
    {
        $receita = Receita::with(['prontuario.paciente', 'medico', 'consulta'])
            ->findOrFail($id);

        // Verificar permissões
        if (Auth::user()->tipo === 'medico') {
            $medicoId = Auth::user()->medico->id;

            // Médico pode ver apenas receitas que ele emitiu
            if ($receita->medico_id !== $medicoId) {
                return redirect()->route('receitas.index')
                    ->with('error', 'Você não tem permissão para visualizar esta receita.');
            }
        }

        return view('receitas.show', compact('receita'));
    }

    /**
     * Mostra o formulário para editar uma receita
     */
    public function edit($id)
    {
        $receitaId = $id;
        $receita = Receita::findOrFail($id);

        // Verificar permissões
        if (Auth::user()->tipo === 'medico') {
            $medicoId = Auth::user()->medico->id;

            // Médico pode editar apenas receitas que ele emitiu
            if ($receita->medico_id !== $medicoId) {
                return redirect()->route('receitas.index')
                    ->with('error', 'Você não tem permissão para editar esta receita.');
            }
        } elseif (Auth::user()->tipo === 'atendente') {
            // Atendentes não podem editar receitas
            return redirect()->route('receitas.show', $receita->id)
                ->with('error', 'Atendentes não podem editar receitas.');
        }

        return view('receitas.edit', compact('receitaId'));
    }

    /**
     * Atualiza uma receita específica
     */
    public function update(Request $request, $id)
    {
        // Validações são feitas no componente Livewire
    }

    /**
     * Remove uma receita
     */
    public function destroy($id)
    {
        $receita = Receita::findOrFail($id);

        // Verificar permissões
        if (Auth::user()->tipo === 'medico') {
            $medicoId = Auth::user()->medico->id;

            // Médico pode excluir apenas receitas que ele emitiu
            if ($receita->medico_id !== $medicoId) {
                return redirect()->route('receitas.index')
                    ->with('error', 'Você não tem permissão para excluir esta receita.');
            }
        } elseif (Auth::user()->tipo === 'atendente') {
            // Atendentes não podem excluir receitas
            return redirect()->route('receitas.show', $receita->id)
                ->with('error', 'Atendentes não podem excluir receitas.');
        }

        $prontuarioId = $receita->prontuario_id;
        $pacienteId = $receita->prontuario->paciente_id;

        try {
            $receita->delete();
            return redirect()->route('pacientes.show', $pacienteId)
                ->with('success', 'Receita excluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir receita: ' . $e->getMessage());
        }
    }

    /**
     * Gera um PDF da receita para impressão
     */
    public function imprimir($id)
    {
        $receita = Receita::with(['prontuario.paciente', 'medico'])
            ->findOrFail($id);

        // Verificar permissões
        if (Auth::user()->tipo === 'medico') {
            $medicoId = Auth::user()->medico->id;

            // Médico pode imprimir apenas receitas que ele emitiu
            if ($receita->medico_id !== $medicoId) {
                return redirect()->route('receitas.index')
                    ->with('error', 'Você não tem permissão para imprimir esta receita.');
            }
        }

        $pdf = PDF::loadView('receitas.pdf', compact('receita'));
        return $pdf->stream('receita_' . $id . '.pdf');
    }
}
