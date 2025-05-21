<?

namespace App\Builders;

use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentBuilder
{
    private array $dados = [];

    public function paraCliente(int $id): self
    {
        $this->dados['cliente_id'] = $id;
        return $this;
    }

    public function comBarbeiro(int $id): self
    {
        $this->dados['barber_id'] = $id;
        return $this;
    }

    public function comServico(int $id): self
    {
        $this->dados['services_id'] = $id;
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
        return Appointment::create($this->dados);
    }
}
