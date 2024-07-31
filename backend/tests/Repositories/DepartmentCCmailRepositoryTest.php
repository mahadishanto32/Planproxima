<?php namespace Tests\Repositories;

use App\Models\DepartmentCCmail;
use App\Repositories\DepartmentCCmailRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DepartmentCCmailRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DepartmentCCmailRepository
     */
    protected $departmentCCmailRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->departmentCCmailRepo = \App::make(DepartmentCCmailRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_department_c_cmail()
    {
        $departmentCCmail = DepartmentCCmail::factory()->make()->toArray();

        $createdDepartmentCCmail = $this->departmentCCmailRepo->create($departmentCCmail);

        $createdDepartmentCCmail = $createdDepartmentCCmail->toArray();
        $this->assertArrayHasKey('id', $createdDepartmentCCmail);
        $this->assertNotNull($createdDepartmentCCmail['id'], 'Created DepartmentCCmail must have id specified');
        $this->assertNotNull(DepartmentCCmail::find($createdDepartmentCCmail['id']), 'DepartmentCCmail with given id must be in DB');
        $this->assertModelData($departmentCCmail, $createdDepartmentCCmail);
    }

    /**
     * @test read
     */
    public function test_read_department_c_cmail()
    {
        $departmentCCmail = DepartmentCCmail::factory()->create();

        $dbDepartmentCCmail = $this->departmentCCmailRepo->find($departmentCCmail->id);

        $dbDepartmentCCmail = $dbDepartmentCCmail->toArray();
        $this->assertModelData($departmentCCmail->toArray(), $dbDepartmentCCmail);
    }

    /**
     * @test update
     */
    public function test_update_department_c_cmail()
    {
        $departmentCCmail = DepartmentCCmail::factory()->create();
        $fakeDepartmentCCmail = DepartmentCCmail::factory()->make()->toArray();

        $updatedDepartmentCCmail = $this->departmentCCmailRepo->update($fakeDepartmentCCmail, $departmentCCmail->id);

        $this->assertModelData($fakeDepartmentCCmail, $updatedDepartmentCCmail->toArray());
        $dbDepartmentCCmail = $this->departmentCCmailRepo->find($departmentCCmail->id);
        $this->assertModelData($fakeDepartmentCCmail, $dbDepartmentCCmail->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_department_c_cmail()
    {
        $departmentCCmail = DepartmentCCmail::factory()->create();

        $resp = $this->departmentCCmailRepo->delete($departmentCCmail->id);

        $this->assertTrue($resp);
        $this->assertNull(DepartmentCCmail::find($departmentCCmail->id), 'DepartmentCCmail should not exist in DB');
    }
}
