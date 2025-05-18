<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Http\Requests\StoreAgendamentoRequest;
use Illuminate\Http\JsonResponse;

class AgendamentoController extends Controller
{
    private array $statusPermitidos = ['pendente', 'confirmado', 'cancelado', 'concluido'];

    public function index(): JsonResponse
    {
        $agendamentos = Agendamento::all();

        return response()->json([
            'agendamentos' => $agendamentos,
            'message' => 'Lista de agendamentos'
        ]);
    }
    public function store(StoreAgendamentoRequest $request): JsonResponse
    {
        $agendamento = Agendamento::create($request->validated());

        return response()->json([
            'message' => 'Agendamento criado com sucesso!',
            'data' => $agendamento
        ], 201);
    }
    public function show(int $id): JsonResponse
    {
        $agendamento = Agendamento::find($id);

        if (!$agendamento) {
            return $this->notFoundResponse();
        }

        return response()->json(['agendamento' => $agendamento]);
    }
    public function atualizarStatus(Request $request, int $id): JsonResponse
    {
        $agendamento = Agendamento::find($id);

        if (!$agendamento) {
            return $this->notFoundResponse();
        }

        $status = $request->input('status');

        if (!in_array($status, $this->statusPermitidos)) {
            return response()->json(['message' => 'Status inválido'], 400);
        }

        $agendamento->update(['status' => $status]);

        return response()->json(['message' => "Status atualizado para {$status} com sucesso"]);
    }

    private function notFoundResponse(): JsonResponse
    {
        return response()->json(['message' => 'Agendamento não encontrado'], 404);
    }
}
