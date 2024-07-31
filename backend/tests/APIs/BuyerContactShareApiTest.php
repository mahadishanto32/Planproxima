<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\BuyerContactShare;

class BuyerContactShareApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_buyer_contact_share()
    {
        $buyerContactShare = BuyerContactShare::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/buyer_contact_shares', $buyerContactShare
        );

        $this->assertApiResponse($buyerContactShare);
    }

    /**
     * @test
     */
    public function test_read_buyer_contact_share()
    {
        $buyerContactShare = BuyerContactShare::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/buyer_contact_shares/'.$buyerContactShare->id
        );

        $this->assertApiResponse($buyerContactShare->toArray());
    }

    /**
     * @test
     */
    public function test_update_buyer_contact_share()
    {
        $buyerContactShare = BuyerContactShare::factory()->create();
        $editedBuyerContactShare = BuyerContactShare::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/buyer_contact_shares/'.$buyerContactShare->id,
            $editedBuyerContactShare
        );

        $this->assertApiResponse($editedBuyerContactShare);
    }

    /**
     * @test
     */
    public function test_delete_buyer_contact_share()
    {
        $buyerContactShare = BuyerContactShare::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/buyer_contact_shares/'.$buyerContactShare->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/buyer_contact_shares/'.$buyerContactShare->id
        );

        $this->response->assertStatus(404);
    }
}
