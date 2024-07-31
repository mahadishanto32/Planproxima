<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ProductionFeedback;

class ProductionFeedbackApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_production_feedback()
    {
        $productionFeedback = ProductionFeedback::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/production_feedbacks', $productionFeedback
        );

        $this->assertApiResponse($productionFeedback);
    }

    /**
     * @test
     */
    public function test_read_production_feedback()
    {
        $productionFeedback = ProductionFeedback::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/production_feedbacks/'.$productionFeedback->id
        );

        $this->assertApiResponse($productionFeedback->toArray());
    }

    /**
     * @test
     */
    public function test_update_production_feedback()
    {
        $productionFeedback = ProductionFeedback::factory()->create();
        $editedProductionFeedback = ProductionFeedback::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/production_feedbacks/'.$productionFeedback->id,
            $editedProductionFeedback
        );

        $this->assertApiResponse($editedProductionFeedback);
    }

    /**
     * @test
     */
    public function test_delete_production_feedback()
    {
        $productionFeedback = ProductionFeedback::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/production_feedbacks/'.$productionFeedback->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/production_feedbacks/'.$productionFeedback->id
        );

        $this->response->assertStatus(404);
    }
}
