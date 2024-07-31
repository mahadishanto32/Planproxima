<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Daily_schedule_comment;

class Daily_schedule_commentApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_daily_schedule_comment()
    {
        $dailyScheduleComment = Daily_schedule_comment::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/daily_schedule_comments', $dailyScheduleComment
        );

        $this->assertApiResponse($dailyScheduleComment);
    }

    /**
     * @test
     */
    public function test_read_daily_schedule_comment()
    {
        $dailyScheduleComment = Daily_schedule_comment::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/daily_schedule_comments/'.$dailyScheduleComment->id
        );

        $this->assertApiResponse($dailyScheduleComment->toArray());
    }

    /**
     * @test
     */
    public function test_update_daily_schedule_comment()
    {
        $dailyScheduleComment = Daily_schedule_comment::factory()->create();
        $editedDaily_schedule_comment = Daily_schedule_comment::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/daily_schedule_comments/'.$dailyScheduleComment->id,
            $editedDaily_schedule_comment
        );

        $this->assertApiResponse($editedDaily_schedule_comment);
    }

    /**
     * @test
     */
    public function test_delete_daily_schedule_comment()
    {
        $dailyScheduleComment = Daily_schedule_comment::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/daily_schedule_comments/'.$dailyScheduleComment->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/daily_schedule_comments/'.$dailyScheduleComment->id
        );

        $this->response->assertStatus(404);
    }
}
