<?php namespace Tests\Repositories;

use App\Models\MosDataLog;
use App\Repositories\MosDataLogRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MosDataLogRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MosDataLogRepository
     */
    protected $mosDataLogRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->mosDataLogRepo = \App::make(MosDataLogRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_mos_data_log()
    {
        $mosDataLog = MosDataLog::factory()->make()->toArray();

        $createdMosDataLog = $this->mosDataLogRepo->create($mosDataLog);

        $createdMosDataLog = $createdMosDataLog->toArray();
        $this->assertArrayHasKey('id', $createdMosDataLog);
        $this->assertNotNull($createdMosDataLog['id'], 'Created MosDataLog must have id specified');
        $this->assertNotNull(MosDataLog::find($createdMosDataLog['id']), 'MosDataLog with given id must be in DB');
        $this->assertModelData($mosDataLog, $createdMosDataLog);
    }

    /**
     * @test read
     */
    public function test_read_mos_data_log()
    {
        $mosDataLog = MosDataLog::factory()->create();

        $dbMosDataLog = $this->mosDataLogRepo->find($mosDataLog->id);

        $dbMosDataLog = $dbMosDataLog->toArray();
        $this->assertModelData($mosDataLog->toArray(), $dbMosDataLog);
    }

    /**
     * @test update
     */
    public function test_update_mos_data_log()
    {
        $mosDataLog = MosDataLog::factory()->create();
        $fakeMosDataLog = MosDataLog::factory()->make()->toArray();

        $updatedMosDataLog = $this->mosDataLogRepo->update($fakeMosDataLog, $mosDataLog->id);

        $this->assertModelData($fakeMosDataLog, $updatedMosDataLog->toArray());
        $dbMosDataLog = $this->mosDataLogRepo->find($mosDataLog->id);
        $this->assertModelData($fakeMosDataLog, $dbMosDataLog->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_mos_data_log()
    {
        $mosDataLog = MosDataLog::factory()->create();

        $resp = $this->mosDataLogRepo->delete($mosDataLog->id);

        $this->assertTrue($resp);
        $this->assertNull(MosDataLog::find($mosDataLog->id), 'MosDataLog should not exist in DB');
    }
}
