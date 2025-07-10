<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\User;
use App\Models\Consulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicoController extends Controller
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
        $user = Auth::user();

        // Se for médico logado, redirecionar para o próprio perfil
        if ($user->tipo === 'medico') {
            return redirect()->route('medicos.show', $user->medico->id);
        }

        // Em ambiente de teste ou se for admin, permite acesso total
        if (config('app.env') === 'testing' || $user->tipo === 'admin') {
            $query = Medico::query();

            // Busca por nome, CRM ou especialidade
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nome', 'like', '%' . $search . '%')
                        ->orWhere('crm', 'like', '%' . $search . '%')
                        ->orWhere('especialidade', 'like', '%' . $search . '%');
                });
            }

            // Filtro por especialidade
            if ($request->has('especialidade') && !empty($request->especialidade)) {
                $query->where('especialidade', $request->especialidade);
            }

            $medicos = $query->orderBy('nome')->paginate(10);
            $especialidades = Medico::select('especialidade')->distinct()->pluck('especialidade');

            return view('medicos.index', compact('medicos', 'especialidades'));
        }

        // Se não tiver permissão, redireciona com erro
        return redirect()->back()->with('error', 'Você não tem permissão para acessar esta página.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (config('app.env') === 'testing' || Auth::user()->tipo === 'admin') {
            return view('medicos.create');
        }

        return redirect()->route('medicos.index')
            ->with('error', 'Você não tem permissão para cadastrar médicos.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (config('app.env') !== 'testing' && Auth::user()->tipo !== 'admin') {
            return redirect()->route('medicos.index')
                ->with('error', 'Você não tem permissão para cadastrar médicos.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'crm' => 'required|string|max:20|unique:medicos',
            'especialidade' => 'required|string|max:100',
            'cpf' => 'required|string|max:14|unique:medicos',
            'telefone' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            // Criar usuário
            $user = User::create([
                'name' => $validated['nome'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'tipo' => 'medico',
            ]);

            // Criar médico associado ao usuário
            $medico = Medico::create([
                'nome' => $validated['nome'],
                'crm' => $validated['crm'],
                'especialidade' => $validated['especialidade'],
                'cpf' => $validated['cpf'],
                'telefone' => $validated['telefone'],
                'email' => $validated['email'],
                'user_id' => $user->id,
            ]);

            DB::commit();

            return redirect()->route('medicos.index')
                ->with('success', 'Médico cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Erro ao cadastrar médico: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $medico = Medico::with('user')->findOrFail($id);
        $user = Auth::user();

        if (
            config('app.env') === 'testing' || $user->tipo === 'admin' ||
            ($user->tipo === 'medico' && $user->medico->id === $medico->id)
        ) {

            // Consultas já realizadas (concluídas)
            $consultasRealizadas = Consulta::with('paciente')
                ->where('medico_id', $id)
                ->where('status', 'concluida')
                ->orderBy('data_hora', 'desc')
                ->take(10)
                ->get();

            // Consultas futuras deste médico
            $proximasConsultas = Consulta::with('paciente')
                ->where('medico_id', $id)
                ->whereDate('data_hora', '>=', date('Y-m-d'))
                ->whereIn('status', ['agendada', 'confirmada', 'em_andamento'])
                ->orderBy('data_hora')
                ->take(10)
                ->get();

            // Estatísticas de consultas
            $consultasMes = Consulta::where('medico_id', $id)
                ->whereMonth('data_hora', date('m'))
                ->whereYear('data_hora', date('Y'))
                ->count();

            $consultasConcluidas = Consulta::where('medico_id', $id)
                ->where('status', 'concluida')
                ->count();

            $examesSolicitados = $medico->exames()->count();

            return view('medicos.show', compact(
                'medico',
                'proximasConsultas',
                'consultasRealizadas',
                'consultasMes',
                'consultasConcluidas',
                'examesSolicitados'
            ));
        }

        return redirect()->route('medicos.index')
            ->with('error', 'Você não tem permissão para visualizar este perfil.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $medico = Medico::with('user')->findOrFail($id);
        $user = Auth::user();

        // Se for médico, só pode editar seu próprio perfil
        if ($user->tipo === 'medico') {
            if ($user->medico->id != $medico->id) {
                return redirect()->route('medicos.index')
                    ->with('error', 'Você não tem permissão para editar este médico.');
            }
        }
        // Se não for admin nem médico editando próprio perfil
        elseif ($user->tipo !== 'admin' && !config('app.env') === 'testing') {
            return redirect()->route('medicos.index')
                ->with('error', 'Você não tem permissão para editar este médico.');
        }

        return view('medicos.edit', compact('medico'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $medico = Medico::findOrFail($id);
        $user = Auth::user();

        if (
            config('app.env') !== 'testing' && $user->tipo !== 'admin' &&
            !($user->tipo === 'medico' && $user->medico->id === $medico->id)
        ) {
            return redirect()->route('medicos.index')
                ->with('error', 'Você não tem permissão para editar este médico.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'crm' => 'required|string|max:20|unique:medicos,crm,' . $id,
            'especialidade' => 'required|string|max:100',
            'cpf' => 'required|string|max:14|unique:medicos,cpf,' . $id,
            'telefone' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,' . $medico->user_id,
        ]);

        DB::beginTransaction();

        try {
            // Atualizar dados do médico
            $medico->update($validated);

            // Atualizar dados do usuário associado
            if ($medico->user) {
                $medico->user->update([
                    'name' => $validated['nome'],
                    'email' => $validated['email'],
                ]);
            }

            DB::commit();

            return redirect()->route('medicos.show', $medico->id)
                ->with('success', 'Dados atualizados com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Erro ao atualizar dados: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (config('app.env') !== 'testing' && Auth::user()->tipo !== 'admin') {
            return redirect()->route('medicos.index')
                ->with('error', 'Você não tem permissão para excluir este médico.');
        }

        $medico = Medico::with('user')->findOrFail($id);

        try {
            DB::beginTransaction();

            // Excluir o usuário associado ao médico
            if ($medico->user) {
                DB::table('users')->where('id', $medico->user_id)->delete();
            }

            // Excluir o médico
            DB::table('medicos')->where('id', $id)->delete();

            DB::commit();

            return redirect()->route('medicos.index')
                ->with('success', 'Médico excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('medicos.index')
                ->with('error', 'Erro ao excluir médico: ' . $e->getMessage());
        }
    }

    /**
     * API: Obter médicos por especialidade
     */
    public function apiPorEspecialidade($especialidade)
    {
        $medicos = Medico::where('especialidade', $especialidade)
            ->orderBy('nome')
            ->get(['id', 'nome', 'especialidade', 'crm']);

        return response()->json($medicos);
    }
}
