<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\KPI;

class KPIApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_k_p_i()
    {
        $kPI = KPI::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/k_p_i_s', $kPI
        );

        $this->assertApiResponse($kPI);
    }

    /**
     * @test
     */
    public function test_read_k_p_i()
    {
        $kPI = KPI::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/k_p_i_s/'.$kPI->id
        );

        $this->assertApiResponse($kPI->toArray());
    }

    /**
     * @test
     */
    public function test_update_k_p_i()
    {
        $kPI = KPI::factory()->create();
        $editedKPI = KPI::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/k_p_i_s/'.$kPI->id,
            $editedKPI
        );

        $this->assertApiResponse($editedKPI);
    }

    /**
     * @test
     */
    public function test_delete_k_p_i()
    {
        $kPI = KPI::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/k_p_i_s/'.$kPI->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/k_p_i_s/'.$kPI->id
        );

        $this->response->assertStatus(404);
    }
}
