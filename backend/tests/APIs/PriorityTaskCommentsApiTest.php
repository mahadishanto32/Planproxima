<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PriorityTaskComments;

class PriorityTaskCommentsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_priority_task_comments()
    {
        $priorityTaskComments = PriorityTaskComments::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/priority_task_comments', $priorityTaskComments
        );

        $this->assertApiResponse($priorityTaskComments);
    }

    /**
     * @test
     */
    public function test_read_priority_task_comments()
    {
        $priorityTaskComments = PriorityTaskComments::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/priority_task_comments/'.$priorityTaskComments->id
        );

        $this->assertApiResponse($priorityTaskComments->toArray());
    }

    /**
     * @test
     */
    public function test_update_priority_task_comments()
    {
        $priorityTaskComments = PriorityTaskComments::factory()->create();
        $editedPriorityTaskComments = PriorityTaskComments::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/priority_task_comments/'.$priorityTaskComments->id,
            $editedPriorityTaskComments
        );

        $this->assertApiResponse($editedPriorityTaskComments);
    }

    /**
     * @test
     */
    public function test_delete_priority_task_comments()
    {
        $priorityTaskComments = PriorityTaskComments::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/priority_task_comments/'.$priorityTaskComments->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/priority_task_comments/'.$priorityTaskComments->id
        );

        $this->response->assertStatus(404);
    }
}
