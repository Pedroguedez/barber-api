<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceResquest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $Services = Service::all();

        return response()->json([
            'Services' => $Services
        ]);
    }
    public function store(StoreServiceResquest $request)
    {
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

    public function update(UpdateServiceRequest $request, $id)
    {
        $Service = Service::find($id);
        if (!$Service) {
            return response()->json(['message' => 'Serviço não encontrado'], 404);
        }

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
