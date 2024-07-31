<?php namespace Tests\Repositories;

use App\Models\SapFiles;
use App\Repositories\SapFilesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SapFilesRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var SapFilesRepository
     */
    protected $sapFilesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->sapFilesRepo = \App::make(SapFilesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_sap_files()
    {
        $sapFiles = SapFiles::factory()->make()->toArray();

        $createdSapFiles = $this->sapFilesRepo->create($sapFiles);

        $createdSapFiles = $createdSapFiles->toArray();
        $this->assertArrayHasKey('id', $createdSapFiles);
        $this->assertNotNull($createdSapFiles['id'], 'Created SapFiles must have id specified');
        $this->assertNotNull(SapFiles::find($createdSapFiles['id']), 'SapFiles with given id must be in DB');
        $this->assertModelData($sapFiles, $createdSapFiles);
    }

    /**
     * @test read
     */
    public function test_read_sap_files()
    {
        $sapFiles = SapFiles::factory()->create();

        $dbSapFiles = $this->sapFilesRepo->find($sapFiles->id);

        $dbSapFiles = $dbSapFiles->toArray();
        $this->assertModelData($sapFiles->toArray(), $dbSapFiles);
    }

    /**
     * @test update
     */
    public function test_update_sap_files()
    {
        $sapFiles = SapFiles::factory()->create();
        $fakeSapFiles = SapFiles::factory()->make()->toArray();

        $updatedSapFiles = $this->sapFilesRepo->update($fakeSapFiles, $sapFiles->id);

        $this->assertModelData($fakeSapFiles, $updatedSapFiles->toArray());
        $dbSapFiles = $this->sapFilesRepo->find($sapFiles->id);
        $this->assertModelData($fakeSapFiles, $dbSapFiles->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_sap_files()
    {
        $sapFiles = SapFiles::factory()->create();

        $resp = $this->sapFilesRepo->delete($sapFiles->id);

        $this->assertTrue($resp);
        $this->assertNull(SapFiles::find($sapFiles->id), 'SapFiles should not exist in DB');
    }
}
