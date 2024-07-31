<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TourEntrieObjective;

class TourEntrieObjectiveApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_tour_entrie_objective()
    {
        $tourEntrieObjective = TourEntrieObjective::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/tour_entrie_objectives', $tourEntrieObjective
        );

        $this->assertApiResponse($tourEntrieObjective);
    }

    /**
     * @test
     */
    public function test_read_tour_entrie_objective()
    {
        $tourEntrieObjective = TourEntrieObjective::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/tour_entrie_objectives/'.$tourEntrieObjective->id
        );

        $this->assertApiResponse($tourEntrieObjective->toArray());
    }

    /**
     * @test
     */
    public function test_update_tour_entrie_objective()
    {
        $tourEntrieObjective = TourEntrieObjective::factory()->create();
        $editedTourEntrieObjective = TourEntrieObjective::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/tour_entrie_objectives/'.$tourEntrieObjective->id,
            $editedTourEntrieObjective
        );

        $this->assertApiResponse($editedTourEntrieObjective);
    }

    /**
     * @test
     */
    public function test_delete_tour_entrie_objective()
    {
        $tourEntrieObjective = TourEntrieObjective::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/tour_entrie_objectives/'.$tourEntrieObjective->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/tour_entrie_objectives/'.$tourEntrieObjective->id
        );

        $this->response->assertStatus(404);
    }
}
