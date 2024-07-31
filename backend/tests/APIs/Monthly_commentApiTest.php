<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Monthly_comment;

class Monthly_commentApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_monthly_comment()
    {
        $monthlyComment = Monthly_comment::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/monthly_comments', $monthlyComment
        );

        $this->assertApiResponse($monthlyComment);
    }

    /**
     * @test
     */
    public function test_read_monthly_comment()
    {
        $monthlyComment = Monthly_comment::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/monthly_comments/'.$monthlyComment->id
        );

        $this->assertApiResponse($monthlyComment->toArray());
    }

    /**
     * @test
     */
    public function test_update_monthly_comment()
    {
        $monthlyComment = Monthly_comment::factory()->create();
        $editedMonthly_comment = Monthly_comment::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/monthly_comments/'.$monthlyComment->id,
            $editedMonthly_comment
        );

        $this->assertApiResponse($editedMonthly_comment);
    }

    /**
     * @test
     */
    public function test_delete_monthly_comment()
    {
        $monthlyComment = Monthly_comment::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/monthly_comments/'.$monthlyComment->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/monthly_comments/'.$monthlyComment->id
        );

        $this->response->assertStatus(404);
    }
}
