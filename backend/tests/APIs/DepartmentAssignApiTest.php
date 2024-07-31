<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\DepartmentAssign;

class DepartmentAssignApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_department_assign()
    {
        $departmentAssign = DepartmentAssign::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/department_assigns', $departmentAssign
        );

        $this->assertApiResponse($departmentAssign);
    }

    /**
     * @test
     */
    public function test_read_department_assign()
    {
        $departmentAssign = DepartmentAssign::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/department_assigns/'.$departmentAssign->id
        );

        $this->assertApiResponse($departmentAssign->toArray());
    }

    /**
     * @test
     */
    public function test_update_department_assign()
    {
        $departmentAssign = DepartmentAssign::factory()->create();
        $editedDepartmentAssign = DepartmentAssign::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/department_assigns/'.$departmentAssign->id,
            $editedDepartmentAssign
        );

        $this->assertApiResponse($editedDepartmentAssign);
    }

    /**
     * @test
     */
    public function test_delete_department_assign()
    {
        $departmentAssign = DepartmentAssign::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/department_assigns/'.$departmentAssign->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/department_assigns/'.$departmentAssign->id
        );

        $this->response->assertStatus(404);
    }
}
