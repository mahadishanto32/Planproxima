<?php namespace Tests\Repositories;

use App\Models\DailyScheduleType;
use App\Repositories\DailyScheduleTypeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DailyScheduleTypeRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DailyScheduleTypeRepository
     */
    protected $dailyScheduleTypeRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->dailyScheduleTypeRepo = \App::make(DailyScheduleTypeRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_daily_schedule_type()
    {
        $dailyScheduleType = DailyScheduleType::factory()->make()->toArray();

        $createdDailyScheduleType = $this->dailyScheduleTypeRepo->create($dailyScheduleType);

        $createdDailyScheduleType = $createdDailyScheduleType->toArray();
        $this->assertArrayHasKey('id', $createdDailyScheduleType);
        $this->assertNotNull($createdDailyScheduleType['id'], 'Created DailyScheduleType must have id specified');
        $this->assertNotNull(DailyScheduleType::find($createdDailyScheduleType['id']), 'DailyScheduleType with given id must be in DB');
        $this->assertModelData($dailyScheduleType, $createdDailyScheduleType);
    }

    /**
     * @test read
     */
    public function test_read_daily_schedule_type()
    {
        $dailyScheduleType = DailyScheduleType::factory()->create();

        $dbDailyScheduleType = $this->dailyScheduleTypeRepo->find($dailyScheduleType->id);

        $dbDailyScheduleType = $dbDailyScheduleType->toArray();
        $this->assertModelData($dailyScheduleType->toArray(), $dbDailyScheduleType);
    }

    /**
     * @test update
     */
    public function test_update_daily_schedule_type()
    {
        $dailyScheduleType = DailyScheduleType::factory()->create();
        $fakeDailyScheduleType = DailyScheduleType::factory()->make()->toArray();

        $updatedDailyScheduleType = $this->dailyScheduleTypeRepo->update($fakeDailyScheduleType, $dailyScheduleType->id);

        $this->assertModelData($fakeDailyScheduleType, $updatedDailyScheduleType->toArray());
        $dbDailyScheduleType = $this->dailyScheduleTypeRepo->find($dailyScheduleType->id);
        $this->assertModelData($fakeDailyScheduleType, $dbDailyScheduleType->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_daily_schedule_type()
    {
        $dailyScheduleType = DailyScheduleType::factory()->create();

        $resp = $this->dailyScheduleTypeRepo->delete($dailyScheduleType->id);

        $this->assertTrue($resp);
        $this->assertNull(DailyScheduleType::find($dailyScheduleType->id), 'DailyScheduleType should not exist in DB');
    }
}
