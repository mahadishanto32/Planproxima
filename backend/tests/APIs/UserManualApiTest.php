<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\UserManual;

class UserManualApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_user_manual()
    {
        $userManual = UserManual::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/user_manuals', $userManual
        );

        $this->assertApiResponse($userManual);
    }

    /**
     * @test
     */
    public function test_read_user_manual()
    {
        $userManual = UserManual::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/user_manuals/'.$userManual->id
        );

        $this->assertApiResponse($userManual->toArray());
    }

    /**
     * @test
     */
    public function test_update_user_manual()
    {
        $userManual = UserManual::factory()->create();
        $editedUserManual = UserManual::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/user_manuals/'.$userManual->id,
            $editedUserManual
        );

        $this->assertApiResponse($editedUserManual);
    }

    /**
     * @test
     */
    public function test_delete_user_manual()
    {
        $userManual = UserManual::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/user_manuals/'.$userManual->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/user_manuals/'.$userManual->id
        );

        $this->response->assertStatus(404);
    }
}
