<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $Services = Service::all();

        return response()->json([
            'Services' => $Services
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
        ]);

        $Service = Service::create($request->all());
        return response()->json($Service, 201);
    }

    public function show($id)
    {
        $Service = Service::find($id);
        if (!$Service) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }
        return $Service;
    }

    public function update(Request $request, $id)
    {
        $Service = Service::find($id);
        if (!$Service) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }

        $request->validate([
            'nome' => 'string',
            'preco' => 'numeric|min:0',
            'duracao' => 'integer|min:1',
            'status' => 'in:ativo,inativo',
        ]);

        $Service->update($request->all());
        return response()->json($Service);
    }

    public function destroy($id)
    {
        $Service = Service::find($id);
        if (!$Service) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }

        $Service->delete();
        return response()->json(['message' => 'Serviço deletado com sucesso']);
    }
}
