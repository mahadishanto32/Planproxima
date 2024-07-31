<?php namespace Tests\Repositories;

use App\Models\Wing;
use App\Repositories\WingRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class WingRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var WingRepository
     */
    protected $wingRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->wingRepo = \App::make(WingRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_wing()
    {
        $wing = Wing::factory()->make()->toArray();

        $createdWing = $this->wingRepo->create($wing);

        $createdWing = $createdWing->toArray();
        $this->assertArrayHasKey('id', $createdWing);
        $this->assertNotNull($createdWing['id'], 'Created Wing must have id specified');
        $this->assertNotNull(Wing::find($createdWing['id']), 'Wing with given id must be in DB');
        $this->assertModelData($wing, $createdWing);
    }

    /**
     * @test read
     */
    public function test_read_wing()
    {
        $wing = Wing::factory()->create();

        $dbWing = $this->wingRepo->find($wing->id);

        $dbWing = $dbWing->toArray();
        $this->assertModelData($wing->toArray(), $dbWing);
    }

    /**
     * @test update
     */
    public function test_update_wing()
    {
        $wing = Wing::factory()->create();
        $fakeWing = Wing::factory()->make()->toArray();

        $updatedWing = $this->wingRepo->update($fakeWing, $wing->id);

        $this->assertModelData($fakeWing, $updatedWing->toArray());
        $dbWing = $this->wingRepo->find($wing->id);
        $this->assertModelData($fakeWing, $dbWing->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_wing()
    {
        $wing = Wing::factory()->create();

        $resp = $this->wingRepo->delete($wing->id);

        $this->assertTrue($resp);
        $this->assertNull(Wing::find($wing->id), 'Wing should not exist in DB');
    }
}
