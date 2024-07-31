<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MosDataLog;

class MosDataLogApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_mos_data_log()
    {
        $mosDataLog = MosDataLog::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/mos_data_logs', $mosDataLog
        );

        $this->assertApiResponse($mosDataLog);
    }

    /**
     * @test
     */
    public function test_read_mos_data_log()
    {
        $mosDataLog = MosDataLog::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/mos_data_logs/'.$mosDataLog->id
        );

        $this->assertApiResponse($mosDataLog->toArray());
    }

    /**
     * @test
     */
    public function test_update_mos_data_log()
    {
        $mosDataLog = MosDataLog::factory()->create();
        $editedMosDataLog = MosDataLog::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/mos_data_logs/'.$mosDataLog->id,
            $editedMosDataLog
        );

        $this->assertApiResponse($editedMosDataLog);
    }

    /**
     * @test
     */
    public function test_delete_mos_data_log()
    {
        $mosDataLog = MosDataLog::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/mos_data_logs/'.$mosDataLog->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/mos_data_logs/'.$mosDataLog->id
        );

        $this->response->assertStatus(404);
    }
}
