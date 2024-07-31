<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CostSummaryGroup;

class CostSummaryGroupApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_cost_summary_group()
    {
        $costSummaryGroup = CostSummaryGroup::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/cost_summary_groups', $costSummaryGroup
        );

        $this->assertApiResponse($costSummaryGroup);
    }

    /**
     * @test
     */
    public function test_read_cost_summary_group()
    {
        $costSummaryGroup = CostSummaryGroup::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/cost_summary_groups/'.$costSummaryGroup->id
        );

        $this->assertApiResponse($costSummaryGroup->toArray());
    }

    /**
     * @test
     */
    public function test_update_cost_summary_group()
    {
        $costSummaryGroup = CostSummaryGroup::factory()->create();
        $editedCostSummaryGroup = CostSummaryGroup::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/cost_summary_groups/'.$costSummaryGroup->id,
            $editedCostSummaryGroup
        );

        $this->assertApiResponse($editedCostSummaryGroup);
    }

    /**
     * @test
     */
    public function test_delete_cost_summary_group()
    {
        $costSummaryGroup = CostSummaryGroup::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/cost_summary_groups/'.$costSummaryGroup->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/cost_summary_groups/'.$costSummaryGroup->id
        );

        $this->response->assertStatus(404);
    }
}
