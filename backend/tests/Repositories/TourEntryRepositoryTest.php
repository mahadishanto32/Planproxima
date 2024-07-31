<?php namespace Tests\Repositories;

use App\Models\TourEntry;
use App\Repositories\TourEntryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TourEntryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TourEntryRepository
     */
    protected $tourEntryRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->tourEntryRepo = \App::make(TourEntryRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_tour_entry()
    {
        $tourEntry = TourEntry::factory()->make()->toArray();

        $createdTourEntry = $this->tourEntryRepo->create($tourEntry);

        $createdTourEntry = $createdTourEntry->toArray();
        $this->assertArrayHasKey('id', $createdTourEntry);
        $this->assertNotNull($createdTourEntry['id'], 'Created TourEntry must have id specified');
        $this->assertNotNull(TourEntry::find($createdTourEntry['id']), 'TourEntry with given id must be in DB');
        $this->assertModelData($tourEntry, $createdTourEntry);
    }

    /**
     * @test read
     */
    public function test_read_tour_entry()
    {
        $tourEntry = TourEntry::factory()->create();

        $dbTourEntry = $this->tourEntryRepo->find($tourEntry->id);

        $dbTourEntry = $dbTourEntry->toArray();
        $this->assertModelData($tourEntry->toArray(), $dbTourEntry);
    }

    /**
     * @test update
     */
    public function test_update_tour_entry()
    {
        $tourEntry = TourEntry::factory()->create();
        $fakeTourEntry = TourEntry::factory()->make()->toArray();

        $updatedTourEntry = $this->tourEntryRepo->update($fakeTourEntry, $tourEntry->id);

        $this->assertModelData($fakeTourEntry, $updatedTourEntry->toArray());
        $dbTourEntry = $this->tourEntryRepo->find($tourEntry->id);
        $this->assertModelData($fakeTourEntry, $dbTourEntry->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_tour_entry()
    {
        $tourEntry = TourEntry::factory()->create();

        $resp = $this->tourEntryRepo->delete($tourEntry->id);

        $this->assertTrue($resp);
        $this->assertNull(TourEntry::find($tourEntry->id), 'TourEntry should not exist in DB');
    }
}
