<?php namespace Tests\Repositories;

use App\Models\DailyScheduleHeader;
use App\Repositories\DailyScheduleHeaderRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DailyScheduleHeaderRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DailyScheduleHeaderRepository
     */
    protected $dailyScheduleHeaderRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->dailyScheduleHeaderRepo = \App::make(DailyScheduleHeaderRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_daily_schedule_header()
    {
        $dailyScheduleHeader = DailyScheduleHeader::factory()->make()->toArray();

        $createdDailyScheduleHeader = $this->dailyScheduleHeaderRepo->create($dailyScheduleHeader);

        $createdDailyScheduleHeader = $createdDailyScheduleHeader->toArray();
        $this->assertArrayHasKey('id', $createdDailyScheduleHeader);
        $this->assertNotNull($createdDailyScheduleHeader['id'], 'Created DailyScheduleHeader must have id specified');
        $this->assertNotNull(DailyScheduleHeader::find($createdDailyScheduleHeader['id']), 'DailyScheduleHeader with given id must be in DB');
        $this->assertModelData($dailyScheduleHeader, $createdDailyScheduleHeader);
    }

    /**
     * @test read
     */
    public function test_read_daily_schedule_header()
    {
        $dailyScheduleHeader = DailyScheduleHeader::factory()->create();

        $dbDailyScheduleHeader = $this->dailyScheduleHeaderRepo->find($dailyScheduleHeader->id);

        $dbDailyScheduleHeader = $dbDailyScheduleHeader->toArray();
        $this->assertModelData($dailyScheduleHeader->toArray(), $dbDailyScheduleHeader);
    }

    /**
     * @test update
     */
    public function test_update_daily_schedule_header()
    {
        $dailyScheduleHeader = DailyScheduleHeader::factory()->create();
        $fakeDailyScheduleHeader = DailyScheduleHeader::factory()->make()->toArray();

        $updatedDailyScheduleHeader = $this->dailyScheduleHeaderRepo->update($fakeDailyScheduleHeader, $dailyScheduleHeader->id);

        $this->assertModelData($fakeDailyScheduleHeader, $updatedDailyScheduleHeader->toArray());
        $dbDailyScheduleHeader = $this->dailyScheduleHeaderRepo->find($dailyScheduleHeader->id);
        $this->assertModelData($fakeDailyScheduleHeader, $dbDailyScheduleHeader->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_daily_schedule_header()
    {
        $dailyScheduleHeader = DailyScheduleHeader::factory()->create();

        $resp = $this->dailyScheduleHeaderRepo->delete($dailyScheduleHeader->id);

        $this->assertTrue($resp);
        $this->assertNull(DailyScheduleHeader::find($dailyScheduleHeader->id), 'DailyScheduleHeader should not exist in DB');
    }
}
