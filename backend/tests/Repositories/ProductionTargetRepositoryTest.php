<?php namespace Tests\Repositories;

use App\Models\ProductionTarget;
use App\Repositories\ProductionTargetRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ProductionTargetRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ProductionTargetRepository
     */
    protected $productionTargetRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->productionTargetRepo = \App::make(ProductionTargetRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_production_target()
    {
        $productionTarget = ProductionTarget::factory()->make()->toArray();

        $createdProductionTarget = $this->productionTargetRepo->create($productionTarget);

        $createdProductionTarget = $createdProductionTarget->toArray();
        $this->assertArrayHasKey('id', $createdProductionTarget);
        $this->assertNotNull($createdProductionTarget['id'], 'Created ProductionTarget must have id specified');
        $this->assertNotNull(ProductionTarget::find($createdProductionTarget['id']), 'ProductionTarget with given id must be in DB');
        $this->assertModelData($productionTarget, $createdProductionTarget);
    }

    /**
     * @test read
     */
    public function test_read_production_target()
    {
        $productionTarget = ProductionTarget::factory()->create();

        $dbProductionTarget = $this->productionTargetRepo->find($productionTarget->id);

        $dbProductionTarget = $dbProductionTarget->toArray();
        $this->assertModelData($productionTarget->toArray(), $dbProductionTarget);
    }

    /**
     * @test update
     */
    public function test_update_production_target()
    {
        $productionTarget = ProductionTarget::factory()->create();
        $fakeProductionTarget = ProductionTarget::factory()->make()->toArray();

        $updatedProductionTarget = $this->productionTargetRepo->update($fakeProductionTarget, $productionTarget->id);

        $this->assertModelData($fakeProductionTarget, $updatedProductionTarget->toArray());
        $dbProductionTarget = $this->productionTargetRepo->find($productionTarget->id);
        $this->assertModelData($fakeProductionTarget, $dbProductionTarget->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_production_target()
    {
        $productionTarget = ProductionTarget::factory()->create();

        $resp = $this->productionTargetRepo->delete($productionTarget->id);

        $this->assertTrue($resp);
        $this->assertNull(ProductionTarget::find($productionTarget->id), 'ProductionTarget should not exist in DB');
    }
}
