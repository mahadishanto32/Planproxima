<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\UserManualFile;

class UserManualFileApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_user_manual_file()
    {
        $userManualFile = UserManualFile::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/user_manual_files', $userManualFile
        );

        $this->assertApiResponse($userManualFile);
    }

    /**
     * @test
     */
    public function test_read_user_manual_file()
    {
        $userManualFile = UserManualFile::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/user_manual_files/'.$userManualFile->id
        );

        $this->assertApiResponse($userManualFile->toArray());
    }

    /**
     * @test
     */
    public function test_update_user_manual_file()
    {
        $userManualFile = UserManualFile::factory()->create();
        $editedUserManualFile = UserManualFile::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/user_manual_files/'.$userManualFile->id,
            $editedUserManualFile
        );

        $this->assertApiResponse($editedUserManualFile);
    }

    /**
     * @test
     */
    public function test_delete_user_manual_file()
    {
        $userManualFile = UserManualFile::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/user_manual_files/'.$userManualFile->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/user_manual_files/'.$userManualFile->id
        );

        $this->response->assertStatus(404);
    }
}
