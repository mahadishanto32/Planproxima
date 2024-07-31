<?php namespace Tests\Repositories;

use App\Models\Production_emp;
use App\Repositories\Production_empRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class Production_empRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var Production_empRepository
     */
    protected $productionEmpRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->productionEmpRepo = \App::make(Production_empRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_production_emp()
    {
        $productionEmp = Production_emp::factory()->make()->toArray();

        $createdProduction_emp = $this->productionEmpRepo->create($productionEmp);

        $createdProduction_emp = $createdProduction_emp->toArray();
        $this->assertArrayHasKey('id', $createdProduction_emp);
        $this->assertNotNull($createdProduction_emp['id'], 'Created Production_emp must have id specified');
        $this->assertNotNull(Production_emp::find($createdProduction_emp['id']), 'Production_emp with given id must be in DB');
        $this->assertModelData($productionEmp, $createdProduction_emp);
    }

    /**
     * @test read
     */
    public function test_read_production_emp()
    {
        $productionEmp = Production_emp::factory()->create();

        $dbProduction_emp = $this->productionEmpRepo->find($productionEmp->id);

        $dbProduction_emp = $dbProduction_emp->toArray();
        $this->assertModelData($productionEmp->toArray(), $dbProduction_emp);
    }

    /**
     * @test update
     */
    public function test_update_production_emp()
    {
        $productionEmp = Production_emp::factory()->create();
        $fakeProduction_emp = Production_emp::factory()->make()->toArray();

        $updatedProduction_emp = $this->productionEmpRepo->update($fakeProduction_emp, $productionEmp->id);

        $this->assertModelData($fakeProduction_emp, $updatedProduction_emp->toArray());
        $dbProduction_emp = $this->productionEmpRepo->find($productionEmp->id);
        $this->assertModelData($fakeProduction_emp, $dbProduction_emp->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_production_emp()
    {
        $productionEmp = Production_emp::factory()->create();

        $resp = $this->productionEmpRepo->delete($productionEmp->id);

        $this->assertTrue($resp);
        $this->assertNull(Production_emp::find($productionEmp->id), 'Production_emp should not exist in DB');
    }
}
