<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\FactoryStandard;

class FactoryStandardApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_factory_standard()
    {
        $factoryStandard = FactoryStandard::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/factory_standards', $factoryStandard
        );

        $this->assertApiResponse($factoryStandard);
    }

    /**
     * @test
     */
    public function test_read_factory_standard()
    {
        $factoryStandard = FactoryStandard::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/factory_standards/'.$factoryStandard->id
        );

        $this->assertApiResponse($factoryStandard->toArray());
    }

    /**
     * @test
     */
    public function test_update_factory_standard()
    {
        $factoryStandard = FactoryStandard::factory()->create();
        $editedFactoryStandard = FactoryStandard::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/factory_standards/'.$factoryStandard->id,
            $editedFactoryStandard
        );

        $this->assertApiResponse($editedFactoryStandard);
    }

    /**
     * @test
     */
    public function test_delete_factory_standard()
    {
        $factoryStandard = FactoryStandard::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/factory_standards/'.$factoryStandard->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/factory_standards/'.$factoryStandard->id
        );

        $this->response->assertStatus(404);
    }
}
