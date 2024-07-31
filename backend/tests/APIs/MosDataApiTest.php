<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MosData;

class MosDataApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_mos_data()
    {
        $mosData = MosData::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/mos_datas', $mosData
        );

        $this->assertApiResponse($mosData);
    }

    /**
     * @test
     */
    public function test_read_mos_data()
    {
        $mosData = MosData::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/mos_datas/'.$mosData->id
        );

        $this->assertApiResponse($mosData->toArray());
    }

    /**
     * @test
     */
    public function test_update_mos_data()
    {
        $mosData = MosData::factory()->create();
        $editedMosData = MosData::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/mos_datas/'.$mosData->id,
            $editedMosData
        );

        $this->assertApiResponse($editedMosData);
    }

    /**
     * @test
     */
    public function test_delete_mos_data()
    {
        $mosData = MosData::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/mos_datas/'.$mosData->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/mos_datas/'.$mosData->id
        );

        $this->response->assertStatus(404);
    }
}
