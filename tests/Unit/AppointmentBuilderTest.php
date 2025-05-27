<?php

namespace Tests\Unit;

use App\Builders\AppointmentBuilder;
use App\Models\Appointment;
use Carbon\Carbon;
use Exception;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class AppointmentBuilderTest extends TestCase
{
    private AppointmentBuilder $builder;
    private MockInterface $appointmentModelMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->appointmentModelMock = Mockery::mock('alias:' . Appointment::class);

        $this->builder = new AppointmentBuilder();
    }

    protected function tearDown(): void
    {
        Mockery::close(); // Importante para limpar os mocks do Mockery após cada teste
        parent::tearDown();
    }

    /**
     * Testa se o método criar retorna uma instância de Appointment
     * e chama Appointment::create() quando o horário está disponível.
     */
    public function test_criar_creates_and_returns_appointment_when_slot_is_available(): void
    {
        $clientId = 1;
        $barberId = 2;
        $serviceId = 3;
        $dateString = '2025-12-25';
        $timeString = '10:00:00';
        $status = 'pending';
        $notes = 'Teste de observação';

        $expectedDataForCreate = [
            'client_id' => $clientId,
            'barber_id' => $barberId,
            'service_id' => $serviceId,
            'data' => Carbon::parse($dateString),
            'time' => Carbon::parse($timeString),
            'status' => $status,
            'notes' => $notes,
        ];

        // Configura o mock para a verificação de disponibilidade
        // Appointment::where('barber_id', ...)->where('data', ...)->where('time', ...)->exists()
        $this->appointmentModelMock
            ->shouldReceive('where')->once()->with('barber_id', $barberId)->andReturnSelf()
            ->shouldReceive('where')->once()->with('data', Mockery::on(function ($arg) use ($dateString) {
                return $arg instanceof Carbon && $arg->equalTo(Carbon::parse($dateString));
            }))->andReturnSelf()
            ->shouldReceive('where')->once()->with('time', Mockery::on(function ($arg) use ($timeString) {
                return $arg instanceof Carbon && $arg->equalTo(Carbon::parse($timeString));
            }))->andReturnSelf()
            ->shouldReceive('exists')->once()->andReturn(false); // Horário disponível

        // Configura o mock para a criação do agendamento
        // Appointment::create($dados)
        $createdAppointmentInstance = new Appointment($expectedDataForCreate); // Instância real para retorno
        $this->appointmentModelMock
            ->shouldReceive('create')->once()
            ->with(Mockery::on(function ($arg) use ($expectedDataForCreate) {
                // Verifica se os dados passados para o create são os esperados
                // Compara campos e tipos, especialmente para Carbon
                return $arg['client_id'] === $expectedDataForCreate['client_id'] &&
                       $arg['barber_id'] === $expectedDataForCreate['barber_id'] &&
                       $arg['service_id'] === $expectedDataForCreate['service_id'] &&
                       $arg['status'] === $expectedDataForCreate['status'] &&
                       $arg['notes'] === $expectedDataForCreate['notes'] &&
                       ($arg['data'] instanceof Carbon && $arg['data']->equalTo($expectedDataForCreate['data'])) &&
                       ($arg['time'] instanceof Carbon && $arg['time']->equalTo($expectedDataForCreate['time']));
            }))
            ->andReturn($createdAppointmentInstance);

        // Act: Chama os métodos do builder e o criar()
        $appointment = $this->builder
            ->paraCliente($clientId)
            ->comBarbeiro($barberId)
            ->comServico($serviceId)
            ->naData($dateString)
            ->noHorario($timeString)
            ->comStatus($status)
            ->comObservacao($notes)
            ->criar();

        // Assert: Verifica se o retorno é a instância esperada
        $this->assertInstanceOf(Appointment::class, $appointment);
        $this->assertSame($createdAppointmentInstance, $appointment); // Verifica se é a mesma instância mockada
    }

    /**
     * Testa se o método criar lança uma exceção
     * quando o horário está indisponível.
     */
    public function test_criar_throws_exception_when_slot_is_unavailable(): void
    {
        $clientId = 1;
        $barberId = 2;
        $serviceId = 3;
        $dateString = '2025-12-25';
        $timeString = '10:00:00';

        // Configura o mock para a verificação de disponibilidade
        $this->appointmentModelMock
            ->shouldReceive('where')->once()->with('barber_id', $barberId)->andReturnSelf()
            ->shouldReceive('where')->once()->with('data', Mockery::on(function ($arg) use ($dateString) {
                return $arg instanceof Carbon && $arg->equalTo(Carbon::parse($dateString));
            }))->andReturnSelf()
            ->shouldReceive('where')->once()->with('time', Mockery::on(function ($arg) use ($timeString) {
                return $arg instanceof Carbon && $arg->equalTo(Carbon::parse($timeString));
            }))->andReturnSelf()
            ->shouldReceive('exists')->once()->andReturn(true); // Horário INDISPONÍVEL

        // Espera que Appointment::create() NÃO seja chamado
        $this->appointmentModelMock->shouldNotReceive('create');

        // Assert: Espera a exceção
        // Seria melhor usar uma exceção customizada aqui (ex: HorarioIndisponivelException::class)
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Horário indisponível para esse barbeiro.');

        // Act: Chama os métodos do builder e o criar()
        $this->builder
            ->paraCliente($clientId)
            ->comBarbeiro($barberId)
            ->comServico($serviceId)
            ->naData($dateString)
            ->noHorario($timeString)
            ->comStatus('pending') // Status e notes não importam tanto aqui
            ->comObservacao('Qualquer nota')
            ->criar();
    }

    /**
     * Testa se todos os setters do builder configuram os dados corretamente.
     * Esta é uma forma de verificar os dados internos antes do criar(),
     * o que é validado implicitamente nos testes acima, mas pode ser explícito.
     */
    public function test_all_setters_prepare_data_correctly_for_creation(): void
    {
        $clientId = 10;
        $barberId = 20;
        $serviceId = 30;
        $dateString = '2026-01-15';
        $timeString = '15:30:00';
        $status = 'confirmed';
        $notes = 'Apenas corte de cabelo';

        $expectedData = [
            'client_id' => $clientId,
            'barber_id' => $barberId,
            'service_id' => $serviceId,
            'data' => Carbon::parse($dateString),
            'time' => Carbon::parse($timeString),
            'status' => $status,
            'notes' => $notes,
        ];

        // Mock para `exists` retornando false (horário disponível)
        $this->appointmentModelMock
            ->shouldReceive('where->where->where->exists')->andReturn(false);

        // Mock para `create` esperando os dados corretos
        $createdAppointment = new Appointment($expectedData);
        $this->appointmentModelMock
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($actualData) use ($expectedData) {
                $this->assertEquals($expectedData['client_id'], $actualData['client_id']);
                $this->assertEquals($expectedData['barber_id'], $actualData['barber_id']);
                $this->assertEquals($expectedData['service_id'], $actualData['service_id']);
                $this->assertTrue($actualData['data']->equalTo($expectedData['data']));
                $this->assertTrue($actualData['time']->equalTo($expectedData['time']));
                $this->assertEquals($expectedData['status'], $actualData['status']);
                $this->assertEquals($expectedData['notes'], $actualData['notes']);
                return true; // Se todas as asserções passarem
            }))
            ->andReturn($createdAppointment);

        $this->builder
            ->paraCliente($clientId)
            ->comBarbeiro($barberId)
            ->comServico($serviceId)
            ->naData($dateString)
            ->noHorario($timeString)
            ->comStatus($status)
            ->comObservacao($notes)
            ->criar();

        // A verificação principal é feita pela expectativa do Mockery (`with(...)`).
        // Se o teste chegar aqui sem erros do Mockery, significa que create foi chamado com os dados corretos.
    }
}