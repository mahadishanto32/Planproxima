<?php namespace Tests\Repositories;

use App\Models\TourUser;
use App\Repositories\TourUserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TourUserRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TourUserRepository
     */
    protected $tourUserRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->tourUserRepo = \App::make(TourUserRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_tour_user()
    {
        $tourUser = TourUser::factory()->make()->toArray();

        $createdTourUser = $this->tourUserRepo->create($tourUser);

        $createdTourUser = $createdTourUser->toArray();
        $this->assertArrayHasKey('id', $createdTourUser);
        $this->assertNotNull($createdTourUser['id'], 'Created TourUser must have id specified');
        $this->assertNotNull(TourUser::find($createdTourUser['id']), 'TourUser with given id must be in DB');
        $this->assertModelData($tourUser, $createdTourUser);
    }

    /**
     * @test read
     */
    public function test_read_tour_user()
    {
        $tourUser = TourUser::factory()->create();

        $dbTourUser = $this->tourUserRepo->find($tourUser->id);

        $dbTourUser = $dbTourUser->toArray();
        $this->assertModelData($tourUser->toArray(), $dbTourUser);
    }

    /**
     * @test update
     */
    public function test_update_tour_user()
    {
        $tourUser = TourUser::factory()->create();
        $fakeTourUser = TourUser::factory()->make()->toArray();

        $updatedTourUser = $this->tourUserRepo->update($fakeTourUser, $tourUser->id);

        $this->assertModelData($fakeTourUser, $updatedTourUser->toArray());
        $dbTourUser = $this->tourUserRepo->find($tourUser->id);
        $this->assertModelData($fakeTourUser, $dbTourUser->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_tour_user()
    {
        $tourUser = TourUser::factory()->create();

        $resp = $this->tourUserRepo->delete($tourUser->id);

        $this->assertTrue($resp);
        $this->assertNull(TourUser::find($tourUser->id), 'TourUser should not exist in DB');
    }
}
