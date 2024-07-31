<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\follow_up;

class follow_upApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_follow_up()
    {
        $followUp = follow_up::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/follow_ups', $followUp
        );

        $this->assertApiResponse($followUp);
    }

    /**
     * @test
     */
    public function test_read_follow_up()
    {
        $followUp = follow_up::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/follow_ups/'.$followUp->id
        );

        $this->assertApiResponse($followUp->toArray());
    }

    /**
     * @test
     */
    public function test_update_follow_up()
    {
        $followUp = follow_up::factory()->create();
        $editedfollow_up = follow_up::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/follow_ups/'.$followUp->id,
            $editedfollow_up
        );

        $this->assertApiResponse($editedfollow_up);
    }

    /**
     * @test
     */
    public function test_delete_follow_up()
    {
        $followUp = follow_up::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/follow_ups/'.$followUp->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/follow_ups/'.$followUp->id
        );

        $this->response->assertStatus(404);
    }
}
