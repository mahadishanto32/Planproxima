<?php namespace Tests\Repositories;

use App\Models\Production_product_name;
use App\Repositories\Production_product_nameRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class Production_product_nameRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var Production_product_nameRepository
     */
    protected $productionProductNameRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->productionProductNameRepo = \App::make(Production_product_nameRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_production_product_name()
    {
        $productionProductName = Production_product_name::factory()->make()->toArray();

        $createdProduction_product_name = $this->productionProductNameRepo->create($productionProductName);

        $createdProduction_product_name = $createdProduction_product_name->toArray();
        $this->assertArrayHasKey('id', $createdProduction_product_name);
        $this->assertNotNull($createdProduction_product_name['id'], 'Created Production_product_name must have id specified');
        $this->assertNotNull(Production_product_name::find($createdProduction_product_name['id']), 'Production_product_name with given id must be in DB');
        $this->assertModelData($productionProductName, $createdProduction_product_name);
    }

    /**
     * @test read
     */
    public function test_read_production_product_name()
    {
        $productionProductName = Production_product_name::factory()->create();

        $dbProduction_product_name = $this->productionProductNameRepo->find($productionProductName->id);

        $dbProduction_product_name = $dbProduction_product_name->toArray();
        $this->assertModelData($productionProductName->toArray(), $dbProduction_product_name);
    }

    /**
     * @test update
     */
    public function test_update_production_product_name()
    {
        $productionProductName = Production_product_name::factory()->create();
        $fakeProduction_product_name = Production_product_name::factory()->make()->toArray();

        $updatedProduction_product_name = $this->productionProductNameRepo->update($fakeProduction_product_name, $productionProductName->id);

        $this->assertModelData($fakeProduction_product_name, $updatedProduction_product_name->toArray());
        $dbProduction_product_name = $this->productionProductNameRepo->find($productionProductName->id);
        $this->assertModelData($fakeProduction_product_name, $dbProduction_product_name->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_production_product_name()
    {
        $productionProductName = Production_product_name::factory()->create();

        $resp = $this->productionProductNameRepo->delete($productionProductName->id);

        $this->assertTrue($resp);
        $this->assertNull(Production_product_name::find($productionProductName->id), 'Production_product_name should not exist in DB');
    }
}
