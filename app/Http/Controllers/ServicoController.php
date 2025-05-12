<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function index()
    {
        $servicos = Servico::all();

        return response()->json([
            'servicos' => $servicos
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'preco' => 'required|numeric|min:0',
            'duracao' => 'required|integer|min:1',
            'status' => 'in:ativo,inativo',
        ]);

        $servico = Servico::create($request->all());
        return response()->json($servico, 201);
    }

    public function show($id)
    {
        $servico = Servico::find($id);
        if (!$servico) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }
        return $servico;
    }

    public function update(Request $request, $id)
    {
        $servico = Servico::find($id);
        if (!$servico) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }

        $request->validate([
            'nome' => 'string',
            'preco' => 'numeric|min:0',
            'duracao' => 'integer|min:1',
            'status' => 'in:ativo,inativo',
        ]);

        $servico->update($request->all());
        return response()->json($servico);
    }

    public function destroy($id)
    {
        $servico = Servico::find($id);
        if (!$servico) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }

        $servico->delete();
        return response()->json(['message' => 'Serviço deletado com sucesso']);
    }
}
