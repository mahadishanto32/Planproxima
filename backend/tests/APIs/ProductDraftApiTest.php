<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ProductDraft;

class ProductDraftApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_product_draft()
    {
        $productDraft = ProductDraft::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/product_drafts', $productDraft
        );

        $this->assertApiResponse($productDraft);
    }

    /**
     * @test
     */
    public function test_read_product_draft()
    {
        $productDraft = ProductDraft::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/product_drafts/'.$productDraft->id
        );

        $this->assertApiResponse($productDraft->toArray());
    }

    /**
     * @test
     */
    public function test_update_product_draft()
    {
        $productDraft = ProductDraft::factory()->create();
        $editedProductDraft = ProductDraft::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/product_drafts/'.$productDraft->id,
            $editedProductDraft
        );

        $this->assertApiResponse($editedProductDraft);
    }

    /**
     * @test
     */
    public function test_delete_product_draft()
    {
        $productDraft = ProductDraft::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/product_drafts/'.$productDraft->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/product_drafts/'.$productDraft->id
        );

        $this->response->assertStatus(404);
    }
}
