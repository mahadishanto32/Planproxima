<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MOS;

class MOSApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_m_o_s()
    {
        $mOS = MOS::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/m_o_s', $mOS
        );

        $this->assertApiResponse($mOS);
    }

    /**
     * @test
     */
    public function test_read_m_o_s()
    {
        $mOS = MOS::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/m_o_s/'.$mOS->id
        );

        $this->assertApiResponse($mOS->toArray());
    }

    /**
     * @test
     */
    public function test_update_m_o_s()
    {
        $mOS = MOS::factory()->create();
        $editedMOS = MOS::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/m_o_s/'.$mOS->id,
            $editedMOS
        );

        $this->assertApiResponse($editedMOS);
    }

    /**
     * @test
     */
    public function test_delete_m_o_s()
    {
        $mOS = MOS::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/m_o_s/'.$mOS->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/m_o_s/'.$mOS->id
        );

        $this->response->assertStatus(404);
    }
}
