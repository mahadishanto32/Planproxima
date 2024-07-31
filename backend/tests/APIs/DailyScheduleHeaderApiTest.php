<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DailyScheduleHeader;

class DailyScheduleHeaderApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_daily_schedule_header()
    {
        $dailyScheduleHeader = DailyScheduleHeader::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/daily_schedule_headers', $dailyScheduleHeader
        );

        $this->assertApiResponse($dailyScheduleHeader);
    }

    /**
     * @test
     */
    public function test_read_daily_schedule_header()
    {
        $dailyScheduleHeader = DailyScheduleHeader::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/daily_schedule_headers/'.$dailyScheduleHeader->id
        );

        $this->assertApiResponse($dailyScheduleHeader->toArray());
    }

    /**
     * @test
     */
    public function test_update_daily_schedule_header()
    {
        $dailyScheduleHeader = DailyScheduleHeader::factory()->create();
        $editedDailyScheduleHeader = DailyScheduleHeader::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/daily_schedule_headers/'.$dailyScheduleHeader->id,
            $editedDailyScheduleHeader
        );

        $this->assertApiResponse($editedDailyScheduleHeader);
    }

    /**
     * @test
     */
    public function test_delete_daily_schedule_header()
    {
        $dailyScheduleHeader = DailyScheduleHeader::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/daily_schedule_headers/'.$dailyScheduleHeader->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/daily_schedule_headers/'.$dailyScheduleHeader->id
        );

        $this->response->assertStatus(404);
    }
}
