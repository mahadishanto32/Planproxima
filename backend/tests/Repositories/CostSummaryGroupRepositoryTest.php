<?php namespace Tests\Repositories;

use App\Models\CostSummaryGroup;
use App\Repositories\CostSummaryGroupRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CostSummaryGroupRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CostSummaryGroupRepository
     */
    protected $costSummaryGroupRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->costSummaryGroupRepo = \App::make(CostSummaryGroupRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_cost_summary_group()
    {
        $costSummaryGroup = CostSummaryGroup::factory()->make()->toArray();

        $createdCostSummaryGroup = $this->costSummaryGroupRepo->create($costSummaryGroup);

        $createdCostSummaryGroup = $createdCostSummaryGroup->toArray();
        $this->assertArrayHasKey('id', $createdCostSummaryGroup);
        $this->assertNotNull($createdCostSummaryGroup['id'], 'Created CostSummaryGroup must have id specified');
        $this->assertNotNull(CostSummaryGroup::find($createdCostSummaryGroup['id']), 'CostSummaryGroup with given id must be in DB');
        $this->assertModelData($costSummaryGroup, $createdCostSummaryGroup);
    }

    /**
     * @test read
     */
    public function test_read_cost_summary_group()
    {
        $costSummaryGroup = CostSummaryGroup::factory()->create();

        $dbCostSummaryGroup = $this->costSummaryGroupRepo->find($costSummaryGroup->id);

        $dbCostSummaryGroup = $dbCostSummaryGroup->toArray();
        $this->assertModelData($costSummaryGroup->toArray(), $dbCostSummaryGroup);
    }

    /**
     * @test update
     */
    public function test_update_cost_summary_group()
    {
        $costSummaryGroup = CostSummaryGroup::factory()->create();
        $fakeCostSummaryGroup = CostSummaryGroup::factory()->make()->toArray();

        $updatedCostSummaryGroup = $this->costSummaryGroupRepo->update($fakeCostSummaryGroup, $costSummaryGroup->id);

        $this->assertModelData($fakeCostSummaryGroup, $updatedCostSummaryGroup->toArray());
        $dbCostSummaryGroup = $this->costSummaryGroupRepo->find($costSummaryGroup->id);
        $this->assertModelData($fakeCostSummaryGroup, $dbCostSummaryGroup->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_cost_summary_group()
    {
        $costSummaryGroup = CostSummaryGroup::factory()->create();

        $resp = $this->costSummaryGroupRepo->delete($costSummaryGroup->id);

        $this->assertTrue($resp);
        $this->assertNull(CostSummaryGroup::find($costSummaryGroup->id), 'CostSummaryGroup should not exist in DB');
    }
}
