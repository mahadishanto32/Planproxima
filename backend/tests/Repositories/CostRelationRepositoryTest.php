<?php namespace Tests\Repositories;

use App\Models\CostRelation;
use App\Repositories\CostRelationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CostRelationRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CostRelationRepository
     */
    protected $costRelationRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->costRelationRepo = \App::make(CostRelationRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_cost_relation()
    {
        $costRelation = CostRelation::factory()->make()->toArray();

        $createdCostRelation = $this->costRelationRepo->create($costRelation);

        $createdCostRelation = $createdCostRelation->toArray();
        $this->assertArrayHasKey('id', $createdCostRelation);
        $this->assertNotNull($createdCostRelation['id'], 'Created CostRelation must have id specified');
        $this->assertNotNull(CostRelation::find($createdCostRelation['id']), 'CostRelation with given id must be in DB');
        $this->assertModelData($costRelation, $createdCostRelation);
    }

    /**
     * @test read
     */
    public function test_read_cost_relation()
    {
        $costRelation = CostRelation::factory()->create();

        $dbCostRelation = $this->costRelationRepo->find($costRelation->id);

        $dbCostRelation = $dbCostRelation->toArray();
        $this->assertModelData($costRelation->toArray(), $dbCostRelation);
    }

    /**
     * @test update
     */
    public function test_update_cost_relation()
    {
        $costRelation = CostRelation::factory()->create();
        $fakeCostRelation = CostRelation::factory()->make()->toArray();

        $updatedCostRelation = $this->costRelationRepo->update($fakeCostRelation, $costRelation->id);

        $this->assertModelData($fakeCostRelation, $updatedCostRelation->toArray());
        $dbCostRelation = $this->costRelationRepo->find($costRelation->id);
        $this->assertModelData($fakeCostRelation, $dbCostRelation->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_cost_relation()
    {
        $costRelation = CostRelation::factory()->create();

        $resp = $this->costRelationRepo->delete($costRelation->id);

        $this->assertTrue($resp);
        $this->assertNull(CostRelation::find($costRelation->id), 'CostRelation should not exist in DB');
    }
}
