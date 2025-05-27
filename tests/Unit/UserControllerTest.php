<?php

namespace Tests\Unit;

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    protected UserController $userController;
    protected MockInterface $userModelMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModelMock = Mockery::mock('alias:' . User::class);

        $this->userController = new UserController();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Testa se o método getBarbeiros retorna os barbeiros corretamente formatados.
     * @test
     */
    public function getBarbeiros_should_return_barbers_with_id_and_name(): void
    {
        $expectedBarbersData = new Collection([
            (object)['id' => 1, 'name' => 'Barbeiro Um'],
            (object)['id' => 2, 'name' => 'Barbeiro Dois'],
        ]);

        $this->userModelMock
            ->shouldReceive('where')
            ->once()
            ->with('level', 'Barber')
            ->andReturnSelf();

        $this->userModelMock
            ->shouldReceive('select')
            ->once()
            ->with('id', 'name')
            ->andReturnSelf();

        $this->userModelMock
            ->shouldReceive('get')
            ->once()
            ->andReturn($expectedBarbersData);

        $response = $this->userController->getBarbeiros();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());


        $responseData = $response->getData(true);
        $this->assertArrayHasKey('barbeiros', $responseData);
        $expectedArrayForAssertion = $expectedBarbersData->map(function ($item) {
            return (array)$item;
        })->toArray();
        $this->assertEquals($expectedArrayForAssertion, $responseData['barbeiros']);
    }

    /**
     * Testa se getBarbeiros retorna uma lista vazia quando não há barbeiros.
     * @test
     */
    public function getBarbeiros_should_return_empty_list_when_no_barbers_exist(): void
    {
        $emptyCollection = new Collection([]);

        $this->userModelMock->shouldReceive('where')->once()->with('level', 'Barber')->andReturnSelf();
        $this->userModelMock->shouldReceive('select')->once()->with('id', 'name')->andReturnSelf();
        $this->userModelMock->shouldReceive('get')->once()->andReturn($emptyCollection);

        $response = $this->userController->getBarbeiros();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertArrayHasKey('barbeiros', $responseData);
        $this->assertEmpty($responseData['barbeiros']);
    }
}