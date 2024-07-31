<?php namespace Tests\Repositories;

use App\Models\DailyScheduleItem;
use App\Repositories\DailyScheduleItemRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DailyScheduleItemRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DailyScheduleItemRepository
     */
    protected $dailyScheduleItemRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->dailyScheduleItemRepo = \App::make(DailyScheduleItemRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_daily_schedule_item()
    {
        $dailyScheduleItem = DailyScheduleItem::factory()->make()->toArray();

        $createdDailyScheduleItem = $this->dailyScheduleItemRepo->create($dailyScheduleItem);

        $createdDailyScheduleItem = $createdDailyScheduleItem->toArray();
        $this->assertArrayHasKey('id', $createdDailyScheduleItem);
        $this->assertNotNull($createdDailyScheduleItem['id'], 'Created DailyScheduleItem must have id specified');
        $this->assertNotNull(DailyScheduleItem::find($createdDailyScheduleItem['id']), 'DailyScheduleItem with given id must be in DB');
        $this->assertModelData($dailyScheduleItem, $createdDailyScheduleItem);
    }

    /**
     * @test read
     */
    public function test_read_daily_schedule_item()
    {
        $dailyScheduleItem = DailyScheduleItem::factory()->create();

        $dbDailyScheduleItem = $this->dailyScheduleItemRepo->find($dailyScheduleItem->id);

        $dbDailyScheduleItem = $dbDailyScheduleItem->toArray();
        $this->assertModelData($dailyScheduleItem->toArray(), $dbDailyScheduleItem);
    }

    /**
     * @test update
     */
    public function test_update_daily_schedule_item()
    {
        $dailyScheduleItem = DailyScheduleItem::factory()->create();
        $fakeDailyScheduleItem = DailyScheduleItem::factory()->make()->toArray();

        $updatedDailyScheduleItem = $this->dailyScheduleItemRepo->update($fakeDailyScheduleItem, $dailyScheduleItem->id);

        $this->assertModelData($fakeDailyScheduleItem, $updatedDailyScheduleItem->toArray());
        $dbDailyScheduleItem = $this->dailyScheduleItemRepo->find($dailyScheduleItem->id);
        $this->assertModelData($fakeDailyScheduleItem, $dbDailyScheduleItem->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_daily_schedule_item()
    {
        $dailyScheduleItem = DailyScheduleItem::factory()->create();

        $resp = $this->dailyScheduleItemRepo->delete($dailyScheduleItem->id);

        $this->assertTrue($resp);
        $this->assertNull(DailyScheduleItem::find($dailyScheduleItem->id), 'DailyScheduleItem should not exist in DB');
    }
}
