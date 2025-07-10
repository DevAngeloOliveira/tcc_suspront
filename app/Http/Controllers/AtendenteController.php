<?php

namespace App\Http\Controllers;

use App\Models\Atendente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AtendenteController extends Controller
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
        // Verificar permissão - apenas admin pode ver todos os atendentes
        if (Auth::user()->tipo != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta página.');
        }

        $query = Atendente::query();

        // Busca por nome, CPF ou email
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', '%' . $search . '%')
                    ->orWhere('cpf', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $atendentes = $query->orderBy('nome')->paginate(10);

        return view('atendentes.index', compact('atendentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar permissão - apenas admin pode criar atendentes
        if (Auth::user()->tipo != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para cadastrar atendentes.');
        }

        return view('atendentes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar permissão - apenas admin pode criar atendentes
        if (Auth::user()->tipo != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para cadastrar atendentes.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'registro' => 'required|string|max:20|unique:atendentes,registro',
            'cpf' => 'required|string|max:14|unique:atendentes,cpf',
            'telefone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            // Criar usuário
            $user = User::create([
                'name' => $validated['nome'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'tipo' => 'atendente',
            ]);

            // Criar atendente associado ao usuário
            $atendente = Atendente::create([
                'nome' => $validated['nome'],
                'registro' => $validated['registro'],
                'cpf' => $validated['cpf'],
                'telefone' => $validated['telefone'],
                'email' => $validated['email'],
                'user_id' => $user->id,
            ]);

            DB::commit();

            return redirect()->route('atendentes.index')
                ->with('success', 'Atendente cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Erro ao cadastrar atendente: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Verificar permissão - apenas admin pode ver detalhes de atendentes
        if (Auth::user()->tipo != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para visualizar este atendente.');
        }

        $atendente = Atendente::with('user')->findOrFail($id);

        // Estatísticas de atividades do atendente
        $atividadesMes = 0; // Implementar contagem de atividades se necessário

        return view('atendentes.show', compact('atendente', 'atividadesMes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Verificar permissão - apenas admin pode editar atendentes
        if (Auth::user()->tipo != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para editar atendentes.');
        }

        $atendente = Atendente::with('user')->findOrFail($id);
        return view('atendentes.edit', compact('atendente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Verificar permissão - apenas admin pode editar atendentes
        if (Auth::user()->tipo != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para editar atendentes.');
        }

        $atendente = Atendente::findOrFail($id);

        // Regras de validação
        $rules = [
            'nome' => 'required|string|max:255',
            'registro' => 'required|string|max:20|unique:atendentes,registro,' . $id,
            'cpf' => 'required|string|max:14|unique:atendentes,cpf,' . $id,
            'telefone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:atendentes,email,' . $id,
        ];

        // Se estiver alterando a senha
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            // Atualizar atendente
            $atendente->update([
                'nome' => $validated['nome'],
                'registro' => $validated['registro'],
                'cpf' => $validated['cpf'],
                'telefone' => $validated['telefone'],
                'email' => $validated['email'],
            ]);

            // Atualizar usuário associado
            if ($atendente->user) {
                $userUpdate = [
                    'name' => $validated['nome'],
                    'email' => $validated['email'],
                ];

                // Se estiver alterando a senha
                if ($request->filled('password')) {
                    $userUpdate['password'] = Hash::make($validated['password']);
                }

                $atendente->user->update($userUpdate);
            }

            DB::commit();

            return redirect()->route('atendentes.show', $atendente->id)
                ->with('success', 'Atendente atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Erro ao atualizar atendente: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Verificar permissão - apenas admin pode excluir atendentes
        if (Auth::user()->tipo != 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para excluir atendentes.');
        }

        $atendente = Atendente::findOrFail($id);

        DB::beginTransaction();

        try {
            // Armazenar o user_id para excluir o usuário associado
            $userId = $atendente->user_id;

            // Excluir o atendente
            $atendente->delete();

            // Excluir o usuário associado
            if ($userId) {
                User::destroy($userId);
            }

            DB::commit();

            return redirect()->route('atendentes.index')
                ->with('success', 'Atendente excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Erro ao excluir atendente: ' . $e->getMessage());
        }
    }
}
