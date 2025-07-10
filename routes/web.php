<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\AtendenteController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\ProntuarioController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\ExameController;

// Página inicial (sem autenticação)
Route::get('/', function () {
    return view('welcome');
});

// Documentação da API (apenas para admins)
Route::get('/api/doc', function () {
    $markdown = file_get_contents(base_path('docs/api-documentation.md'));
    return view('api.documentation', ['markdown' => $markdown]);
})->middleware(['auth', 'admin'])->name('api.documentation');

// Rotas de Autenticação
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Rotas autenticadas
Route::middleware(['auth'])->group(function () {
    // Dashboard (Livewire)
    Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');

    // Pacientes
    Route::resource('pacientes', PacienteController::class);

    // Médicos (Rota principal usa Livewire)
    Route::get('/medicos', \App\Livewire\Medicos\MedicosList::class)->name('medicos.index')->middleware('admin');
    Route::resource('medicos', MedicoController::class)->except(['index'])->middleware('admin');

    // Atendentes (Rota principal usa Livewire)
    Route::get('/atendentes', \App\Livewire\Atendentes\AtendentesList::class)->name('atendentes.index')->middleware('admin');
    Route::resource('atendentes', AtendenteController::class)->except(['index'])->middleware('admin');

    // Prontuários (Rota principal usa Livewire)
    Route::get('/prontuarios', \App\Livewire\Prontuarios\ProntuariosList::class)->name('prontuarios.index');
    Route::resource('prontuarios', ProntuarioController::class)->except(['index']);

    // Consultas (Rota principal usa Livewire)
    Route::get('/consultas', \App\Livewire\Consultas\ConsultaList::class)->name('consultas.index');
    Route::resource('consultas', ConsultaController::class)->except(['index']);
    Route::put('consultas/{id}/concluir', [ConsultaController::class, 'concluir'])->name('consultas.concluir');
    Route::put('consultas/{id}/registrar-atendimento', [ConsultaController::class, 'registrarAtendimento'])->name('consultas.registrarAtendimento');
    Route::put('consultas/{id}/status', [ConsultaController::class, 'updateStatus'])->name('consultas.updateStatus');

    // Remarcação de consultas
    Route::get('consultas/{id}/remarcacao', [App\Http\Controllers\ConsultaRemarcacaoController::class, 'edit'])->name('consultas.remarcacao.edit');
    Route::put('consultas/{id}/remarcacao', [App\Http\Controllers\ConsultaRemarcacaoController::class, 'update'])->name('consultas.remarcacao.update');

    // Receitas (Já tem componente Livewire ReceitasList)
    Route::get('/receitas', function () {
        return view('receitas.index');
    })->name('receitas.index');
    Route::resource('receitas', ReceitaController::class)->except(['index']);
    Route::get('receitas/{id}/imprimir', [ReceitaController::class, 'imprimir'])->name('receitas.imprimir');

    // Exames (Rota principal usa Livewire)
    Route::get('/exames', \App\Livewire\Exames\ExamesList::class)->name('exames.index');
    Route::resource('exames', ExameController::class)->except(['index']);
    Route::post('exames/{id}/agendar', [ExameController::class, 'agendar'])->name('exames.agendar');

    // Notificações
    Route::get('notificacoes', [App\Http\Controllers\NotificacaoController::class, 'index'])->name('notificacoes.index');
    Route::put('notificacoes/{id}/marcar-como-lida', [App\Http\Controllers\NotificacaoController::class, 'marcarComoLida'])->name('notificacoes.marcar-como-lida');
    Route::put('notificacoes/marcar-todas-como-lidas', [App\Http\Controllers\NotificacaoController::class, 'marcarTodasComoLidas'])->name('notificacoes.marcar-todas-como-lidas');
});
