<?php namespace Tests\Repositories;

use App\Models\PriorityTaskComments;
use App\Repositories\PriorityTaskCommentsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PriorityTaskCommentsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var PriorityTaskCommentsRepository
     */
    protected $priorityTaskCommentsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->priorityTaskCommentsRepo = \App::make(PriorityTaskCommentsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_priority_task_comments()
    {
        $priorityTaskComments = PriorityTaskComments::factory()->make()->toArray();

        $createdPriorityTaskComments = $this->priorityTaskCommentsRepo->create($priorityTaskComments);

        $createdPriorityTaskComments = $createdPriorityTaskComments->toArray();
        $this->assertArrayHasKey('id', $createdPriorityTaskComments);
        $this->assertNotNull($createdPriorityTaskComments['id'], 'Created PriorityTaskComments must have id specified');
        $this->assertNotNull(PriorityTaskComments::find($createdPriorityTaskComments['id']), 'PriorityTaskComments with given id must be in DB');
        $this->assertModelData($priorityTaskComments, $createdPriorityTaskComments);
    }

    /**
     * @test read
     */
    public function test_read_priority_task_comments()
    {
        $priorityTaskComments = PriorityTaskComments::factory()->create();

        $dbPriorityTaskComments = $this->priorityTaskCommentsRepo->find($priorityTaskComments->id);

        $dbPriorityTaskComments = $dbPriorityTaskComments->toArray();
        $this->assertModelData($priorityTaskComments->toArray(), $dbPriorityTaskComments);
    }

    /**
     * @test update
     */
    public function test_update_priority_task_comments()
    {
        $priorityTaskComments = PriorityTaskComments::factory()->create();
        $fakePriorityTaskComments = PriorityTaskComments::factory()->make()->toArray();

        $updatedPriorityTaskComments = $this->priorityTaskCommentsRepo->update($fakePriorityTaskComments, $priorityTaskComments->id);

        $this->assertModelData($fakePriorityTaskComments, $updatedPriorityTaskComments->toArray());
        $dbPriorityTaskComments = $this->priorityTaskCommentsRepo->find($priorityTaskComments->id);
        $this->assertModelData($fakePriorityTaskComments, $dbPriorityTaskComments->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_priority_task_comments()
    {
        $priorityTaskComments = PriorityTaskComments::factory()->create();

        $resp = $this->priorityTaskCommentsRepo->delete($priorityTaskComments->id);

        $this->assertTrue($resp);
        $this->assertNull(PriorityTaskComments::find($priorityTaskComments->id), 'PriorityTaskComments should not exist in DB');
    }
}
