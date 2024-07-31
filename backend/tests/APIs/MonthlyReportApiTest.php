<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MonthlyReport;

class MonthlyReportApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_monthly_report()
    {
        $monthlyReport = MonthlyReport::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/monthly_reports', $monthlyReport
        );

        $this->assertApiResponse($monthlyReport);
    }

    /**
     * @test
     */
    public function test_read_monthly_report()
    {
        $monthlyReport = MonthlyReport::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/monthly_reports/'.$monthlyReport->id
        );

        $this->assertApiResponse($monthlyReport->toArray());
    }

    /**
     * @test
     */
    public function test_update_monthly_report()
    {
        $monthlyReport = MonthlyReport::factory()->create();
        $editedMonthlyReport = MonthlyReport::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/monthly_reports/'.$monthlyReport->id,
            $editedMonthlyReport
        );

        $this->assertApiResponse($editedMonthlyReport);
    }

    /**
     * @test
     */
    public function test_delete_monthly_report()
    {
        $monthlyReport = MonthlyReport::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/monthly_reports/'.$monthlyReport->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/monthly_reports/'.$monthlyReport->id
        );

        $this->response->assertStatus(404);
    }
}
