<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;

class AgendamentoController extends Controller
{
    public function index()
    {
        $agendamentos = Agendamento::all();

        return response()->json([
            'agendamentos' => $agendamentos
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:users,id',
            'barbeiro_id' => 'required|exists:users,id',
            'data' => 'required|date',
            'horario' => 'required|date_format:H:i',
            'status' => 'in:pendente,confirmado,cancelado,concluido',
            'observacao' => 'nullable|string',
        ]);

        $agendamento = Agendamento::create($validated);

        return response()->json([
            'message' => 'Agendamento criado com sucesso!',
            'data' => $agendamento
        ], 201);
    }

    public function atualizarStatus(Request $request, $id)
    {
        $agendamento = Agendamento::find($id);

        if (!$agendamento) {
            return response()->json(['message' => 'Agendamento não encontrado'], 404);
        }

        $status = $request->input('status');

        $statusPermitidos = ['pendente', 'confirmado', 'cancelado', 'concluido'];

        if (!in_array($status, $statusPermitidos)) {
            return response()->json(['message' => 'Status inválido'], 400);
        }

        $agendamento->status = $status;
        $agendamento->save();

        return response()->json(['message' => "Status atualizado para {$status} com sucesso"]);
    }
}
