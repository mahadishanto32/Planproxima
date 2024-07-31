<?php namespace Tests\Repositories;

use App\Models\TourEntrieObjective;
use App\Repositories\TourEntrieObjectiveRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TourEntrieObjectiveRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TourEntrieObjectiveRepository
     */
    protected $tourEntrieObjectiveRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->tourEntrieObjectiveRepo = \App::make(TourEntrieObjectiveRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_tour_entrie_objective()
    {
        $tourEntrieObjective = TourEntrieObjective::factory()->make()->toArray();

        $createdTourEntrieObjective = $this->tourEntrieObjectiveRepo->create($tourEntrieObjective);

        $createdTourEntrieObjective = $createdTourEntrieObjective->toArray();
        $this->assertArrayHasKey('id', $createdTourEntrieObjective);
        $this->assertNotNull($createdTourEntrieObjective['id'], 'Created TourEntrieObjective must have id specified');
        $this->assertNotNull(TourEntrieObjective::find($createdTourEntrieObjective['id']), 'TourEntrieObjective with given id must be in DB');
        $this->assertModelData($tourEntrieObjective, $createdTourEntrieObjective);
    }

    /**
     * @test read
     */
    public function test_read_tour_entrie_objective()
    {
        $tourEntrieObjective = TourEntrieObjective::factory()->create();

        $dbTourEntrieObjective = $this->tourEntrieObjectiveRepo->find($tourEntrieObjective->id);

        $dbTourEntrieObjective = $dbTourEntrieObjective->toArray();
        $this->assertModelData($tourEntrieObjective->toArray(), $dbTourEntrieObjective);
    }

    /**
     * @test update
     */
    public function test_update_tour_entrie_objective()
    {
        $tourEntrieObjective = TourEntrieObjective::factory()->create();
        $fakeTourEntrieObjective = TourEntrieObjective::factory()->make()->toArray();

        $updatedTourEntrieObjective = $this->tourEntrieObjectiveRepo->update($fakeTourEntrieObjective, $tourEntrieObjective->id);

        $this->assertModelData($fakeTourEntrieObjective, $updatedTourEntrieObjective->toArray());
        $dbTourEntrieObjective = $this->tourEntrieObjectiveRepo->find($tourEntrieObjective->id);
        $this->assertModelData($fakeTourEntrieObjective, $dbTourEntrieObjective->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_tour_entrie_objective()
    {
        $tourEntrieObjective = TourEntrieObjective::factory()->create();

        $resp = $this->tourEntrieObjectiveRepo->delete($tourEntrieObjective->id);

        $this->assertTrue($resp);
        $this->assertNull(TourEntrieObjective::find($tourEntrieObjective->id), 'TourEntrieObjective should not exist in DB');
    }
}
