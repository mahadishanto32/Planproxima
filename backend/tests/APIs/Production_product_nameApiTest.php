<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Production_product_name;

class Production_product_nameApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_production_product_name()
    {
        $productionProductName = Production_product_name::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/production_product_names', $productionProductName
        );

        $this->assertApiResponse($productionProductName);
    }

    /**
     * @test
     */
    public function test_read_production_product_name()
    {
        $productionProductName = Production_product_name::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/production_product_names/'.$productionProductName->id
        );

        $this->assertApiResponse($productionProductName->toArray());
    }

    /**
     * @test
     */
    public function test_update_production_product_name()
    {
        $productionProductName = Production_product_name::factory()->create();
        $editedProduction_product_name = Production_product_name::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/production_product_names/'.$productionProductName->id,
            $editedProduction_product_name
        );

        $this->assertApiResponse($editedProduction_product_name);
    }

    /**
     * @test
     */
    public function test_delete_production_product_name()
    {
        $productionProductName = Production_product_name::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/production_product_names/'.$productionProductName->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/production_product_names/'.$productionProductName->id
        );

        $this->response->assertStatus(404);
    }
}
