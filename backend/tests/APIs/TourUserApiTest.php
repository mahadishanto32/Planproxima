<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TourUser;

class TourUserApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_tour_user()
    {
        $tourUser = TourUser::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/tour_users', $tourUser
        );

        $this->assertApiResponse($tourUser);
    }

    /**
     * @test
     */
    public function test_read_tour_user()
    {
        $tourUser = TourUser::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/tour_users/'.$tourUser->id
        );

        $this->assertApiResponse($tourUser->toArray());
    }

    /**
     * @test
     */
    public function test_update_tour_user()
    {
        $tourUser = TourUser::factory()->create();
        $editedTourUser = TourUser::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/tour_users/'.$tourUser->id,
            $editedTourUser
        );

        $this->assertApiResponse($editedTourUser);
    }

    /**
     * @test
     */
    public function test_delete_tour_user()
    {
        $tourUser = TourUser::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/tour_users/'.$tourUser->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/tour_users/'.$tourUser->id
        );

        $this->response->assertStatus(404);
    }
}
