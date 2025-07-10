<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacaoController extends Controller
{
    /**
     * Construtor com middleware de autenticação.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibir lista de notificações do usuário logado.
     */
    public function index()
    {
        $notificacoes = Notificacao::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notificacoes.index', compact('notificacoes'));
    }

    /**
     * Marcar uma notificação como lida.
     */
    public function marcarComoLida(Request $request, $id)
    {
        $notificacao = Notificacao::where('user_id', Auth::id())
            ->findOrFail($id);

        $notificacao->marcarComoLida();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notificação marcada como lida.');
    }

    /**
     * Marcar todas as notificações do usuário como lidas.
     */
    public function marcarTodasComoLidas(Request $request)
    {
        Notificacao::where('user_id', Auth::id())
            ->where('lida', false)
            ->update([
                'lida' => true,
                'lida_em' => now()
            ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Todas as notificações foram marcadas como lidas.');
    }
}
