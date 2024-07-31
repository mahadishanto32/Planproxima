<?php namespace Tests\Repositories;

use App\Models\DepartmentAssign;
use App\Repositories\DepartmentAssignRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DepartmentAssignRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DepartmentAssignRepository
     */
    protected $departmentAssignRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->departmentAssignRepo = \App::make(DepartmentAssignRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_department_assign()
    {
        $departmentAssign = DepartmentAssign::factory()->make()->toArray();

        $createdDepartmentAssign = $this->departmentAssignRepo->create($departmentAssign);

        $createdDepartmentAssign = $createdDepartmentAssign->toArray();
        $this->assertArrayHasKey('id', $createdDepartmentAssign);
        $this->assertNotNull($createdDepartmentAssign['id'], 'Created DepartmentAssign must have id specified');
        $this->assertNotNull(DepartmentAssign::find($createdDepartmentAssign['id']), 'DepartmentAssign with given id must be in DB');
        $this->assertModelData($departmentAssign, $createdDepartmentAssign);
    }

    /**
     * @test read
     */
    public function test_read_department_assign()
    {
        $departmentAssign = DepartmentAssign::factory()->create();

        $dbDepartmentAssign = $this->departmentAssignRepo->find($departmentAssign->id);

        $dbDepartmentAssign = $dbDepartmentAssign->toArray();
        $this->assertModelData($departmentAssign->toArray(), $dbDepartmentAssign);
    }

    /**
     * @test update
     */
    public function test_update_department_assign()
    {
        $departmentAssign = DepartmentAssign::factory()->create();
        $fakeDepartmentAssign = DepartmentAssign::factory()->make()->toArray();

        $updatedDepartmentAssign = $this->departmentAssignRepo->update($fakeDepartmentAssign, $departmentAssign->id);

        $this->assertModelData($fakeDepartmentAssign, $updatedDepartmentAssign->toArray());
        $dbDepartmentAssign = $this->departmentAssignRepo->find($departmentAssign->id);
        $this->assertModelData($fakeDepartmentAssign, $dbDepartmentAssign->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_department_assign()
    {
        $departmentAssign = DepartmentAssign::factory()->create();

        $resp = $this->departmentAssignRepo->delete($departmentAssign->id);

        $this->assertTrue($resp);
        $this->assertNull(DepartmentAssign::find($departmentAssign->id), 'DepartmentAssign should not exist in DB');
    }
}
