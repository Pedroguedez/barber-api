<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkingHourRequest;
use App\Http\Requests\UpdateWorkingHourRequest;
use App\Models\WorkingHour;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class WorkingHourController extends Controller
{
    public function index(): JsonResponse
    {
        $horarios = WorkingHour::all();

        return response()->json([
            'horarios' => $horarios
        ]);
    }

    public function store(StoreWorkingHourRequest $request): JsonResponse
    {
        // Verificar se o employee_id realmente é um barbeiro
        $barbeiro = User::where('id', $request->employee_id)
                                    ->where('level', 'Barber')
                                    ->first();

        if (!$barbeiro) {
            return response()->json(['error' => 'O funcionário informado não é um barbeiro.'], 422);
        }

        $horario = WorkingHour::create($request->validated());

        return response()->json(['success' => true, 'data' => $horario], 201);
    }
    public function update(UpdateWorkingHourRequest $request, int $id): JsonResponse
    {
        $horario = WorkingHour::find($id);
        if (!$horario) {
            return response()->json(['message' => 'Horario não encontrado'], 404);
        }

        if ($request->has('employee_id')) {
            $barbeiro = User::where('id', $request->employee_id)
                            ->where('level', 'Barber')
                            ->first();

            if (!$barbeiro) {
                return response()->json(['error' => 'O funcionário informado não é um barbeiro.'], 422);
            }
        }

        $horario->update($request->all());

        return response()->json($horario);
    }
    public function show(int $id): JsonResponse
    {
        $horario = WorkingHour::find($id);
        if (!$horario) {
            return response()->json(['message' => 'Horarios não encontrado'], 404);
        }
        return $horario;
    }
    public function destroy(int $id): JsonResponse
    {
        $horario = WorkingHour::find($id);
        if (!$horario) {
            return response()->json(['message' => 'Horario não encontrado'], 404);
        }

        $horario->delete();
        return response()->json(['message' => 'Horario deletado com sucesso']);
    }
}
