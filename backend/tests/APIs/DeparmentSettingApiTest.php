<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DeparmentSetting;

class DeparmentSettingApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_deparment_setting()
    {
        $deparmentSetting = DeparmentSetting::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/deparment_settings', $deparmentSetting
        );

        $this->assertApiResponse($deparmentSetting);
    }

    /**
     * @test
     */
    public function test_read_deparment_setting()
    {
        $deparmentSetting = DeparmentSetting::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/deparment_settings/'.$deparmentSetting->id
        );

        $this->assertApiResponse($deparmentSetting->toArray());
    }

    /**
     * @test
     */
    public function test_update_deparment_setting()
    {
        $deparmentSetting = DeparmentSetting::factory()->create();
        $editedDeparmentSetting = DeparmentSetting::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/deparment_settings/'.$deparmentSetting->id,
            $editedDeparmentSetting
        );

        $this->assertApiResponse($editedDeparmentSetting);
    }

    /**
     * @test
     */
    public function test_delete_deparment_setting()
    {
        $deparmentSetting = DeparmentSetting::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/deparment_settings/'.$deparmentSetting->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/deparment_settings/'.$deparmentSetting->id
        );

        $this->response->assertStatus(404);
    }
}
