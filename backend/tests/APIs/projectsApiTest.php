<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\projects;

class projectsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_projects()
    {
        $projects = projects::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/projects', $projects
        );

        $this->assertApiResponse($projects);
    }

    /**
     * @test
     */
    public function test_read_projects()
    {
        $projects = projects::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/projects/'.$projects->id
        );

        $this->assertApiResponse($projects->toArray());
    }

    /**
     * @test
     */
    public function test_update_projects()
    {
        $projects = projects::factory()->create();
        $editedprojects = projects::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/projects/'.$projects->id,
            $editedprojects
        );

        $this->assertApiResponse($editedprojects);
    }

    /**
     * @test
     */
    public function test_delete_projects()
    {
        $projects = projects::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/projects/'.$projects->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/projects/'.$projects->id
        );

        $this->response->assertStatus(404);
    }
}
