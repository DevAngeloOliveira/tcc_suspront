<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ConsultaRemarcacaoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    // Consultas
    Route::get('/consultas', [ConsultaController::class, 'apiListar']);
    Route::get('/consultas/horarios-disponiveis', [ConsultaController::class, 'apiHorariosDisponiveis']);
    Route::get('/consultas/{id}', [ConsultaController::class, 'apiObter']);
    Route::put('/consultas/{id}/status', [ConsultaController::class, 'apiAtualizarStatus']);

    // API para remarcação de consultas
    Route::post('/consultas/verificar-disponibilidade', [ConsultaRemarcacaoController::class, 'verificarDisponibilidade']);

    // Médicos
    Route::get('/medicos/especialidade/{especialidade}', [MedicoController::class, 'apiPorEspecialidade']);

    // Pacientes
    Route::get('/pacientes/{id}/consultas', [PacienteController::class, 'apiConsultas']);
});
