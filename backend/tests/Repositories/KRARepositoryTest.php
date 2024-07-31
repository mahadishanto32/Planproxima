<?php namespace Tests\Repositories;

use App\Models\KRA;
use App\Repositories\KRARepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class KRARepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var KRARepository
     */
    protected $kRARepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->kRARepo = \App::make(KRARepository::class);
    }

    /**
     * @test create
     */
    public function test_create_k_r_a()
    {
        $kRA = KRA::factory()->make()->toArray();

        $createdKRA = $this->kRARepo->create($kRA);

        $createdKRA = $createdKRA->toArray();
        $this->assertArrayHasKey('id', $createdKRA);
        $this->assertNotNull($createdKRA['id'], 'Created KRA must have id specified');
        $this->assertNotNull(KRA::find($createdKRA['id']), 'KRA with given id must be in DB');
        $this->assertModelData($kRA, $createdKRA);
    }

    /**
     * @test read
     */
    public function test_read_k_r_a()
    {
        $kRA = KRA::factory()->create();

        $dbKRA = $this->kRARepo->find($kRA->id);

        $dbKRA = $dbKRA->toArray();
        $this->assertModelData($kRA->toArray(), $dbKRA);
    }

    /**
     * @test update
     */
    public function test_update_k_r_a()
    {
        $kRA = KRA::factory()->create();
        $fakeKRA = KRA::factory()->make()->toArray();

        $updatedKRA = $this->kRARepo->update($fakeKRA, $kRA->id);

        $this->assertModelData($fakeKRA, $updatedKRA->toArray());
        $dbKRA = $this->kRARepo->find($kRA->id);
        $this->assertModelData($fakeKRA, $dbKRA->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_k_r_a()
    {
        $kRA = KRA::factory()->create();

        $resp = $this->kRARepo->delete($kRA->id);

        $this->assertTrue($resp);
        $this->assertNull(KRA::find($kRA->id), 'KRA should not exist in DB');
    }
}
