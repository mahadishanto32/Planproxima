<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PriorityTask;

class PriorityTaskApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_priority_task()
    {
        $priorityTask = PriorityTask::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/priority_tasks', $priorityTask
        );

        $this->assertApiResponse($priorityTask);
    }

    /**
     * @test
     */
    public function test_read_priority_task()
    {
        $priorityTask = PriorityTask::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/priority_tasks/'.$priorityTask->id
        );

        $this->assertApiResponse($priorityTask->toArray());
    }

    /**
     * @test
     */
    public function test_update_priority_task()
    {
        $priorityTask = PriorityTask::factory()->create();
        $editedPriorityTask = PriorityTask::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/priority_tasks/'.$priorityTask->id,
            $editedPriorityTask
        );

        $this->assertApiResponse($editedPriorityTask);
    }

    /**
     * @test
     */
    public function test_delete_priority_task()
    {
        $priorityTask = PriorityTask::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/priority_tasks/'.$priorityTask->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/priority_tasks/'.$priorityTask->id
        );

        $this->response->assertStatus(404);
    }
}
