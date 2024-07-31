<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\MonthlyDateRange;

class MonthlyDateRangeApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_monthly_date_range()
    {
        $monthlyDateRange = MonthlyDateRange::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/monthly_date_ranges', $monthlyDateRange
        );

        $this->assertApiResponse($monthlyDateRange);
    }

    /**
     * @test
     */
    public function test_read_monthly_date_range()
    {
        $monthlyDateRange = MonthlyDateRange::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/monthly_date_ranges/'.$monthlyDateRange->id
        );

        $this->assertApiResponse($monthlyDateRange->toArray());
    }

    /**
     * @test
     */
    public function test_update_monthly_date_range()
    {
        $monthlyDateRange = MonthlyDateRange::factory()->create();
        $editedMonthlyDateRange = MonthlyDateRange::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/monthly_date_ranges/'.$monthlyDateRange->id,
            $editedMonthlyDateRange
        );

        $this->assertApiResponse($editedMonthlyDateRange);
    }

    /**
     * @test
     */
    public function test_delete_monthly_date_range()
    {
        $monthlyDateRange = MonthlyDateRange::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/monthly_date_ranges/'.$monthlyDateRange->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/monthly_date_ranges/'.$monthlyDateRange->id
        );

        $this->response->assertStatus(404);
    }
}
