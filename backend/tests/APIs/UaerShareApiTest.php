<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\UaerShare;

class UaerShareApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_uaer_share()
    {
        $uaerShare = UaerShare::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/uaer_shares', $uaerShare
        );

        $this->assertApiResponse($uaerShare);
    }

    /**
     * @test
     */
    public function test_read_uaer_share()
    {
        $uaerShare = UaerShare::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/uaer_shares/'.$uaerShare->id
        );

        $this->assertApiResponse($uaerShare->toArray());
    }

    /**
     * @test
     */
    public function test_update_uaer_share()
    {
        $uaerShare = UaerShare::factory()->create();
        $editedUaerShare = UaerShare::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/uaer_shares/'.$uaerShare->id,
            $editedUaerShare
        );

        $this->assertApiResponse($editedUaerShare);
    }

    /**
     * @test
     */
    public function test_delete_uaer_share()
    {
        $uaerShare = UaerShare::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/uaer_shares/'.$uaerShare->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/uaer_shares/'.$uaerShare->id
        );

        $this->response->assertStatus(404);
    }
}
