<?php namespace Tests\Repositories;

use App\Models\Daily_schedule_comment;
use App\Repositories\Daily_schedule_commentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class Daily_schedule_commentRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var Daily_schedule_commentRepository
     */
    protected $dailyScheduleCommentRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->dailyScheduleCommentRepo = \App::make(Daily_schedule_commentRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_daily_schedule_comment()
    {
        $dailyScheduleComment = Daily_schedule_comment::factory()->make()->toArray();

        $createdDaily_schedule_comment = $this->dailyScheduleCommentRepo->create($dailyScheduleComment);

        $createdDaily_schedule_comment = $createdDaily_schedule_comment->toArray();
        $this->assertArrayHasKey('id', $createdDaily_schedule_comment);
        $this->assertNotNull($createdDaily_schedule_comment['id'], 'Created Daily_schedule_comment must have id specified');
        $this->assertNotNull(Daily_schedule_comment::find($createdDaily_schedule_comment['id']), 'Daily_schedule_comment with given id must be in DB');
        $this->assertModelData($dailyScheduleComment, $createdDaily_schedule_comment);
    }

    /**
     * @test read
     */
    public function test_read_daily_schedule_comment()
    {
        $dailyScheduleComment = Daily_schedule_comment::factory()->create();

        $dbDaily_schedule_comment = $this->dailyScheduleCommentRepo->find($dailyScheduleComment->id);

        $dbDaily_schedule_comment = $dbDaily_schedule_comment->toArray();
        $this->assertModelData($dailyScheduleComment->toArray(), $dbDaily_schedule_comment);
    }

    /**
     * @test update
     */
    public function test_update_daily_schedule_comment()
    {
        $dailyScheduleComment = Daily_schedule_comment::factory()->create();
        $fakeDaily_schedule_comment = Daily_schedule_comment::factory()->make()->toArray();

        $updatedDaily_schedule_comment = $this->dailyScheduleCommentRepo->update($fakeDaily_schedule_comment, $dailyScheduleComment->id);

        $this->assertModelData($fakeDaily_schedule_comment, $updatedDaily_schedule_comment->toArray());
        $dbDaily_schedule_comment = $this->dailyScheduleCommentRepo->find($dailyScheduleComment->id);
        $this->assertModelData($fakeDaily_schedule_comment, $dbDaily_schedule_comment->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_daily_schedule_comment()
    {
        $dailyScheduleComment = Daily_schedule_comment::factory()->create();

        $resp = $this->dailyScheduleCommentRepo->delete($dailyScheduleComment->id);

        $this->assertTrue($resp);
        $this->assertNull(Daily_schedule_comment::find($dailyScheduleComment->id), 'Daily_schedule_comment should not exist in DB');
    }
}
