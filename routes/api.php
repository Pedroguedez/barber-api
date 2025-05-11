<?php

use App\Http\Controllers\AgendamentoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//-----------------------------Usuários-------------------------------

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/usuarios', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::get('/usuarios/{id}', [UserController::class, 'show']);
    Route::put('/usuarios/{id}', [UserController::class, 'update']);
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);


//-----------------------------Agendamentos
    Route::get('/agendamentos', [AgendamentoController::class,'index']);
    Route::post('/agendamentos', [AgendamentoController::class, 'store']);
    Route::put('/agendamentos/{id}/status', [AgendamentoController::class, 'atualizarStatus']);
});
