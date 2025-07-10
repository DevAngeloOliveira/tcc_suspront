<?php

namespace App\Http\Controllers;

use App\Models\Exame;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Consulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExameController extends Controller
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
        $query = Exame::with(['paciente', 'medico']);

        // Filtro por status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filtro por data de solicitação
        if ($request->has('data_solicitacao') && !empty($request->data_solicitacao)) {
            $query->whereDate('data_solicitacao', $request->data_solicitacao);
        }

        // Filtro por médico
        if ($request->has('medico_id') && !empty($request->medico_id)) {
            $query->where('medico_id', $request->medico_id);
        } elseif (Auth::user()->tipo == 'medico') {
            // Se for médico, mostrar apenas seus exames
            $query->where('medico_id', Auth::user()->medico->id);
        }

        // Filtro por paciente
        if ($request->has('paciente_id') && !empty($request->paciente_id)) {
            $query->where('paciente_id', $request->paciente_id);
        }

        // Busca por termo (nome do paciente ou tipo de exame)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tipo_exame', 'like', '%' . $search . '%')
                    ->orWhereHas('paciente', function ($q2) use ($search) {
                        $q2->where('nome', 'like', '%' . $search . '%');
                    });
            });
        }

        // Lista de médicos para o filtro (apenas para admin e atendentes)
        $medicos = [];
        if (Auth::user()->tipo != 'medico') {
            $medicos = Medico::orderBy('nome')->get();
        }

        $exames = $query->orderBy('data_solicitacao', 'desc')->paginate(10);
        return view('exames.index', compact('exames', 'medicos'));
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

        // Se a consulta for especificada pela URL
        $consulta = null;
        if ($request->has('consulta_id')) {
            $consulta = Consulta::with(['paciente', 'medico'])->findOrFail($request->consulta_id);
            $paciente = $consulta->paciente;
        }

        // Listar todos os pacientes caso não tenha sido especificado
        $pacientes = Paciente::orderBy('nome')->get();

        // Listar médicos para seleção (se não for médico logado)
        $medicos = [];
        if (Auth::user()->tipo != 'medico') {
            $medicos = Medico::orderBy('nome')->get();
        }

        // Listar consultas relevantes
        $consultas = [];
        if ($paciente) {
            $consultas = Consulta::with('medico')
                ->where('paciente_id', $paciente->id)
                ->orderBy('data_hora', 'desc')
                ->get();
        } else {
            $consultas = Consulta::with(['paciente', 'medico'])
                ->orderBy('data_hora', 'desc')
                ->limit(50)
                ->get();
        }

        return view('exames.create', compact('paciente', 'pacientes', 'medicos', 'consulta', 'consultas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'consulta_id' => 'nullable|exists:consultas,id',
            'tipo_exame' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_solicitacao' => 'required|date',
            'observacoes' => 'nullable|string',
        ]);

        // Adicionar status padrão
        $validated['status'] = 'solicitado';

        try {
            $exame = Exame::create($validated);
            return redirect()->route('exames.show', $exame->id)
                ->with('success', 'Exame solicitado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao solicitar exame: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $exame = Exame::with(['paciente', 'medico', 'consulta.medico'])->findOrFail($id);
        return view('exames.show', compact('exame'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $exame = Exame::with(['paciente', 'medico', 'consulta'])->findOrFail($id);

        // Verificar permissões - apenas médico que solicitou ou admin podem editar
        if (Auth::user()->tipo == 'medico' && Auth::user()->medico->id != $exame->medico_id) {
            return redirect()->route('exames.show', $exame->id)
                ->with('error', 'Você não tem permissão para editar este exame.');
        }

        return view('exames.edit', compact('exame'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $exame = Exame::findOrFail($id);

        // Verificar permissões - apenas médico que solicitou ou admin podem editar
        if (Auth::user()->tipo == 'medico' && Auth::user()->medico->id != $exame->medico_id) {
            return redirect()->route('exames.show', $exame->id)
                ->with('error', 'Você não tem permissão para editar este exame.');
        }

        $rules = [
            'tipo_exame' => 'nullable|string|max:255',  // Mudado para nullable
            'data_solicitacao' => 'nullable|date',      // Mudado para nullable
            'status' => 'required|in:solicitado,agendado,realizado,cancelado',
        ];

        // Se tipo_exame ou data_solicitacao estiverem presentes, então são obrigatórios
        if ($request->has('tipo_exame')) {
            $rules['tipo_exame'] = 'required|string|max:255';
        }
        if ($request->has('data_solicitacao')) {
            $rules['data_solicitacao'] = 'required|date';
        }

        // Regras adicionais
        if ($request->status == 'agendado' || $request->status == 'realizado') {
            $rules['data_realizacao'] = 'required|date';
        }
        if ($request->status == 'realizado') {
            $rules['resultado'] = 'nullable|string';
            $rules['arquivo_resultado'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        $validated = $request->validate($rules);

        try {
            if ($request->hasFile('arquivo_resultado')) {
                // Remover arquivo antigo se existir
                if ($exame->arquivo_resultado) {
                    Storage::disk('public')->delete($exame->arquivo_resultado);
                }

                // Armazenar novo arquivo
                $path = $request->file('arquivo_resultado')->store('exames', 'public');
                $validated['arquivo_resultado'] = $path;
            }

            $exame->update($validated);

            return redirect()->route('exames.show', $exame->id)
                ->with('success', 'Exame atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao atualizar exame: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Apenas admin pode excluir exames
        if (Auth::user()->tipo != 'admin') {
            return redirect()->route('exames.index')
                ->with('error', 'Você não tem permissão para excluir exames.');
        }

        try {
            $exame = Exame::findOrFail($id);

            // Remover arquivo físico se existir
            if ($exame->arquivo_resultado) {
                Storage::disk('public')->delete($exame->arquivo_resultado);
            }

            // Armazenar paciente_id para redirecionamento
            $pacienteId = $exame->paciente_id;

            $exame->delete();

            return redirect()->route('pacientes.show', $pacienteId)
                ->with('success', 'Exame excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir exame: ' . $e->getMessage());
        }
    }

    /**
     * Método adicional para agendar um exame rapidamente
     */
    public function agendar(Request $request, string $id)
    {
        $exame = Exame::findOrFail($id);

        // Apenas admin ou atendente pode agendar
        if (Auth::user()->tipo == 'medico') {
            return redirect()->route('exames.show', $exame->id)
                ->with('error', 'Apenas administradores e atendentes podem agendar exames.');
        }

        $validated = $request->validate([
            'data_realizacao' => 'required|date|after_or_equal:today',
        ]);

        try {
            $exame->update([
                'status' => 'agendado',
                'data_realizacao' => $validated['data_realizacao'],
            ]);

            return redirect()->route('exames.show', $exame->id)
                ->with('success', 'Exame agendado com sucesso para ' . date('d/m/Y', strtotime($validated['data_realizacao'])));
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao agendar exame: ' . $e->getMessage());
        }
    }
}
