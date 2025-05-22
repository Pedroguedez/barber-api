<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkingHourController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//-----------------------------Usuários-------------------------------

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
//----------------------------Usuarios------------------------------
    Route::post('/usuarios', [UserController::class, 'store']);
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::get('/usuarios/{id}', [UserController::class, 'show']);
    Route::put('/usuarios/{id}', [UserController::class, 'update']);
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);
    Route::get('/barbeiros', [UserController::class, 'getBarbeiros']);

//-----------------------------Appointments---------------------------
    Route::post('/agendamentos', [AppointmentController::class, 'store']);
    Route::get('/agendamentos', [AppointmentController::class,'index']);
    Route::get('/agendamentos/{id}', [AppointmentController::class, 'show']);
    Route::put('/agendamentos/{id}/status', [AppointmentController::class, 'atualizarStatus']);

//-----------------------------Servicos-------------------------------
    Route::post('/servicos', [ServiceController::class, 'store']);
    Route::get('/servicos', [ServiceController::class,'index']);
    Route::get('/servicos/{id}', [ServiceController::class, 'show']);
    Route::put('/servicos/{id}', [ServiceController::class, 'update']);
    Route::delete('/servicos/{id}', [ServiceController::class, 'destroy']);

//----------------------------HorarioFuncionamento---------------------
    Route::post('/horariosFuncionamento', [WorkingHourController::class, 'store']);
    Route::get('/horariosFuncionamento', [WorkingHourController::class,'index']);
    Route::get('/horariosFuncionamento/{id}', [WorkingHourController::class, 'show']);
    Route::put('/horariosFuncionamento/{id}', [WorkingHourController::class, 'update']);
    Route::delete('/horariosFuncionamento/{id}', [WorkingHourController::class, 'destroy']);

//-----------------------------------------------
    Route::post('/horariosdisponiveis', [AppointmentController::class, 'horariosDisponiveis']);

});
