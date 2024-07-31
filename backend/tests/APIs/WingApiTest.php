<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Wing;

class WingApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_wing()
    {
        $wing = Wing::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/wings', $wing
        );

        $this->assertApiResponse($wing);
    }

    /**
     * @test
     */
    public function test_read_wing()
    {
        $wing = Wing::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/wings/'.$wing->id
        );

        $this->assertApiResponse($wing->toArray());
    }

    /**
     * @test
     */
    public function test_update_wing()
    {
        $wing = Wing::factory()->create();
        $editedWing = Wing::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/wings/'.$wing->id,
            $editedWing
        );

        $this->assertApiResponse($editedWing);
    }

    /**
     * @test
     */
    public function test_delete_wing()
    {
        $wing = Wing::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/wings/'.$wing->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/wings/'.$wing->id
        );

        $this->response->assertStatus(404);
    }
}
