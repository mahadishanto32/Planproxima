<?php namespace Tests\Repositories;

use App\Models\AllLike;
use App\Repositories\AllLikeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AllLikeRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var AllLikeRepository
     */
    protected $allLikeRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->allLikeRepo = \App::make(AllLikeRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_all_like()
    {
        $allLike = AllLike::factory()->make()->toArray();

        $createdAllLike = $this->allLikeRepo->create($allLike);

        $createdAllLike = $createdAllLike->toArray();
        $this->assertArrayHasKey('id', $createdAllLike);
        $this->assertNotNull($createdAllLike['id'], 'Created AllLike must have id specified');
        $this->assertNotNull(AllLike::find($createdAllLike['id']), 'AllLike with given id must be in DB');
        $this->assertModelData($allLike, $createdAllLike);
    }

    /**
     * @test read
     */
    public function test_read_all_like()
    {
        $allLike = AllLike::factory()->create();

        $dbAllLike = $this->allLikeRepo->find($allLike->id);

        $dbAllLike = $dbAllLike->toArray();
        $this->assertModelData($allLike->toArray(), $dbAllLike);
    }

    /**
     * @test update
     */
    public function test_update_all_like()
    {
        $allLike = AllLike::factory()->create();
        $fakeAllLike = AllLike::factory()->make()->toArray();

        $updatedAllLike = $this->allLikeRepo->update($fakeAllLike, $allLike->id);

        $this->assertModelData($fakeAllLike, $updatedAllLike->toArray());
        $dbAllLike = $this->allLikeRepo->find($allLike->id);
        $this->assertModelData($fakeAllLike, $dbAllLike->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_all_like()
    {
        $allLike = AllLike::factory()->create();

        $resp = $this->allLikeRepo->delete($allLike->id);

        $this->assertTrue($resp);
        $this->assertNull(AllLike::find($allLike->id), 'AllLike should not exist in DB');
    }
}
