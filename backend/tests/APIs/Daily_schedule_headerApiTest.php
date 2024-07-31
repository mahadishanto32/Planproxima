<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Daily_schedule_header;

class Daily_schedule_headerApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_daily_schedule_header()
    {
        $dailyScheduleHeader = Daily_schedule_header::factory()->make()->toArray();

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
        $dailyScheduleHeader = Daily_schedule_header::factory()->create();

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
        $dailyScheduleHeader = Daily_schedule_header::factory()->create();
        $editedDaily_schedule_header = Daily_schedule_header::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/daily_schedule_headers/'.$dailyScheduleHeader->id,
            $editedDaily_schedule_header
        );

        $this->assertApiResponse($editedDaily_schedule_header);
    }

    /**
     * @test
     */
    public function test_delete_daily_schedule_header()
    {
        $dailyScheduleHeader = Daily_schedule_header::factory()->create();

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
