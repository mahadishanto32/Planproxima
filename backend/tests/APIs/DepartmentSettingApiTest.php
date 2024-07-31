<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DepartmentSetting;

class DepartmentSettingApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_department_setting()
    {
        $departmentSetting = DepartmentSetting::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/department_settings', $departmentSetting
        );

        $this->assertApiResponse($departmentSetting);
    }

    /**
     * @test
     */
    public function test_read_department_setting()
    {
        $departmentSetting = DepartmentSetting::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/department_settings/'.$departmentSetting->id
        );

        $this->assertApiResponse($departmentSetting->toArray());
    }

    /**
     * @test
     */
    public function test_update_department_setting()
    {
        $departmentSetting = DepartmentSetting::factory()->create();
        $editedDepartmentSetting = DepartmentSetting::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/department_settings/'.$departmentSetting->id,
            $editedDepartmentSetting
        );

        $this->assertApiResponse($editedDepartmentSetting);
    }

    /**
     * @test
     */
    public function test_delete_department_setting()
    {
        $departmentSetting = DepartmentSetting::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/department_settings/'.$departmentSetting->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/department_settings/'.$departmentSetting->id
        );

        $this->response->assertStatus(404);
    }
}
