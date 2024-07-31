<?php namespace Tests\Repositories;

use App\Models\FactoryStandard;
use App\Repositories\FactoryStandardRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class FactoryStandardRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var FactoryStandardRepository
     */
    protected $factoryStandardRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->factoryStandardRepo = \App::make(FactoryStandardRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_factory_standard()
    {
        $factoryStandard = FactoryStandard::factory()->make()->toArray();

        $createdFactoryStandard = $this->factoryStandardRepo->create($factoryStandard);

        $createdFactoryStandard = $createdFactoryStandard->toArray();
        $this->assertArrayHasKey('id', $createdFactoryStandard);
        $this->assertNotNull($createdFactoryStandard['id'], 'Created FactoryStandard must have id specified');
        $this->assertNotNull(FactoryStandard::find($createdFactoryStandard['id']), 'FactoryStandard with given id must be in DB');
        $this->assertModelData($factoryStandard, $createdFactoryStandard);
    }

    /**
     * @test read
     */
    public function test_read_factory_standard()
    {
        $factoryStandard = FactoryStandard::factory()->create();

        $dbFactoryStandard = $this->factoryStandardRepo->find($factoryStandard->id);

        $dbFactoryStandard = $dbFactoryStandard->toArray();
        $this->assertModelData($factoryStandard->toArray(), $dbFactoryStandard);
    }

    /**
     * @test update
     */
    public function test_update_factory_standard()
    {
        $factoryStandard = FactoryStandard::factory()->create();
        $fakeFactoryStandard = FactoryStandard::factory()->make()->toArray();

        $updatedFactoryStandard = $this->factoryStandardRepo->update($fakeFactoryStandard, $factoryStandard->id);

        $this->assertModelData($fakeFactoryStandard, $updatedFactoryStandard->toArray());
        $dbFactoryStandard = $this->factoryStandardRepo->find($factoryStandard->id);
        $this->assertModelData($fakeFactoryStandard, $dbFactoryStandard->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_factory_standard()
    {
        $factoryStandard = FactoryStandard::factory()->create();

        $resp = $this->factoryStandardRepo->delete($factoryStandard->id);

        $this->assertTrue($resp);
        $this->assertNull(FactoryStandard::find($factoryStandard->id), 'FactoryStandard should not exist in DB');
    }
}
