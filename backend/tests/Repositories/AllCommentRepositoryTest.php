<?php namespace Tests\Repositories;

use App\Models\AllComment;
use App\Repositories\AllCommentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AllCommentRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var AllCommentRepository
     */
    protected $allCommentRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->allCommentRepo = \App::make(AllCommentRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_all_comment()
    {
        $allComment = AllComment::factory()->make()->toArray();

        $createdAllComment = $this->allCommentRepo->create($allComment);

        $createdAllComment = $createdAllComment->toArray();
        $this->assertArrayHasKey('id', $createdAllComment);
        $this->assertNotNull($createdAllComment['id'], 'Created AllComment must have id specified');
        $this->assertNotNull(AllComment::find($createdAllComment['id']), 'AllComment with given id must be in DB');
        $this->assertModelData($allComment, $createdAllComment);
    }

    /**
     * @test read
     */
    public function test_read_all_comment()
    {
        $allComment = AllComment::factory()->create();

        $dbAllComment = $this->allCommentRepo->find($allComment->id);

        $dbAllComment = $dbAllComment->toArray();
        $this->assertModelData($allComment->toArray(), $dbAllComment);
    }

    /**
     * @test update
     */
    public function test_update_all_comment()
    {
        $allComment = AllComment::factory()->create();
        $fakeAllComment = AllComment::factory()->make()->toArray();

        $updatedAllComment = $this->allCommentRepo->update($fakeAllComment, $allComment->id);

        $this->assertModelData($fakeAllComment, $updatedAllComment->toArray());
        $dbAllComment = $this->allCommentRepo->find($allComment->id);
        $this->assertModelData($fakeAllComment, $dbAllComment->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_all_comment()
    {
        $allComment = AllComment::factory()->create();

        $resp = $this->allCommentRepo->delete($allComment->id);

        $this->assertTrue($resp);
        $this->assertNull(AllComment::find($allComment->id), 'AllComment should not exist in DB');
    }
}
