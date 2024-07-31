<?php namespace Tests\Repositories;

use App\Models\FollowUpDept;
use App\Repositories\FollowUpDeptRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class FollowUpDeptRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var FollowUpDeptRepository
     */
    protected $followUpDeptRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->followUpDeptRepo = \App::make(FollowUpDeptRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_follow_up_dept()
    {
        $followUpDept = FollowUpDept::factory()->make()->toArray();

        $createdFollowUpDept = $this->followUpDeptRepo->create($followUpDept);

        $createdFollowUpDept = $createdFollowUpDept->toArray();
        $this->assertArrayHasKey('id', $createdFollowUpDept);
        $this->assertNotNull($createdFollowUpDept['id'], 'Created FollowUpDept must have id specified');
        $this->assertNotNull(FollowUpDept::find($createdFollowUpDept['id']), 'FollowUpDept with given id must be in DB');
        $this->assertModelData($followUpDept, $createdFollowUpDept);
    }

    /**
     * @test read
     */
    public function test_read_follow_up_dept()
    {
        $followUpDept = FollowUpDept::factory()->create();

        $dbFollowUpDept = $this->followUpDeptRepo->find($followUpDept->id);

        $dbFollowUpDept = $dbFollowUpDept->toArray();
        $this->assertModelData($followUpDept->toArray(), $dbFollowUpDept);
    }

    /**
     * @test update
     */
    public function test_update_follow_up_dept()
    {
        $followUpDept = FollowUpDept::factory()->create();
        $fakeFollowUpDept = FollowUpDept::factory()->make()->toArray();

        $updatedFollowUpDept = $this->followUpDeptRepo->update($fakeFollowUpDept, $followUpDept->id);

        $this->assertModelData($fakeFollowUpDept, $updatedFollowUpDept->toArray());
        $dbFollowUpDept = $this->followUpDeptRepo->find($followUpDept->id);
        $this->assertModelData($fakeFollowUpDept, $dbFollowUpDept->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_follow_up_dept()
    {
        $followUpDept = FollowUpDept::factory()->create();

        $resp = $this->followUpDeptRepo->delete($followUpDept->id);

        $this->assertTrue($resp);
        $this->assertNull(FollowUpDept::find($followUpDept->id), 'FollowUpDept should not exist in DB');
    }
}
