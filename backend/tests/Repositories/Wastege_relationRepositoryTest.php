<?php namespace Tests\Repositories;

use App\Models\Wastege_relation;
use App\Repositories\Wastege_relationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class Wastege_relationRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var Wastege_relationRepository
     */
    protected $wastegeRelationRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->wastegeRelationRepo = \App::make(Wastege_relationRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_wastege_relation()
    {
        $wastegeRelation = Wastege_relation::factory()->make()->toArray();

        $createdWastege_relation = $this->wastegeRelationRepo->create($wastegeRelation);

        $createdWastege_relation = $createdWastege_relation->toArray();
        $this->assertArrayHasKey('id', $createdWastege_relation);
        $this->assertNotNull($createdWastege_relation['id'], 'Created Wastege_relation must have id specified');
        $this->assertNotNull(Wastege_relation::find($createdWastege_relation['id']), 'Wastege_relation with given id must be in DB');
        $this->assertModelData($wastegeRelation, $createdWastege_relation);
    }

    /**
     * @test read
     */
    public function test_read_wastege_relation()
    {
        $wastegeRelation = Wastege_relation::factory()->create();

        $dbWastege_relation = $this->wastegeRelationRepo->find($wastegeRelation->id);

        $dbWastege_relation = $dbWastege_relation->toArray();
        $this->assertModelData($wastegeRelation->toArray(), $dbWastege_relation);
    }

    /**
     * @test update
     */
    public function test_update_wastege_relation()
    {
        $wastegeRelation = Wastege_relation::factory()->create();
        $fakeWastege_relation = Wastege_relation::factory()->make()->toArray();

        $updatedWastege_relation = $this->wastegeRelationRepo->update($fakeWastege_relation, $wastegeRelation->id);

        $this->assertModelData($fakeWastege_relation, $updatedWastege_relation->toArray());
        $dbWastege_relation = $this->wastegeRelationRepo->find($wastegeRelation->id);
        $this->assertModelData($fakeWastege_relation, $dbWastege_relation->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_wastege_relation()
    {
        $wastegeRelation = Wastege_relation::factory()->create();

        $resp = $this->wastegeRelationRepo->delete($wastegeRelation->id);

        $this->assertTrue($resp);
        $this->assertNull(Wastege_relation::find($wastegeRelation->id), 'Wastege_relation should not exist in DB');
    }
}
