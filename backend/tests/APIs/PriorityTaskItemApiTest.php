<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PriorityTaskItem;

class PriorityTaskItemApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_priority_task_item()
    {
        $priorityTaskItem = PriorityTaskItem::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/priority_task_items', $priorityTaskItem
        );

        $this->assertApiResponse($priorityTaskItem);
    }

    /**
     * @test
     */
    public function test_read_priority_task_item()
    {
        $priorityTaskItem = PriorityTaskItem::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/priority_task_items/'.$priorityTaskItem->id
        );

        $this->assertApiResponse($priorityTaskItem->toArray());
    }

    /**
     * @test
     */
    public function test_update_priority_task_item()
    {
        $priorityTaskItem = PriorityTaskItem::factory()->create();
        $editedPriorityTaskItem = PriorityTaskItem::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/priority_task_items/'.$priorityTaskItem->id,
            $editedPriorityTaskItem
        );

        $this->assertApiResponse($editedPriorityTaskItem);
    }

    /**
     * @test
     */
    public function test_delete_priority_task_item()
    {
        $priorityTaskItem = PriorityTaskItem::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/priority_task_items/'.$priorityTaskItem->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/priority_task_items/'.$priorityTaskItem->id
        );

        $this->response->assertStatus(404);
    }
}
