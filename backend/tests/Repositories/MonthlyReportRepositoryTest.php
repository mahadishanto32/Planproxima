<?php namespace Tests\Repositories;

use App\Models\MonthlyReport;
use App\Repositories\MonthlyReportRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MonthlyReportRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MonthlyReportRepository
     */
    protected $monthlyReportRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->monthlyReportRepo = \App::make(MonthlyReportRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_monthly_report()
    {
        $monthlyReport = MonthlyReport::factory()->make()->toArray();

        $createdMonthlyReport = $this->monthlyReportRepo->create($monthlyReport);

        $createdMonthlyReport = $createdMonthlyReport->toArray();
        $this->assertArrayHasKey('id', $createdMonthlyReport);
        $this->assertNotNull($createdMonthlyReport['id'], 'Created MonthlyReport must have id specified');
        $this->assertNotNull(MonthlyReport::find($createdMonthlyReport['id']), 'MonthlyReport with given id must be in DB');
        $this->assertModelData($monthlyReport, $createdMonthlyReport);
    }

    /**
     * @test read
     */
    public function test_read_monthly_report()
    {
        $monthlyReport = MonthlyReport::factory()->create();

        $dbMonthlyReport = $this->monthlyReportRepo->find($monthlyReport->id);

        $dbMonthlyReport = $dbMonthlyReport->toArray();
        $this->assertModelData($monthlyReport->toArray(), $dbMonthlyReport);
    }

    /**
     * @test update
     */
    public function test_update_monthly_report()
    {
        $monthlyReport = MonthlyReport::factory()->create();
        $fakeMonthlyReport = MonthlyReport::factory()->make()->toArray();

        $updatedMonthlyReport = $this->monthlyReportRepo->update($fakeMonthlyReport, $monthlyReport->id);

        $this->assertModelData($fakeMonthlyReport, $updatedMonthlyReport->toArray());
        $dbMonthlyReport = $this->monthlyReportRepo->find($monthlyReport->id);
        $this->assertModelData($fakeMonthlyReport, $dbMonthlyReport->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_monthly_report()
    {
        $monthlyReport = MonthlyReport::factory()->create();

        $resp = $this->monthlyReportRepo->delete($monthlyReport->id);

        $this->assertTrue($resp);
        $this->assertNull(MonthlyReport::find($monthlyReport->id), 'MonthlyReport should not exist in DB');
    }
}
