<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DailyScheduleType;

class DailyScheduleTypeApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_daily_schedule_type()
    {
        $dailyScheduleType = DailyScheduleType::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/daily_schedule_types', $dailyScheduleType
        );

        $this->assertApiResponse($dailyScheduleType);
    }

    /**
     * @test
     */
    public function test_read_daily_schedule_type()
    {
        $dailyScheduleType = DailyScheduleType::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/daily_schedule_types/'.$dailyScheduleType->id
        );

        $this->assertApiResponse($dailyScheduleType->toArray());
    }

    /**
     * @test
     */
    public function test_update_daily_schedule_type()
    {
        $dailyScheduleType = DailyScheduleType::factory()->create();
        $editedDailyScheduleType = DailyScheduleType::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/daily_schedule_types/'.$dailyScheduleType->id,
            $editedDailyScheduleType
        );

        $this->assertApiResponse($editedDailyScheduleType);
    }

    /**
     * @test
     */
    public function test_delete_daily_schedule_type()
    {
        $dailyScheduleType = DailyScheduleType::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/daily_schedule_types/'.$dailyScheduleType->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/daily_schedule_types/'.$dailyScheduleType->id
        );

        $this->response->assertStatus(404);
    }
}
