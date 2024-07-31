<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\FactoryCapacity;

class FactoryCapacityApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_factory_capacity()
    {
        $factoryCapacity = FactoryCapacity::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/factory_capacities', $factoryCapacity
        );

        $this->assertApiResponse($factoryCapacity);
    }

    /**
     * @test
     */
    public function test_read_factory_capacity()
    {
        $factoryCapacity = FactoryCapacity::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/factory_capacities/'.$factoryCapacity->id
        );

        $this->assertApiResponse($factoryCapacity->toArray());
    }

    /**
     * @test
     */
    public function test_update_factory_capacity()
    {
        $factoryCapacity = FactoryCapacity::factory()->create();
        $editedFactoryCapacity = FactoryCapacity::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/factory_capacities/'.$factoryCapacity->id,
            $editedFactoryCapacity
        );

        $this->assertApiResponse($editedFactoryCapacity);
    }

    /**
     * @test
     */
    public function test_delete_factory_capacity()
    {
        $factoryCapacity = FactoryCapacity::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/factory_capacities/'.$factoryCapacity->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/factory_capacities/'.$factoryCapacity->id
        );

        $this->response->assertStatus(404);
    }
}
