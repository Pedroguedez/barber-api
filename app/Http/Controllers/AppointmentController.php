<?php

namespace App\Http\Controllers;

use App\Builders\AppointmentBuilder;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Http\Requests\AppointmentRequest;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    private array $statusPermitidos = ['pending', 'confirmed', 'canceled', 'completed'];

    public function index(): JsonResponse
    {
        $appointment = Appointment::all();

        return response()->json([
            'data' => $appointment,
            'message' => 'Lista de agendamentos'
        ]);
    }
    public function store(AppointmentRequest $request): JsonResponse
    {
        try {
            $appointment = (new AppointmentBuilder())
                ->paraCliente($request->client_id)
                ->comBarbeiro($request->barber_id)
                ->comServico($request->service_id)
                ->naData($request->data)
                ->noHorario($request->time)
                ->comStatus($request->status ?? 'pending')
                ->comObservacao($request->notes ?? null)
                ->criar();

            return response()->json(['data' => $appointment], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
    public function show(int $id): JsonResponse
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return $this->notFoundResponse();
        }

        return response()->json(['data' => $appointment]);
    }
    public function atualizarStatus(Request $request, int $id): JsonResponse
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return $this->notFoundResponse();
        }

        $status = $request->input('status');

        if (!in_array($status, $this->statusPermitidos)) {
            return response()->json(['message' => 'Status inválido'], 400);
        }

        $appointment->update(['status' => $status]);

        return response()->json(['message' => "Status atualizado para {$status} com sucesso"]);
    }
    public function horariosDisponiveis(Request $request, AppointmentService $appointmentService)
    {
        $horarios = $appointmentService->listarHorariosDisponiveis(
            $request->input('data'),
            $request->input('barber_id'),
            $request->input('service_id')
        );

        return response()->json($horarios);
    }

    private function notFoundResponse(): JsonResponse
    {
        return response()->json(['message' => 'Appointment não encontrado'], 404);
    }
}
