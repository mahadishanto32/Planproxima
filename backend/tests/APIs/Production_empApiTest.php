<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Production_emp;

class Production_empApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_production_emp()
    {
        $productionEmp = Production_emp::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/production_emps', $productionEmp
        );

        $this->assertApiResponse($productionEmp);
    }

    /**
     * @test
     */
    public function test_read_production_emp()
    {
        $productionEmp = Production_emp::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/production_emps/'.$productionEmp->id
        );

        $this->assertApiResponse($productionEmp->toArray());
    }

    /**
     * @test
     */
    public function test_update_production_emp()
    {
        $productionEmp = Production_emp::factory()->create();
        $editedProduction_emp = Production_emp::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/production_emps/'.$productionEmp->id,
            $editedProduction_emp
        );

        $this->assertApiResponse($editedProduction_emp);
    }

    /**
     * @test
     */
    public function test_delete_production_emp()
    {
        $productionEmp = Production_emp::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/production_emps/'.$productionEmp->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/production_emps/'.$productionEmp->id
        );

        $this->response->assertStatus(404);
    }
}
