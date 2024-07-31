<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ProductionTarget;

class ProductionTargetApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_production_target()
    {
        $productionTarget = ProductionTarget::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/production_targets', $productionTarget
        );

        $this->assertApiResponse($productionTarget);
    }

    /**
     * @test
     */
    public function test_read_production_target()
    {
        $productionTarget = ProductionTarget::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/production_targets/'.$productionTarget->id
        );

        $this->assertApiResponse($productionTarget->toArray());
    }

    /**
     * @test
     */
    public function test_update_production_target()
    {
        $productionTarget = ProductionTarget::factory()->create();
        $editedProductionTarget = ProductionTarget::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/production_targets/'.$productionTarget->id,
            $editedProductionTarget
        );

        $this->assertApiResponse($editedProductionTarget);
    }

    /**
     * @test
     */
    public function test_delete_production_target()
    {
        $productionTarget = ProductionTarget::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/production_targets/'.$productionTarget->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/production_targets/'.$productionTarget->id
        );

        $this->response->assertStatus(404);
    }
}
