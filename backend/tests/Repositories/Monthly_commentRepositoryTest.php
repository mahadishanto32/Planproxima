<?php namespace Tests\Repositories;

use App\Models\Monthly_comment;
use App\Repositories\Monthly_commentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class Monthly_commentRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var Monthly_commentRepository
     */
    protected $monthlyCommentRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->monthlyCommentRepo = \App::make(Monthly_commentRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_monthly_comment()
    {
        $monthlyComment = Monthly_comment::factory()->make()->toArray();

        $createdMonthly_comment = $this->monthlyCommentRepo->create($monthlyComment);

        $createdMonthly_comment = $createdMonthly_comment->toArray();
        $this->assertArrayHasKey('id', $createdMonthly_comment);
        $this->assertNotNull($createdMonthly_comment['id'], 'Created Monthly_comment must have id specified');
        $this->assertNotNull(Monthly_comment::find($createdMonthly_comment['id']), 'Monthly_comment with given id must be in DB');
        $this->assertModelData($monthlyComment, $createdMonthly_comment);
    }

    /**
     * @test read
     */
    public function test_read_monthly_comment()
    {
        $monthlyComment = Monthly_comment::factory()->create();

        $dbMonthly_comment = $this->monthlyCommentRepo->find($monthlyComment->id);

        $dbMonthly_comment = $dbMonthly_comment->toArray();
        $this->assertModelData($monthlyComment->toArray(), $dbMonthly_comment);
    }

    /**
     * @test update
     */
    public function test_update_monthly_comment()
    {
        $monthlyComment = Monthly_comment::factory()->create();
        $fakeMonthly_comment = Monthly_comment::factory()->make()->toArray();

        $updatedMonthly_comment = $this->monthlyCommentRepo->update($fakeMonthly_comment, $monthlyComment->id);

        $this->assertModelData($fakeMonthly_comment, $updatedMonthly_comment->toArray());
        $dbMonthly_comment = $this->monthlyCommentRepo->find($monthlyComment->id);
        $this->assertModelData($fakeMonthly_comment, $dbMonthly_comment->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_monthly_comment()
    {
        $monthlyComment = Monthly_comment::factory()->create();

        $resp = $this->monthlyCommentRepo->delete($monthlyComment->id);

        $this->assertTrue($resp);
        $this->assertNull(Monthly_comment::find($monthlyComment->id), 'Monthly_comment should not exist in DB');
    }
}
