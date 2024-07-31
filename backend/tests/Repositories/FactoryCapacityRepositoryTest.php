<?php namespace Tests\Repositories;

use App\Models\FactoryCapacity;
use App\Repositories\FactoryCapacityRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class FactoryCapacityRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var FactoryCapacityRepository
     */
    protected $factoryCapacityRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->factoryCapacityRepo = \App::make(FactoryCapacityRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_factory_capacity()
    {
        $factoryCapacity = FactoryCapacity::factory()->make()->toArray();

        $createdFactoryCapacity = $this->factoryCapacityRepo->create($factoryCapacity);

        $createdFactoryCapacity = $createdFactoryCapacity->toArray();
        $this->assertArrayHasKey('id', $createdFactoryCapacity);
        $this->assertNotNull($createdFactoryCapacity['id'], 'Created FactoryCapacity must have id specified');
        $this->assertNotNull(FactoryCapacity::find($createdFactoryCapacity['id']), 'FactoryCapacity with given id must be in DB');
        $this->assertModelData($factoryCapacity, $createdFactoryCapacity);
    }

    /**
     * @test read
     */
    public function test_read_factory_capacity()
    {
        $factoryCapacity = FactoryCapacity::factory()->create();

        $dbFactoryCapacity = $this->factoryCapacityRepo->find($factoryCapacity->id);

        $dbFactoryCapacity = $dbFactoryCapacity->toArray();
        $this->assertModelData($factoryCapacity->toArray(), $dbFactoryCapacity);
    }

    /**
     * @test update
     */
    public function test_update_factory_capacity()
    {
        $factoryCapacity = FactoryCapacity::factory()->create();
        $fakeFactoryCapacity = FactoryCapacity::factory()->make()->toArray();

        $updatedFactoryCapacity = $this->factoryCapacityRepo->update($fakeFactoryCapacity, $factoryCapacity->id);

        $this->assertModelData($fakeFactoryCapacity, $updatedFactoryCapacity->toArray());
        $dbFactoryCapacity = $this->factoryCapacityRepo->find($factoryCapacity->id);
        $this->assertModelData($fakeFactoryCapacity, $dbFactoryCapacity->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_factory_capacity()
    {
        $factoryCapacity = FactoryCapacity::factory()->create();

        $resp = $this->factoryCapacityRepo->delete($factoryCapacity->id);

        $this->assertTrue($resp);
        $this->assertNull(FactoryCapacity::find($factoryCapacity->id), 'FactoryCapacity should not exist in DB');
    }
}
