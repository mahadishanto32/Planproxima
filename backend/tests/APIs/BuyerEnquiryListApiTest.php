<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\BuyerEnquiryList;

class BuyerEnquiryListApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_buyer_enquiry_list()
    {
        $buyerEnquiryList = BuyerEnquiryList::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/buyer_enquiry_lists', $buyerEnquiryList
        );

        $this->assertApiResponse($buyerEnquiryList);
    }

    /**
     * @test
     */
    public function test_read_buyer_enquiry_list()
    {
        $buyerEnquiryList = BuyerEnquiryList::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/buyer_enquiry_lists/'.$buyerEnquiryList->id
        );

        $this->assertApiResponse($buyerEnquiryList->toArray());
    }

    /**
     * @test
     */
    public function test_update_buyer_enquiry_list()
    {
        $buyerEnquiryList = BuyerEnquiryList::factory()->create();
        $editedBuyerEnquiryList = BuyerEnquiryList::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/buyer_enquiry_lists/'.$buyerEnquiryList->id,
            $editedBuyerEnquiryList
        );

        $this->assertApiResponse($editedBuyerEnquiryList);
    }

    /**
     * @test
     */
    public function test_delete_buyer_enquiry_list()
    {
        $buyerEnquiryList = BuyerEnquiryList::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/buyer_enquiry_lists/'.$buyerEnquiryList->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/buyer_enquiry_lists/'.$buyerEnquiryList->id
        );

        $this->response->assertStatus(404);
    }
}
