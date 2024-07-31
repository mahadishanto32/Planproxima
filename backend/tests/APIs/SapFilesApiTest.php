<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\SapFiles;

class SapFilesApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_sap_files()
    {
        $sapFiles = SapFiles::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/sap_files', $sapFiles
        );

        $this->assertApiResponse($sapFiles);
    }

    /**
     * @test
     */
    public function test_read_sap_files()
    {
        $sapFiles = SapFiles::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/sap_files/'.$sapFiles->id
        );

        $this->assertApiResponse($sapFiles->toArray());
    }

    /**
     * @test
     */
    public function test_update_sap_files()
    {
        $sapFiles = SapFiles::factory()->create();
        $editedSapFiles = SapFiles::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/sap_files/'.$sapFiles->id,
            $editedSapFiles
        );

        $this->assertApiResponse($editedSapFiles);
    }

    /**
     * @test
     */
    public function test_delete_sap_files()
    {
        $sapFiles = SapFiles::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/sap_files/'.$sapFiles->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/sap_files/'.$sapFiles->id
        );

        $this->response->assertStatus(404);
    }
}
