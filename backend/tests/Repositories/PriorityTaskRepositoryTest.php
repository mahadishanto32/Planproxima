<?php namespace Tests\Repositories;

use App\Models\PriorityTask;
use App\Repositories\PriorityTaskRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PriorityTaskRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PriorityTaskRepository
     */
    protected $priorityTaskRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->priorityTaskRepo = \App::make(PriorityTaskRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_priority_task()
    {
        $priorityTask = PriorityTask::factory()->make()->toArray();

        $createdPriorityTask = $this->priorityTaskRepo->create($priorityTask);

        $createdPriorityTask = $createdPriorityTask->toArray();
        $this->assertArrayHasKey('id', $createdPriorityTask);
        $this->assertNotNull($createdPriorityTask['id'], 'Created PriorityTask must have id specified');
        $this->assertNotNull(PriorityTask::find($createdPriorityTask['id']), 'PriorityTask with given id must be in DB');
        $this->assertModelData($priorityTask, $createdPriorityTask);
    }

    /**
     * @test read
     */
    public function test_read_priority_task()
    {
        $priorityTask = PriorityTask::factory()->create();

        $dbPriorityTask = $this->priorityTaskRepo->find($priorityTask->id);

        $dbPriorityTask = $dbPriorityTask->toArray();
        $this->assertModelData($priorityTask->toArray(), $dbPriorityTask);
    }

    /**
     * @test update
     */
    public function test_update_priority_task()
    {
        $priorityTask = PriorityTask::factory()->create();
        $fakePriorityTask = PriorityTask::factory()->make()->toArray();

        $updatedPriorityTask = $this->priorityTaskRepo->update($fakePriorityTask, $priorityTask->id);

        $this->assertModelData($fakePriorityTask, $updatedPriorityTask->toArray());
        $dbPriorityTask = $this->priorityTaskRepo->find($priorityTask->id);
        $this->assertModelData($fakePriorityTask, $dbPriorityTask->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_priority_task()
    {
        $priorityTask = PriorityTask::factory()->create();

        $resp = $this->priorityTaskRepo->delete($priorityTask->id);

        $this->assertTrue($resp);
        $this->assertNull(PriorityTask::find($priorityTask->id), 'PriorityTask should not exist in DB');
    }
}
