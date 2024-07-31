<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DailyScheduleItem;

class DailyScheduleItemApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_daily_schedule_item()
    {
        $dailyScheduleItem = DailyScheduleItem::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/daily_schedule_items', $dailyScheduleItem
        );

        $this->assertApiResponse($dailyScheduleItem);
    }

    /**
     * @test
     */
    public function test_read_daily_schedule_item()
    {
        $dailyScheduleItem = DailyScheduleItem::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/daily_schedule_items/'.$dailyScheduleItem->id
        );

        $this->assertApiResponse($dailyScheduleItem->toArray());
    }

    /**
     * @test
     */
    public function test_update_daily_schedule_item()
    {
        $dailyScheduleItem = DailyScheduleItem::factory()->create();
        $editedDailyScheduleItem = DailyScheduleItem::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/daily_schedule_items/'.$dailyScheduleItem->id,
            $editedDailyScheduleItem
        );

        $this->assertApiResponse($editedDailyScheduleItem);
    }

    /**
     * @test
     */
    public function test_delete_daily_schedule_item()
    {
        $dailyScheduleItem = DailyScheduleItem::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/daily_schedule_items/'.$dailyScheduleItem->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/daily_schedule_items/'.$dailyScheduleItem->id
        );

        $this->response->assertStatus(404);
    }
}
