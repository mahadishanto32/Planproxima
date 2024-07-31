<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\FollowUpDept;

class FollowUpDeptApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_follow_up_dept()
    {
        $followUpDept = FollowUpDept::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/follow_up_depts', $followUpDept
        );

        $this->assertApiResponse($followUpDept);
    }

    /**
     * @test
     */
    public function test_read_follow_up_dept()
    {
        $followUpDept = FollowUpDept::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/follow_up_depts/'.$followUpDept->id
        );

        $this->assertApiResponse($followUpDept->toArray());
    }

    /**
     * @test
     */
    public function test_update_follow_up_dept()
    {
        $followUpDept = FollowUpDept::factory()->create();
        $editedFollowUpDept = FollowUpDept::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/follow_up_depts/'.$followUpDept->id,
            $editedFollowUpDept
        );

        $this->assertApiResponse($editedFollowUpDept);
    }

    /**
     * @test
     */
    public function test_delete_follow_up_dept()
    {
        $followUpDept = FollowUpDept::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/follow_up_depts/'.$followUpDept->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/follow_up_depts/'.$followUpDept->id
        );

        $this->response->assertStatus(404);
    }
}
