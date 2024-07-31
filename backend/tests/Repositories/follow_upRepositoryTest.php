<?php namespace Tests\Repositories;

use App\Models\follow_up;
use App\Repositories\follow_upRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class follow_upRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var follow_upRepository
     */
    protected $followUpRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->followUpRepo = \App::make(follow_upRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_follow_up()
    {
        $followUp = follow_up::factory()->make()->toArray();

        $createdfollow_up = $this->followUpRepo->create($followUp);

        $createdfollow_up = $createdfollow_up->toArray();
        $this->assertArrayHasKey('id', $createdfollow_up);
        $this->assertNotNull($createdfollow_up['id'], 'Created follow_up must have id specified');
        $this->assertNotNull(follow_up::find($createdfollow_up['id']), 'follow_up with given id must be in DB');
        $this->assertModelData($followUp, $createdfollow_up);
    }

    /**
     * @test read
     */
    public function test_read_follow_up()
    {
        $followUp = follow_up::factory()->create();

        $dbfollow_up = $this->followUpRepo->find($followUp->id);

        $dbfollow_up = $dbfollow_up->toArray();
        $this->assertModelData($followUp->toArray(), $dbfollow_up);
    }

    /**
     * @test update
     */
    public function test_update_follow_up()
    {
        $followUp = follow_up::factory()->create();
        $fakefollow_up = follow_up::factory()->make()->toArray();

        $updatedfollow_up = $this->followUpRepo->update($fakefollow_up, $followUp->id);

        $this->assertModelData($fakefollow_up, $updatedfollow_up->toArray());
        $dbfollow_up = $this->followUpRepo->find($followUp->id);
        $this->assertModelData($fakefollow_up, $dbfollow_up->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_follow_up()
    {
        $followUp = follow_up::factory()->create();

        $resp = $this->followUpRepo->delete($followUp->id);

        $this->assertTrue($resp);
        $this->assertNull(follow_up::find($followUp->id), 'follow_up should not exist in DB');
    }
}
