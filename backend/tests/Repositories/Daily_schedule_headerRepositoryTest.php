<?php namespace Tests\Repositories;

use App\Models\Daily_schedule_header;
use App\Repositories\Daily_schedule_headerRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class Daily_schedule_headerRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var Daily_schedule_headerRepository
     */
    protected $dailyScheduleHeaderRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->dailyScheduleHeaderRepo = \App::make(Daily_schedule_headerRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_daily_schedule_header()
    {
        $dailyScheduleHeader = Daily_schedule_header::factory()->make()->toArray();

        $createdDaily_schedule_header = $this->dailyScheduleHeaderRepo->create($dailyScheduleHeader);

        $createdDaily_schedule_header = $createdDaily_schedule_header->toArray();
        $this->assertArrayHasKey('id', $createdDaily_schedule_header);
        $this->assertNotNull($createdDaily_schedule_header['id'], 'Created Daily_schedule_header must have id specified');
        $this->assertNotNull(Daily_schedule_header::find($createdDaily_schedule_header['id']), 'Daily_schedule_header with given id must be in DB');
        $this->assertModelData($dailyScheduleHeader, $createdDaily_schedule_header);
    }

    /**
     * @test read
     */
    public function test_read_daily_schedule_header()
    {
        $dailyScheduleHeader = Daily_schedule_header::factory()->create();

        $dbDaily_schedule_header = $this->dailyScheduleHeaderRepo->find($dailyScheduleHeader->id);

        $dbDaily_schedule_header = $dbDaily_schedule_header->toArray();
        $this->assertModelData($dailyScheduleHeader->toArray(), $dbDaily_schedule_header);
    }

    /**
     * @test update
     */
    public function test_update_daily_schedule_header()
    {
        $dailyScheduleHeader = Daily_schedule_header::factory()->create();
        $fakeDaily_schedule_header = Daily_schedule_header::factory()->make()->toArray();

        $updatedDaily_schedule_header = $this->dailyScheduleHeaderRepo->update($fakeDaily_schedule_header, $dailyScheduleHeader->id);

        $this->assertModelData($fakeDaily_schedule_header, $updatedDaily_schedule_header->toArray());
        $dbDaily_schedule_header = $this->dailyScheduleHeaderRepo->find($dailyScheduleHeader->id);
        $this->assertModelData($fakeDaily_schedule_header, $dbDaily_schedule_header->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_daily_schedule_header()
    {
        $dailyScheduleHeader = Daily_schedule_header::factory()->create();

        $resp = $this->dailyScheduleHeaderRepo->delete($dailyScheduleHeader->id);

        $this->assertTrue($resp);
        $this->assertNull(Daily_schedule_header::find($dailyScheduleHeader->id), 'Daily_schedule_header should not exist in DB');
    }
}
