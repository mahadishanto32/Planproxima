<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MonthlyReportFile;

class MonthlyReportFileApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_monthly_report_file()
    {
        $monthlyReportFile = MonthlyReportFile::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/monthly_report_files', $monthlyReportFile
        );

        $this->assertApiResponse($monthlyReportFile);
    }

    /**
     * @test
     */
    public function test_read_monthly_report_file()
    {
        $monthlyReportFile = MonthlyReportFile::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/monthly_report_files/'.$monthlyReportFile->id
        );

        $this->assertApiResponse($monthlyReportFile->toArray());
    }

    /**
     * @test
     */
    public function test_update_monthly_report_file()
    {
        $monthlyReportFile = MonthlyReportFile::factory()->create();
        $editedMonthlyReportFile = MonthlyReportFile::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/monthly_report_files/'.$monthlyReportFile->id,
            $editedMonthlyReportFile
        );

        $this->assertApiResponse($editedMonthlyReportFile);
    }

    /**
     * @test
     */
    public function test_delete_monthly_report_file()
    {
        $monthlyReportFile = MonthlyReportFile::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/monthly_report_files/'.$monthlyReportFile->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/monthly_report_files/'.$monthlyReportFile->id
        );

        $this->response->assertStatus(404);
    }
}
