<?php namespace Tests\Repositories;

use App\Models\ProductionFeedback;
use App\Repositories\ProductionFeedbackRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ProductionFeedbackRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ProductionFeedbackRepository
     */
    protected $productionFeedbackRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->productionFeedbackRepo = \App::make(ProductionFeedbackRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_production_feedback()
    {
        $productionFeedback = ProductionFeedback::factory()->make()->toArray();

        $createdProductionFeedback = $this->productionFeedbackRepo->create($productionFeedback);

        $createdProductionFeedback = $createdProductionFeedback->toArray();
        $this->assertArrayHasKey('id', $createdProductionFeedback);
        $this->assertNotNull($createdProductionFeedback['id'], 'Created ProductionFeedback must have id specified');
        $this->assertNotNull(ProductionFeedback::find($createdProductionFeedback['id']), 'ProductionFeedback with given id must be in DB');
        $this->assertModelData($productionFeedback, $createdProductionFeedback);
    }

    /**
     * @test read
     */
    public function test_read_production_feedback()
    {
        $productionFeedback = ProductionFeedback::factory()->create();

        $dbProductionFeedback = $this->productionFeedbackRepo->find($productionFeedback->id);

        $dbProductionFeedback = $dbProductionFeedback->toArray();
        $this->assertModelData($productionFeedback->toArray(), $dbProductionFeedback);
    }

    /**
     * @test update
     */
    public function test_update_production_feedback()
    {
        $productionFeedback = ProductionFeedback::factory()->create();
        $fakeProductionFeedback = ProductionFeedback::factory()->make()->toArray();

        $updatedProductionFeedback = $this->productionFeedbackRepo->update($fakeProductionFeedback, $productionFeedback->id);

        $this->assertModelData($fakeProductionFeedback, $updatedProductionFeedback->toArray());
        $dbProductionFeedback = $this->productionFeedbackRepo->find($productionFeedback->id);
        $this->assertModelData($fakeProductionFeedback, $dbProductionFeedback->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_production_feedback()
    {
        $productionFeedback = ProductionFeedback::factory()->create();

        $resp = $this->productionFeedbackRepo->delete($productionFeedback->id);

        $this->assertTrue($resp);
        $this->assertNull(ProductionFeedback::find($productionFeedback->id), 'ProductionFeedback should not exist in DB');
    }
}
