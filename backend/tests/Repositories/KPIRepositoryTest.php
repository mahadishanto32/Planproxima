<?php namespace Tests\Repositories;

use App\Models\KPI;
use App\Repositories\KPIRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class KPIRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var KPIRepository
     */
    protected $kPIRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->kPIRepo = \App::make(KPIRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_k_p_i()
    {
        $kPI = KPI::factory()->make()->toArray();

        $createdKPI = $this->kPIRepo->create($kPI);

        $createdKPI = $createdKPI->toArray();
        $this->assertArrayHasKey('id', $createdKPI);
        $this->assertNotNull($createdKPI['id'], 'Created KPI must have id specified');
        $this->assertNotNull(KPI::find($createdKPI['id']), 'KPI with given id must be in DB');
        $this->assertModelData($kPI, $createdKPI);
    }

    /**
     * @test read
     */
    public function test_read_k_p_i()
    {
        $kPI = KPI::factory()->create();

        $dbKPI = $this->kPIRepo->find($kPI->id);

        $dbKPI = $dbKPI->toArray();
        $this->assertModelData($kPI->toArray(), $dbKPI);
    }

    /**
     * @test update
     */
    public function test_update_k_p_i()
    {
        $kPI = KPI::factory()->create();
        $fakeKPI = KPI::factory()->make()->toArray();

        $updatedKPI = $this->kPIRepo->update($fakeKPI, $kPI->id);

        $this->assertModelData($fakeKPI, $updatedKPI->toArray());
        $dbKPI = $this->kPIRepo->find($kPI->id);
        $this->assertModelData($fakeKPI, $dbKPI->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_k_p_i()
    {
        $kPI = KPI::factory()->create();

        $resp = $this->kPIRepo->delete($kPI->id);

        $this->assertTrue($resp);
        $this->assertNull(KPI::find($kPI->id), 'KPI should not exist in DB');
    }
}
