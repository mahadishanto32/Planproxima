<?php namespace Tests\Repositories;

use App\Models\DepartmentSetting;
use App\Repositories\DepartmentSettingRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DepartmentSettingRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DepartmentSettingRepository
     */
    protected $departmentSettingRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->departmentSettingRepo = \App::make(DepartmentSettingRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_department_setting()
    {
        $departmentSetting = DepartmentSetting::factory()->make()->toArray();

        $createdDepartmentSetting = $this->departmentSettingRepo->create($departmentSetting);

        $createdDepartmentSetting = $createdDepartmentSetting->toArray();
        $this->assertArrayHasKey('id', $createdDepartmentSetting);
        $this->assertNotNull($createdDepartmentSetting['id'], 'Created DepartmentSetting must have id specified');
        $this->assertNotNull(DepartmentSetting::find($createdDepartmentSetting['id']), 'DepartmentSetting with given id must be in DB');
        $this->assertModelData($departmentSetting, $createdDepartmentSetting);
    }

    /**
     * @test read
     */
    public function test_read_department_setting()
    {
        $departmentSetting = DepartmentSetting::factory()->create();

        $dbDepartmentSetting = $this->departmentSettingRepo->find($departmentSetting->id);

        $dbDepartmentSetting = $dbDepartmentSetting->toArray();
        $this->assertModelData($departmentSetting->toArray(), $dbDepartmentSetting);
    }

    /**
     * @test update
     */
    public function test_update_department_setting()
    {
        $departmentSetting = DepartmentSetting::factory()->create();
        $fakeDepartmentSetting = DepartmentSetting::factory()->make()->toArray();

        $updatedDepartmentSetting = $this->departmentSettingRepo->update($fakeDepartmentSetting, $departmentSetting->id);

        $this->assertModelData($fakeDepartmentSetting, $updatedDepartmentSetting->toArray());
        $dbDepartmentSetting = $this->departmentSettingRepo->find($departmentSetting->id);
        $this->assertModelData($fakeDepartmentSetting, $dbDepartmentSetting->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_department_setting()
    {
        $departmentSetting = DepartmentSetting::factory()->create();

        $resp = $this->departmentSettingRepo->delete($departmentSetting->id);

        $this->assertTrue($resp);
        $this->assertNull(DepartmentSetting::find($departmentSetting->id), 'DepartmentSetting should not exist in DB');
    }
}
