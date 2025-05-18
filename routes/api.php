<?php

use App\Http\Controllers\AgendamentoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HorariosFuncionamentoController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//-----------------------------Usuários-------------------------------

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/usuarios', [UserController::class, 'store']);
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::get('/usuarios/{id}', [UserController::class, 'show']);
    Route::put('/usuarios/{id}', [UserController::class, 'update']);
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);
    Route::get('/barbeiros', [UserController::class, 'getBarbeiros']);

//-----------------------------Agendamentos---------------------------
    Route::post('/agendamentos', [AgendamentoController::class, 'store']);
    Route::get('/agendamentos', [AgendamentoController::class,'index']);
    Route::get('/agendamentos/{id}', [AgendamentoController::class, 'show']);
    Route::put('/agendamentos/{id}/status', [AgendamentoController::class, 'atualizarStatus']);

//-----------------------------Servicos-------------------------------
    Route::post('/servicos', [ServicoController::class, 'store']);
    Route::get('/servicos',[ServicoController::class,'index']);
    Route::get('/servicos/{id}', [ServicoController::class, 'show']);
    Route::put('/servicos/{id}', [ServicoController::class, 'update']);
    Route::delete('/servicos/{id}', [ServicoController::class, 'destroy']);

//----------------------------HorarioFuncionamento
    Route::post('/horariosFuncionamento', [HorariosFuncionamentoController::class, 'store']);
    Route::get('/horariosFuncionamento', [HorariosFuncionamentoController::class,'index']);
    Route::get('/horariosFuncionamento/{id}', [HorariosFuncionamentoController::class, 'show']);
    Route::put('/horariosFuncionamento/{id}', [HorariosFuncionamentoController::class, 'update']);
    Route::delete('/horariosFuncionamento/{id}', [HorariosFuncionamentoController::class, 'destroy']);
});
