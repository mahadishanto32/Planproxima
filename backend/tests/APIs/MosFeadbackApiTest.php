<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MosFeadback;

class MosFeadbackApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_mos_feadback()
    {
        $mosFeadback = MosFeadback::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/mos_feadbacks', $mosFeadback
        );

        $this->assertApiResponse($mosFeadback);
    }

    /**
     * @test
     */
    public function test_read_mos_feadback()
    {
        $mosFeadback = MosFeadback::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/mos_feadbacks/'.$mosFeadback->id
        );

        $this->assertApiResponse($mosFeadback->toArray());
    }

    /**
     * @test
     */
    public function test_update_mos_feadback()
    {
        $mosFeadback = MosFeadback::factory()->create();
        $editedMosFeadback = MosFeadback::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/mos_feadbacks/'.$mosFeadback->id,
            $editedMosFeadback
        );

        $this->assertApiResponse($editedMosFeadback);
    }

    /**
     * @test
     */
    public function test_delete_mos_feadback()
    {
        $mosFeadback = MosFeadback::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/mos_feadbacks/'.$mosFeadback->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/mos_feadbacks/'.$mosFeadback->id
        );

        $this->response->assertStatus(404);
    }
}
