<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MOSAchievementPermission;

class MOSAchievementPermissionApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_m_o_s_achievement_permission()
    {
        $mOSAchievementPermission = MOSAchievementPermission::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/m_o_s_achievement_permissions', $mOSAchievementPermission
        );

        $this->assertApiResponse($mOSAchievementPermission);
    }

    /**
     * @test
     */
    public function test_read_m_o_s_achievement_permission()
    {
        $mOSAchievementPermission = MOSAchievementPermission::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/m_o_s_achievement_permissions/'.$mOSAchievementPermission->id
        );

        $this->assertApiResponse($mOSAchievementPermission->toArray());
    }

    /**
     * @test
     */
    public function test_update_m_o_s_achievement_permission()
    {
        $mOSAchievementPermission = MOSAchievementPermission::factory()->create();
        $editedMOSAchievementPermission = MOSAchievementPermission::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/m_o_s_achievement_permissions/'.$mOSAchievementPermission->id,
            $editedMOSAchievementPermission
        );

        $this->assertApiResponse($editedMOSAchievementPermission);
    }

    /**
     * @test
     */
    public function test_delete_m_o_s_achievement_permission()
    {
        $mOSAchievementPermission = MOSAchievementPermission::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/m_o_s_achievement_permissions/'.$mOSAchievementPermission->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/m_o_s_achievement_permissions/'.$mOSAchievementPermission->id
        );

        $this->response->assertStatus(404);
    }
}
