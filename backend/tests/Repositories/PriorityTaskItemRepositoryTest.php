<?php namespace Tests\Repositories;

use App\Models\PriorityTaskItem;
use App\Repositories\PriorityTaskItemRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PriorityTaskItemRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PriorityTaskItemRepository
     */
    protected $priorityTaskItemRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->priorityTaskItemRepo = \App::make(PriorityTaskItemRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_priority_task_item()
    {
        $priorityTaskItem = PriorityTaskItem::factory()->make()->toArray();

        $createdPriorityTaskItem = $this->priorityTaskItemRepo->create($priorityTaskItem);

        $createdPriorityTaskItem = $createdPriorityTaskItem->toArray();
        $this->assertArrayHasKey('id', $createdPriorityTaskItem);
        $this->assertNotNull($createdPriorityTaskItem['id'], 'Created PriorityTaskItem must have id specified');
        $this->assertNotNull(PriorityTaskItem::find($createdPriorityTaskItem['id']), 'PriorityTaskItem with given id must be in DB');
        $this->assertModelData($priorityTaskItem, $createdPriorityTaskItem);
    }

    /**
     * @test read
     */
    public function test_read_priority_task_item()
    {
        $priorityTaskItem = PriorityTaskItem::factory()->create();

        $dbPriorityTaskItem = $this->priorityTaskItemRepo->find($priorityTaskItem->id);

        $dbPriorityTaskItem = $dbPriorityTaskItem->toArray();
        $this->assertModelData($priorityTaskItem->toArray(), $dbPriorityTaskItem);
    }

    /**
     * @test update
     */
    public function test_update_priority_task_item()
    {
        $priorityTaskItem = PriorityTaskItem::factory()->create();
        $fakePriorityTaskItem = PriorityTaskItem::factory()->make()->toArray();

        $updatedPriorityTaskItem = $this->priorityTaskItemRepo->update($fakePriorityTaskItem, $priorityTaskItem->id);

        $this->assertModelData($fakePriorityTaskItem, $updatedPriorityTaskItem->toArray());
        $dbPriorityTaskItem = $this->priorityTaskItemRepo->find($priorityTaskItem->id);
        $this->assertModelData($fakePriorityTaskItem, $dbPriorityTaskItem->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_priority_task_item()
    {
        $priorityTaskItem = PriorityTaskItem::factory()->create();

        $resp = $this->priorityTaskItemRepo->delete($priorityTaskItem->id);

        $this->assertTrue($resp);
        $this->assertNull(PriorityTaskItem::find($priorityTaskItem->id), 'PriorityTaskItem should not exist in DB');
    }
}
