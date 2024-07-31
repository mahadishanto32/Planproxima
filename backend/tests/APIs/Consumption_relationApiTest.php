<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Consumption_relation;

class Consumption_relationApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_consumption_relation()
    {
        $consumptionRelation = Consumption_relation::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/consumption_relations', $consumptionRelation
        );

        $this->assertApiResponse($consumptionRelation);
    }

    /**
     * @test
     */
    public function test_read_consumption_relation()
    {
        $consumptionRelation = Consumption_relation::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/consumption_relations/'.$consumptionRelation->id
        );

        $this->assertApiResponse($consumptionRelation->toArray());
    }

    /**
     * @test
     */
    public function test_update_consumption_relation()
    {
        $consumptionRelation = Consumption_relation::factory()->create();
        $editedConsumption_relation = Consumption_relation::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/consumption_relations/'.$consumptionRelation->id,
            $editedConsumption_relation
        );

        $this->assertApiResponse($editedConsumption_relation);
    }

    /**
     * @test
     */
    public function test_delete_consumption_relation()
    {
        $consumptionRelation = Consumption_relation::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/consumption_relations/'.$consumptionRelation->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/consumption_relations/'.$consumptionRelation->id
        );

        $this->response->assertStatus(404);
    }
}
