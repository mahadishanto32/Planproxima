<?php namespace Tests\Repositories;

use App\Models\MosData;
use App\Repositories\MosDataRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MosDataRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MosDataRepository
     */
    protected $mosDataRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->mosDataRepo = \App::make(MosDataRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_mos_data()
    {
        $mosData = MosData::factory()->make()->toArray();

        $createdMosData = $this->mosDataRepo->create($mosData);

        $createdMosData = $createdMosData->toArray();
        $this->assertArrayHasKey('id', $createdMosData);
        $this->assertNotNull($createdMosData['id'], 'Created MosData must have id specified');
        $this->assertNotNull(MosData::find($createdMosData['id']), 'MosData with given id must be in DB');
        $this->assertModelData($mosData, $createdMosData);
    }

    /**
     * @test read
     */
    public function test_read_mos_data()
    {
        $mosData = MosData::factory()->create();

        $dbMosData = $this->mosDataRepo->find($mosData->id);

        $dbMosData = $dbMosData->toArray();
        $this->assertModelData($mosData->toArray(), $dbMosData);
    }

    /**
     * @test update
     */
    public function test_update_mos_data()
    {
        $mosData = MosData::factory()->create();
        $fakeMosData = MosData::factory()->make()->toArray();

        $updatedMosData = $this->mosDataRepo->update($fakeMosData, $mosData->id);

        $this->assertModelData($fakeMosData, $updatedMosData->toArray());
        $dbMosData = $this->mosDataRepo->find($mosData->id);
        $this->assertModelData($fakeMosData, $dbMosData->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_mos_data()
    {
        $mosData = MosData::factory()->create();

        $resp = $this->mosDataRepo->delete($mosData->id);

        $this->assertTrue($resp);
        $this->assertNull(MosData::find($mosData->id), 'MosData should not exist in DB');
    }
}
