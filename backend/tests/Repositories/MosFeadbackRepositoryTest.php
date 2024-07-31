<?php namespace Tests\Repositories;

use App\Models\MosFeadback;
use App\Repositories\MosFeadbackRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MosFeadbackRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MosFeadbackRepository
     */
    protected $mosFeadbackRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->mosFeadbackRepo = \App::make(MosFeadbackRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_mos_feadback()
    {
        $mosFeadback = MosFeadback::factory()->make()->toArray();

        $createdMosFeadback = $this->mosFeadbackRepo->create($mosFeadback);

        $createdMosFeadback = $createdMosFeadback->toArray();
        $this->assertArrayHasKey('id', $createdMosFeadback);
        $this->assertNotNull($createdMosFeadback['id'], 'Created MosFeadback must have id specified');
        $this->assertNotNull(MosFeadback::find($createdMosFeadback['id']), 'MosFeadback with given id must be in DB');
        $this->assertModelData($mosFeadback, $createdMosFeadback);
    }

    /**
     * @test read
     */
    public function test_read_mos_feadback()
    {
        $mosFeadback = MosFeadback::factory()->create();

        $dbMosFeadback = $this->mosFeadbackRepo->find($mosFeadback->id);

        $dbMosFeadback = $dbMosFeadback->toArray();
        $this->assertModelData($mosFeadback->toArray(), $dbMosFeadback);
    }

    /**
     * @test update
     */
    public function test_update_mos_feadback()
    {
        $mosFeadback = MosFeadback::factory()->create();
        $fakeMosFeadback = MosFeadback::factory()->make()->toArray();

        $updatedMosFeadback = $this->mosFeadbackRepo->update($fakeMosFeadback, $mosFeadback->id);

        $this->assertModelData($fakeMosFeadback, $updatedMosFeadback->toArray());
        $dbMosFeadback = $this->mosFeadbackRepo->find($mosFeadback->id);
        $this->assertModelData($fakeMosFeadback, $dbMosFeadback->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_mos_feadback()
    {
        $mosFeadback = MosFeadback::factory()->create();

        $resp = $this->mosFeadbackRepo->delete($mosFeadback->id);

        $this->assertTrue($resp);
        $this->assertNull(MosFeadback::find($mosFeadback->id), 'MosFeadback should not exist in DB');
    }
}
