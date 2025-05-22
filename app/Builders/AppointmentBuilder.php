<?

namespace App\Builders;

use App\Models\Appointment;
use Carbon\Carbon;
use Exception;

class AppointmentBuilder
{
    private array $dados = [];

    public function paraCliente(int $id): self
    {
        $this->dados['client_id'] = $id;
        return $this;
    }

    public function comBarbeiro(int $id): self
    {
        $this->dados['barber_id'] = $id;
        return $this;
    }

    public function comServico(int $id): self
    {
        $this->dados['service_id'] = $id;
        return $this;
    }

    public function naData(string $data): self
    {
        $this->dados['data'] = Carbon::parse($data);
        return $this;
    }

    public function noHorario(string $hora): self
    {
        $this->dados['time'] = Carbon::parse($hora);
        return $this;
    }

    public function comStatus(string $status): self
    {
        $this->dados['status'] = $status;
        return $this;
    }
    public function comObservacao(string $notes): self
    {
        $this->dados['notes'] = $notes;
        return $this;
    }

    public function criar(): Appointment
    {
        // Verifica de disponibilidade
        $existe = Appointment::where('barber_id', $this->dados['barber_id'])
            ->where('data', $this->dados['data'])
            ->where('time', $this->dados['time'])
            ->exists();

        if ($existe) {
            throw new Exception('Horário indisponível para esse barbeiro.');
        }

        return Appointment::create($this->dados);
    }
}
