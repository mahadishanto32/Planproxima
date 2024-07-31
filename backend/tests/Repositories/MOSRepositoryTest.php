<?php namespace Tests\Repositories;

use App\Models\MOS;
use App\Repositories\MOSRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MOSRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MOSRepository
     */
    protected $mOSRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->mOSRepo = \App::make(MOSRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_m_o_s()
    {
        $mOS = MOS::factory()->make()->toArray();

        $createdMOS = $this->mOSRepo->create($mOS);

        $createdMOS = $createdMOS->toArray();
        $this->assertArrayHasKey('id', $createdMOS);
        $this->assertNotNull($createdMOS['id'], 'Created MOS must have id specified');
        $this->assertNotNull(MOS::find($createdMOS['id']), 'MOS with given id must be in DB');
        $this->assertModelData($mOS, $createdMOS);
    }

    /**
     * @test read
     */
    public function test_read_m_o_s()
    {
        $mOS = MOS::factory()->create();

        $dbMOS = $this->mOSRepo->find($mOS->id);

        $dbMOS = $dbMOS->toArray();
        $this->assertModelData($mOS->toArray(), $dbMOS);
    }

    /**
     * @test update
     */
    public function test_update_m_o_s()
    {
        $mOS = MOS::factory()->create();
        $fakeMOS = MOS::factory()->make()->toArray();

        $updatedMOS = $this->mOSRepo->update($fakeMOS, $mOS->id);

        $this->assertModelData($fakeMOS, $updatedMOS->toArray());
        $dbMOS = $this->mOSRepo->find($mOS->id);
        $this->assertModelData($fakeMOS, $dbMOS->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_m_o_s()
    {
        $mOS = MOS::factory()->create();

        $resp = $this->mOSRepo->delete($mOS->id);

        $this->assertTrue($resp);
        $this->assertNull(MOS::find($mOS->id), 'MOS should not exist in DB');
    }
}
