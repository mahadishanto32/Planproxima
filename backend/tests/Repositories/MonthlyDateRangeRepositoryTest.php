<?php namespace Tests\Repositories;

use App\Models\MonthlyDateRange;
use App\Repositories\MonthlyDateRangeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MonthlyDateRangeRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MonthlyDateRangeRepository
     */
    protected $monthlyDateRangeRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->monthlyDateRangeRepo = \App::make(MonthlyDateRangeRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_monthly_date_range()
    {
        $monthlyDateRange = MonthlyDateRange::factory()->make()->toArray();

        $createdMonthlyDateRange = $this->monthlyDateRangeRepo->create($monthlyDateRange);

        $createdMonthlyDateRange = $createdMonthlyDateRange->toArray();
        $this->assertArrayHasKey('id', $createdMonthlyDateRange);
        $this->assertNotNull($createdMonthlyDateRange['id'], 'Created MonthlyDateRange must have id specified');
        $this->assertNotNull(MonthlyDateRange::find($createdMonthlyDateRange['id']), 'MonthlyDateRange with given id must be in DB');
        $this->assertModelData($monthlyDateRange, $createdMonthlyDateRange);
    }

    /**
     * @test read
     */
    public function test_read_monthly_date_range()
    {
        $monthlyDateRange = MonthlyDateRange::factory()->create();

        $dbMonthlyDateRange = $this->monthlyDateRangeRepo->find($monthlyDateRange->id);

        $dbMonthlyDateRange = $dbMonthlyDateRange->toArray();
        $this->assertModelData($monthlyDateRange->toArray(), $dbMonthlyDateRange);
    }

    /**
     * @test update
     */
    public function test_update_monthly_date_range()
    {
        $monthlyDateRange = MonthlyDateRange::factory()->create();
        $fakeMonthlyDateRange = MonthlyDateRange::factory()->make()->toArray();

        $updatedMonthlyDateRange = $this->monthlyDateRangeRepo->update($fakeMonthlyDateRange, $monthlyDateRange->id);

        $this->assertModelData($fakeMonthlyDateRange, $updatedMonthlyDateRange->toArray());
        $dbMonthlyDateRange = $this->monthlyDateRangeRepo->find($monthlyDateRange->id);
        $this->assertModelData($fakeMonthlyDateRange, $dbMonthlyDateRange->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_monthly_date_range()
    {
        $monthlyDateRange = MonthlyDateRange::factory()->create();

        $resp = $this->monthlyDateRangeRepo->delete($monthlyDateRange->id);

        $this->assertTrue($resp);
        $this->assertNull(MonthlyDateRange::find($monthlyDateRange->id), 'MonthlyDateRange should not exist in DB');
    }
}
