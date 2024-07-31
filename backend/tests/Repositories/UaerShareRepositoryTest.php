<?php namespace Tests\Repositories;

use App\Models\UaerShare;
use App\Repositories\UaerShareRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class UaerShareRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var UaerShareRepository
     */
    protected $uaerShareRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->uaerShareRepo = \App::make(UaerShareRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_uaer_share()
    {
        $uaerShare = UaerShare::factory()->make()->toArray();

        $createdUaerShare = $this->uaerShareRepo->create($uaerShare);

        $createdUaerShare = $createdUaerShare->toArray();
        $this->assertArrayHasKey('id', $createdUaerShare);
        $this->assertNotNull($createdUaerShare['id'], 'Created UaerShare must have id specified');
        $this->assertNotNull(UaerShare::find($createdUaerShare['id']), 'UaerShare with given id must be in DB');
        $this->assertModelData($uaerShare, $createdUaerShare);
    }

    /**
     * @test read
     */
    public function test_read_uaer_share()
    {
        $uaerShare = UaerShare::factory()->create();

        $dbUaerShare = $this->uaerShareRepo->find($uaerShare->id);

        $dbUaerShare = $dbUaerShare->toArray();
        $this->assertModelData($uaerShare->toArray(), $dbUaerShare);
    }

    /**
     * @test update
     */
    public function test_update_uaer_share()
    {
        $uaerShare = UaerShare::factory()->create();
        $fakeUaerShare = UaerShare::factory()->make()->toArray();

        $updatedUaerShare = $this->uaerShareRepo->update($fakeUaerShare, $uaerShare->id);

        $this->assertModelData($fakeUaerShare, $updatedUaerShare->toArray());
        $dbUaerShare = $this->uaerShareRepo->find($uaerShare->id);
        $this->assertModelData($fakeUaerShare, $dbUaerShare->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_uaer_share()
    {
        $uaerShare = UaerShare::factory()->create();

        $resp = $this->uaerShareRepo->delete($uaerShare->id);

        $this->assertTrue($resp);
        $this->assertNull(UaerShare::find($uaerShare->id), 'UaerShare should not exist in DB');
    }
}
