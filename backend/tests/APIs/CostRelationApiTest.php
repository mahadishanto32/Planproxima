<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CostRelation;

class CostRelationApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_cost_relation()
    {
        $costRelation = CostRelation::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/cost_relations', $costRelation
        );

        $this->assertApiResponse($costRelation);
    }

    /**
     * @test
     */
    public function test_read_cost_relation()
    {
        $costRelation = CostRelation::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/cost_relations/'.$costRelation->id
        );

        $this->assertApiResponse($costRelation->toArray());
    }

    /**
     * @test
     */
    public function test_update_cost_relation()
    {
        $costRelation = CostRelation::factory()->create();
        $editedCostRelation = CostRelation::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/cost_relations/'.$costRelation->id,
            $editedCostRelation
        );

        $this->assertApiResponse($editedCostRelation);
    }

    /**
     * @test
     */
    public function test_delete_cost_relation()
    {
        $costRelation = CostRelation::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/cost_relations/'.$costRelation->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/cost_relations/'.$costRelation->id
        );

        $this->response->assertStatus(404);
    }
}
