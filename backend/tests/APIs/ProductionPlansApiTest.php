<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ProductionPlans;

class ProductionPlansApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_production_plans()
    {
        $productionPlans = ProductionPlans::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/production_plans', $productionPlans
        );

        $this->assertApiResponse($productionPlans);
    }

    /**
     * @test
     */
    public function test_read_production_plans()
    {
        $productionPlans = ProductionPlans::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/production_plans/'.$productionPlans->id
        );

        $this->assertApiResponse($productionPlans->toArray());
    }

    /**
     * @test
     */
    public function test_update_production_plans()
    {
        $productionPlans = ProductionPlans::factory()->create();
        $editedProductionPlans = ProductionPlans::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/production_plans/'.$productionPlans->id,
            $editedProductionPlans
        );

        $this->assertApiResponse($editedProductionPlans);
    }

    /**
     * @test
     */
    public function test_delete_production_plans()
    {
        $productionPlans = ProductionPlans::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/production_plans/'.$productionPlans->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/production_plans/'.$productionPlans->id
        );

        $this->response->assertStatus(404);
    }
}
