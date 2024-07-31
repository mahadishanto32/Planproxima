<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DepartmentCCmail;

class DepartmentCCmailApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_department_c_cmail()
    {
        $departmentCCmail = DepartmentCCmail::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/department_c_cmails', $departmentCCmail
        );

        $this->assertApiResponse($departmentCCmail);
    }

    /**
     * @test
     */
    public function test_read_department_c_cmail()
    {
        $departmentCCmail = DepartmentCCmail::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/department_c_cmails/'.$departmentCCmail->id
        );

        $this->assertApiResponse($departmentCCmail->toArray());
    }

    /**
     * @test
     */
    public function test_update_department_c_cmail()
    {
        $departmentCCmail = DepartmentCCmail::factory()->create();
        $editedDepartmentCCmail = DepartmentCCmail::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/department_c_cmails/'.$departmentCCmail->id,
            $editedDepartmentCCmail
        );

        $this->assertApiResponse($editedDepartmentCCmail);
    }

    /**
     * @test
     */
    public function test_delete_department_c_cmail()
    {
        $departmentCCmail = DepartmentCCmail::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/department_c_cmails/'.$departmentCCmail->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/department_c_cmails/'.$departmentCCmail->id
        );

        $this->response->assertStatus(404);
    }
}
