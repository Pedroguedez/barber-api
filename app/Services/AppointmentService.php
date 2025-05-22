<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\WorkingHour;
use Carbon\Carbon;

class AppointmentService
{
    public function listarHorariosDisponiveis(string $data, int $barberId, int $serviceId): array
    {
        $dataSelecionada = Carbon::parse($data);
        $weekday = $dataSelecionada->locale('EN')->dayName;


        $horarios = WorkingHour::where('employee_id', $barberId)
            ->where('weekday', $weekday)
            ->first();

        if (!$horarios) return [];

        $duracaoServico = Service::findOrFail($serviceId)->duration;

        $agendamentos = Appointment::where('barber_id', $barberId)
        ->whereDate('data', $dataSelecionada->toDateString())
        ->with('service')
        ->get();

        $horariosOcupados = [];

        foreach ($agendamentos as $agendamento) {
            $inicio = Carbon::createFromFormat('Y-m-d H:i:s', $agendamento->data . ' ' . $agendamento->time);
            $fim = (clone $inicio)->addMinutes($agendamento->service->duration ?? $duracaoServico);

            while ($inicio < $fim) {
                $horariosOcupados[] = $inicio->format('H:i');
                $inicio->addMinutes(15);
            }
        }

        $todosHorarios = array_merge(
            $this->gerarHorarios($horarios->opening_morning, $horarios->closing_morning, $duracaoServico),
            $this->gerarHorarios($horarios->late_opening, $horarios->late_closing, $duracaoServico)
        );

        return array_values(array_diff($todosHorarios, $horariosOcupados));
    }

    private function gerarHorarios($inicio, $fim, $duracao): array
    {
        $horarios = [];
        $horaAtual = Carbon::parse($inicio);
        $horaFim = Carbon::parse($fim);

        while ($horaAtual->copy()->addMinutes($duracao) <= $horaFim) {
            $horarios[] = $horaAtual->format('H:i');
            $horaAtual->addMinutes(15);
        }

        return $horarios;
    }
}
