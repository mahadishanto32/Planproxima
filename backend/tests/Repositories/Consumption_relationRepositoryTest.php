<?php namespace Tests\Repositories;

use App\Models\Consumption_relation;
use App\Repositories\Consumption_relationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class Consumption_relationRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var Consumption_relationRepository
     */
    protected $consumptionRelationRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->consumptionRelationRepo = \App::make(Consumption_relationRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_consumption_relation()
    {
        $consumptionRelation = Consumption_relation::factory()->make()->toArray();

        $createdConsumption_relation = $this->consumptionRelationRepo->create($consumptionRelation);

        $createdConsumption_relation = $createdConsumption_relation->toArray();
        $this->assertArrayHasKey('id', $createdConsumption_relation);
        $this->assertNotNull($createdConsumption_relation['id'], 'Created Consumption_relation must have id specified');
        $this->assertNotNull(Consumption_relation::find($createdConsumption_relation['id']), 'Consumption_relation with given id must be in DB');
        $this->assertModelData($consumptionRelation, $createdConsumption_relation);
    }

    /**
     * @test read
     */
    public function test_read_consumption_relation()
    {
        $consumptionRelation = Consumption_relation::factory()->create();

        $dbConsumption_relation = $this->consumptionRelationRepo->find($consumptionRelation->id);

        $dbConsumption_relation = $dbConsumption_relation->toArray();
        $this->assertModelData($consumptionRelation->toArray(), $dbConsumption_relation);
    }

    /**
     * @test update
     */
    public function test_update_consumption_relation()
    {
        $consumptionRelation = Consumption_relation::factory()->create();
        $fakeConsumption_relation = Consumption_relation::factory()->make()->toArray();

        $updatedConsumption_relation = $this->consumptionRelationRepo->update($fakeConsumption_relation, $consumptionRelation->id);

        $this->assertModelData($fakeConsumption_relation, $updatedConsumption_relation->toArray());
        $dbConsumption_relation = $this->consumptionRelationRepo->find($consumptionRelation->id);
        $this->assertModelData($fakeConsumption_relation, $dbConsumption_relation->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_consumption_relation()
    {
        $consumptionRelation = Consumption_relation::factory()->create();

        $resp = $this->consumptionRelationRepo->delete($consumptionRelation->id);

        $this->assertTrue($resp);
        $this->assertNull(Consumption_relation::find($consumptionRelation->id), 'Consumption_relation should not exist in DB');
    }
}
