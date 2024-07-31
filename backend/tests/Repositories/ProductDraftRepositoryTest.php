<?php namespace Tests\Repositories;

use App\Models\ProductDraft;
use App\Repositories\ProductDraftRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ProductDraftRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ProductDraftRepository
     */
    protected $productDraftRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->productDraftRepo = \App::make(ProductDraftRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_product_draft()
    {
        $productDraft = ProductDraft::factory()->make()->toArray();

        $createdProductDraft = $this->productDraftRepo->create($productDraft);

        $createdProductDraft = $createdProductDraft->toArray();
        $this->assertArrayHasKey('id', $createdProductDraft);
        $this->assertNotNull($createdProductDraft['id'], 'Created ProductDraft must have id specified');
        $this->assertNotNull(ProductDraft::find($createdProductDraft['id']), 'ProductDraft with given id must be in DB');
        $this->assertModelData($productDraft, $createdProductDraft);
    }

    /**
     * @test read
     */
    public function test_read_product_draft()
    {
        $productDraft = ProductDraft::factory()->create();

        $dbProductDraft = $this->productDraftRepo->find($productDraft->id);

        $dbProductDraft = $dbProductDraft->toArray();
        $this->assertModelData($productDraft->toArray(), $dbProductDraft);
    }

    /**
     * @test update
     */
    public function test_update_product_draft()
    {
        $productDraft = ProductDraft::factory()->create();
        $fakeProductDraft = ProductDraft::factory()->make()->toArray();

        $updatedProductDraft = $this->productDraftRepo->update($fakeProductDraft, $productDraft->id);

        $this->assertModelData($fakeProductDraft, $updatedProductDraft->toArray());
        $dbProductDraft = $this->productDraftRepo->find($productDraft->id);
        $this->assertModelData($fakeProductDraft, $dbProductDraft->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_product_draft()
    {
        $productDraft = ProductDraft::factory()->create();

        $resp = $this->productDraftRepo->delete($productDraft->id);

        $this->assertTrue($resp);
        $this->assertNull(ProductDraft::find($productDraft->id), 'ProductDraft should not exist in DB');
    }
}
