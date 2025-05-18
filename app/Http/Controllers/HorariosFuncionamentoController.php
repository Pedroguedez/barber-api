<?php

namespace App\Http\Controllers;

use App\Models\HorarioFuncionamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorariosFuncionamentoController extends Controller
{
    public function index()
    {
        $horarios = HorarioFuncionamento::all();

        return response()->json([
            'horarios' => $horarios
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_funcionario' => 'required|exists:users,id',
            'dia_semana' => 'required|string',
            'abertura_manha' => 'required',
            'fechamento_manha' => 'required',
            'abertura_tarde' => 'required',
            'fechamento_tarde' => 'required',
        ]);

        // Verificar se o id_funcionario realmente é um barbeiro
        $barbeiro = User::where('id', $request->id_funcionario)
                                    ->where('nivel', 'Barbeiro')
                                    ->first();

        if (!$barbeiro) {
            return response()->json(['error' => 'O funcionário informado não é um barbeiro.'], 422);
        }

        $horario = HorarioFuncionamento::create($request->all());

        return response()->json(['success' => true, 'data' => $horario], 201);
    }
    public function update(Request $request, $id)
    {
        $horario = HorarioFuncionamento::find($id);
        if (!$horario) {
            return response()->json(['message' => 'Horario não encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_funcionario' => 'sometimes|exists:users,id',
            'dia_semana' => 'sometimes|string',
            'abertura_manha' => 'sometimes|date_format:H:i',
            'fechamento_manha' => 'sometimes|date_format:H:i|after:abertura_manha',
            'abertura_tarde' => 'sometimes|date_format:H:i',
            'fechamento_tarde' => 'sometimes|date_format:H:i|after:abertura_tarde',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Só verifica se é barbeiro se o id_funcionario foi enviado
        if ($request->has('id_funcionario')) {
            $barbeiro = User::where('id', $request->id_funcionario)
                            ->where('nivel', 'Barbeiro')
                            ->first();

            if (!$barbeiro) {
                return response()->json(['error' => 'O funcionário informado não é um barbeiro.'], 422);
            }
        }

        $horario->update($request->all());

        return response()->json($horario);
    }
    public function show($id)
    {
        $horario = HorarioFuncionamento::find($id);
        if (!$horario) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }
        return $horario;
    }
    public function destroy($id)
    {
        $horario = HorarioFuncionamento::find($id);
        if (!$horario) {
            return response()->json(['message' => 'Horario não encontrado'], 404);
        }

        $horario->delete();
        return response()->json(['message' => 'Horario deletado com sucesso']);
    }
}
