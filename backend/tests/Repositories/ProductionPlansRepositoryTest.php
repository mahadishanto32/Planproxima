<?php namespace Tests\Repositories;

use App\Models\ProductionPlans;
use App\Repositories\ProductionPlansRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ProductionPlansRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ProductionPlansRepository
     */
    protected $productionPlansRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->productionPlansRepo = \App::make(ProductionPlansRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_production_plans()
    {
        $productionPlans = ProductionPlans::factory()->make()->toArray();

        $createdProductionPlans = $this->productionPlansRepo->create($productionPlans);

        $createdProductionPlans = $createdProductionPlans->toArray();
        $this->assertArrayHasKey('id', $createdProductionPlans);
        $this->assertNotNull($createdProductionPlans['id'], 'Created ProductionPlans must have id specified');
        $this->assertNotNull(ProductionPlans::find($createdProductionPlans['id']), 'ProductionPlans with given id must be in DB');
        $this->assertModelData($productionPlans, $createdProductionPlans);
    }

    /**
     * @test read
     */
    public function test_read_production_plans()
    {
        $productionPlans = ProductionPlans::factory()->create();

        $dbProductionPlans = $this->productionPlansRepo->find($productionPlans->id);

        $dbProductionPlans = $dbProductionPlans->toArray();
        $this->assertModelData($productionPlans->toArray(), $dbProductionPlans);
    }

    /**
     * @test update
     */
    public function test_update_production_plans()
    {
        $productionPlans = ProductionPlans::factory()->create();
        $fakeProductionPlans = ProductionPlans::factory()->make()->toArray();

        $updatedProductionPlans = $this->productionPlansRepo->update($fakeProductionPlans, $productionPlans->id);

        $this->assertModelData($fakeProductionPlans, $updatedProductionPlans->toArray());
        $dbProductionPlans = $this->productionPlansRepo->find($productionPlans->id);
        $this->assertModelData($fakeProductionPlans, $dbProductionPlans->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_production_plans()
    {
        $productionPlans = ProductionPlans::factory()->create();

        $resp = $this->productionPlansRepo->delete($productionPlans->id);

        $this->assertTrue($resp);
        $this->assertNull(ProductionPlans::find($productionPlans->id), 'ProductionPlans should not exist in DB');
    }
}
