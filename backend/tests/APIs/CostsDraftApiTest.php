<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CostsDraft;

class CostsDraftApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_costs_draft()
    {
        $costsDraft = CostsDraft::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/costs_drafts', $costsDraft
        );

        $this->assertApiResponse($costsDraft);
    }

    /**
     * @test
     */
    public function test_read_costs_draft()
    {
        $costsDraft = CostsDraft::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/costs_drafts/'.$costsDraft->id
        );

        $this->assertApiResponse($costsDraft->toArray());
    }

    /**
     * @test
     */
    public function test_update_costs_draft()
    {
        $costsDraft = CostsDraft::factory()->create();
        $editedCostsDraft = CostsDraft::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/costs_drafts/'.$costsDraft->id,
            $editedCostsDraft
        );

        $this->assertApiResponse($editedCostsDraft);
    }

    /**
     * @test
     */
    public function test_delete_costs_draft()
    {
        $costsDraft = CostsDraft::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/costs_drafts/'.$costsDraft->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/costs_drafts/'.$costsDraft->id
        );

        $this->response->assertStatus(404);
    }
}
