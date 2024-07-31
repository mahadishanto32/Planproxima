<?php namespace Tests\Repositories;

use App\Models\CostsDraft;
use App\Repositories\CostsDraftRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CostsDraftRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CostsDraftRepository
     */
    protected $costsDraftRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->costsDraftRepo = \App::make(CostsDraftRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_costs_draft()
    {
        $costsDraft = CostsDraft::factory()->make()->toArray();

        $createdCostsDraft = $this->costsDraftRepo->create($costsDraft);

        $createdCostsDraft = $createdCostsDraft->toArray();
        $this->assertArrayHasKey('id', $createdCostsDraft);
        $this->assertNotNull($createdCostsDraft['id'], 'Created CostsDraft must have id specified');
        $this->assertNotNull(CostsDraft::find($createdCostsDraft['id']), 'CostsDraft with given id must be in DB');
        $this->assertModelData($costsDraft, $createdCostsDraft);
    }

    /**
     * @test read
     */
    public function test_read_costs_draft()
    {
        $costsDraft = CostsDraft::factory()->create();

        $dbCostsDraft = $this->costsDraftRepo->find($costsDraft->id);

        $dbCostsDraft = $dbCostsDraft->toArray();
        $this->assertModelData($costsDraft->toArray(), $dbCostsDraft);
    }

    /**
     * @test update
     */
    public function test_update_costs_draft()
    {
        $costsDraft = CostsDraft::factory()->create();
        $fakeCostsDraft = CostsDraft::factory()->make()->toArray();

        $updatedCostsDraft = $this->costsDraftRepo->update($fakeCostsDraft, $costsDraft->id);

        $this->assertModelData($fakeCostsDraft, $updatedCostsDraft->toArray());
        $dbCostsDraft = $this->costsDraftRepo->find($costsDraft->id);
        $this->assertModelData($fakeCostsDraft, $dbCostsDraft->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_costs_draft()
    {
        $costsDraft = CostsDraft::factory()->create();

        $resp = $this->costsDraftRepo->delete($costsDraft->id);

        $this->assertTrue($resp);
        $this->assertNull(CostsDraft::find($costsDraft->id), 'CostsDraft should not exist in DB');
    }
}
