<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DailySchedule;

class DailyScheduleApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_daily_schedule()
    {
        $dailySchedule = DailySchedule::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/daily_schedules', $dailySchedule
        );

        $this->assertApiResponse($dailySchedule);
    }

    /**
     * @test
     */
    public function test_read_daily_schedule()
    {
        $dailySchedule = DailySchedule::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/daily_schedules/'.$dailySchedule->id
        );

        $this->assertApiResponse($dailySchedule->toArray());
    }

    /**
     * @test
     */
    public function test_update_daily_schedule()
    {
        $dailySchedule = DailySchedule::factory()->create();
        $editedDailySchedule = DailySchedule::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/daily_schedules/'.$dailySchedule->id,
            $editedDailySchedule
        );

        $this->assertApiResponse($editedDailySchedule);
    }

    /**
     * @test
     */
    public function test_delete_daily_schedule()
    {
        $dailySchedule = DailySchedule::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/daily_schedules/'.$dailySchedule->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/daily_schedules/'.$dailySchedule->id
        );

        $this->response->assertStatus(404);
    }
}
