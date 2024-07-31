<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TourEntry;

class TourEntryApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_tour_entry()
    {
        $tourEntry = TourEntry::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/tour_entries', $tourEntry
        );

        $this->assertApiResponse($tourEntry);
    }

    /**
     * @test
     */
    public function test_read_tour_entry()
    {
        $tourEntry = TourEntry::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/tour_entries/'.$tourEntry->id
        );

        $this->assertApiResponse($tourEntry->toArray());
    }

    /**
     * @test
     */
    public function test_update_tour_entry()
    {
        $tourEntry = TourEntry::factory()->create();
        $editedTourEntry = TourEntry::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/tour_entries/'.$tourEntry->id,
            $editedTourEntry
        );

        $this->assertApiResponse($editedTourEntry);
    }

    /**
     * @test
     */
    public function test_delete_tour_entry()
    {
        $tourEntry = TourEntry::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/tour_entries/'.$tourEntry->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/tour_entries/'.$tourEntry->id
        );

        $this->response->assertStatus(404);
    }
}
