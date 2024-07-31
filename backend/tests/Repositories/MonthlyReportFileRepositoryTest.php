<?php namespace Tests\Repositories;

use App\Models\MonthlyReportFile;
use App\Repositories\MonthlyReportFileRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MonthlyReportFileRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MonthlyReportFileRepository
     */
    protected $monthlyReportFileRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->monthlyReportFileRepo = \App::make(MonthlyReportFileRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_monthly_report_file()
    {
        $monthlyReportFile = MonthlyReportFile::factory()->make()->toArray();

        $createdMonthlyReportFile = $this->monthlyReportFileRepo->create($monthlyReportFile);

        $createdMonthlyReportFile = $createdMonthlyReportFile->toArray();
        $this->assertArrayHasKey('id', $createdMonthlyReportFile);
        $this->assertNotNull($createdMonthlyReportFile['id'], 'Created MonthlyReportFile must have id specified');
        $this->assertNotNull(MonthlyReportFile::find($createdMonthlyReportFile['id']), 'MonthlyReportFile with given id must be in DB');
        $this->assertModelData($monthlyReportFile, $createdMonthlyReportFile);
    }

    /**
     * @test read
     */
    public function test_read_monthly_report_file()
    {
        $monthlyReportFile = MonthlyReportFile::factory()->create();

        $dbMonthlyReportFile = $this->monthlyReportFileRepo->find($monthlyReportFile->id);

        $dbMonthlyReportFile = $dbMonthlyReportFile->toArray();
        $this->assertModelData($monthlyReportFile->toArray(), $dbMonthlyReportFile);
    }

    /**
     * @test update
     */
    public function test_update_monthly_report_file()
    {
        $monthlyReportFile = MonthlyReportFile::factory()->create();
        $fakeMonthlyReportFile = MonthlyReportFile::factory()->make()->toArray();

        $updatedMonthlyReportFile = $this->monthlyReportFileRepo->update($fakeMonthlyReportFile, $monthlyReportFile->id);

        $this->assertModelData($fakeMonthlyReportFile, $updatedMonthlyReportFile->toArray());
        $dbMonthlyReportFile = $this->monthlyReportFileRepo->find($monthlyReportFile->id);
        $this->assertModelData($fakeMonthlyReportFile, $dbMonthlyReportFile->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_monthly_report_file()
    {
        $monthlyReportFile = MonthlyReportFile::factory()->create();

        $resp = $this->monthlyReportFileRepo->delete($monthlyReportFile->id);

        $this->assertTrue($resp);
        $this->assertNull(MonthlyReportFile::find($monthlyReportFile->id), 'MonthlyReportFile should not exist in DB');
    }
}
