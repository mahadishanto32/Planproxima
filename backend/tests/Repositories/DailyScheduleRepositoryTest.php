<?php namespace Tests\Repositories;

use App\Models\DailySchedule;
use App\Repositories\DailyScheduleRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DailyScheduleRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DailyScheduleRepository
     */
    protected $dailyScheduleRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->dailyScheduleRepo = \App::make(DailyScheduleRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_daily_schedule()
    {
        $dailySchedule = DailySchedule::factory()->make()->toArray();

        $createdDailySchedule = $this->dailyScheduleRepo->create($dailySchedule);

        $createdDailySchedule = $createdDailySchedule->toArray();
        $this->assertArrayHasKey('id', $createdDailySchedule);
        $this->assertNotNull($createdDailySchedule['id'], 'Created DailySchedule must have id specified');
        $this->assertNotNull(DailySchedule::find($createdDailySchedule['id']), 'DailySchedule with given id must be in DB');
        $this->assertModelData($dailySchedule, $createdDailySchedule);
    }

    /**
     * @test read
     */
    public function test_read_daily_schedule()
    {
        $dailySchedule = DailySchedule::factory()->create();

        $dbDailySchedule = $this->dailyScheduleRepo->find($dailySchedule->id);

        $dbDailySchedule = $dbDailySchedule->toArray();
        $this->assertModelData($dailySchedule->toArray(), $dbDailySchedule);
    }

    /**
     * @test update
     */
    public function test_update_daily_schedule()
    {
        $dailySchedule = DailySchedule::factory()->create();
        $fakeDailySchedule = DailySchedule::factory()->make()->toArray();

        $updatedDailySchedule = $this->dailyScheduleRepo->update($fakeDailySchedule, $dailySchedule->id);

        $this->assertModelData($fakeDailySchedule, $updatedDailySchedule->toArray());
        $dbDailySchedule = $this->dailyScheduleRepo->find($dailySchedule->id);
        $this->assertModelData($fakeDailySchedule, $dbDailySchedule->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_daily_schedule()
    {
        $dailySchedule = DailySchedule::factory()->create();

        $resp = $this->dailyScheduleRepo->delete($dailySchedule->id);

        $this->assertTrue($resp);
        $this->assertNull(DailySchedule::find($dailySchedule->id), 'DailySchedule should not exist in DB');
    }
}
