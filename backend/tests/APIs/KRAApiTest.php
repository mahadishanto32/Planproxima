<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\KRA;

class KRAApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_k_r_a()
    {
        $kRA = KRA::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/k_r_a_s', $kRA
        );

        $this->assertApiResponse($kRA);
    }

    /**
     * @test
     */
    public function test_read_k_r_a()
    {
        $kRA = KRA::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/k_r_a_s/'.$kRA->id
        );

        $this->assertApiResponse($kRA->toArray());
    }

    /**
     * @test
     */
    public function test_update_k_r_a()
    {
        $kRA = KRA::factory()->create();
        $editedKRA = KRA::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/k_r_a_s/'.$kRA->id,
            $editedKRA
        );

        $this->assertApiResponse($editedKRA);
    }

    /**
     * @test
     */
    public function test_delete_k_r_a()
    {
        $kRA = KRA::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/k_r_a_s/'.$kRA->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/k_r_a_s/'.$kRA->id
        );

        $this->response->assertStatus(404);
    }
}
