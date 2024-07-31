<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\buyerEnquiryColumn;

class buyerEnquiryColumnApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_buyer_enquiry_column()
    {
        $buyerEnquiryColumn = buyerEnquiryColumn::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/buyer_enquiry_columns', $buyerEnquiryColumn
        );

        $this->assertApiResponse($buyerEnquiryColumn);
    }

    /**
     * @test
     */
    public function test_read_buyer_enquiry_column()
    {
        $buyerEnquiryColumn = buyerEnquiryColumn::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/buyer_enquiry_columns/'.$buyerEnquiryColumn->id
        );

        $this->assertApiResponse($buyerEnquiryColumn->toArray());
    }

    /**
     * @test
     */
    public function test_update_buyer_enquiry_column()
    {
        $buyerEnquiryColumn = buyerEnquiryColumn::factory()->create();
        $editedbuyerEnquiryColumn = buyerEnquiryColumn::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/buyer_enquiry_columns/'.$buyerEnquiryColumn->id,
            $editedbuyerEnquiryColumn
        );

        $this->assertApiResponse($editedbuyerEnquiryColumn);
    }

    /**
     * @test
     */
    public function test_delete_buyer_enquiry_column()
    {
        $buyerEnquiryColumn = buyerEnquiryColumn::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/buyer_enquiry_columns/'.$buyerEnquiryColumn->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/buyer_enquiry_columns/'.$buyerEnquiryColumn->id
        );

        $this->response->assertStatus(404);
    }
}
