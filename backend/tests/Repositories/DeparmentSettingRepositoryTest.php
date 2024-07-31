<?php namespace Tests\Repositories;

use App\Models\DeparmentSetting;
use App\Repositories\DeparmentSettingRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DeparmentSettingRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DeparmentSettingRepository
     */
    protected $deparmentSettingRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->deparmentSettingRepo = \App::make(DeparmentSettingRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_deparment_setting()
    {
        $deparmentSetting = DeparmentSetting::factory()->make()->toArray();

        $createdDeparmentSetting = $this->deparmentSettingRepo->create($deparmentSetting);

        $createdDeparmentSetting = $createdDeparmentSetting->toArray();
        $this->assertArrayHasKey('id', $createdDeparmentSetting);
        $this->assertNotNull($createdDeparmentSetting['id'], 'Created DeparmentSetting must have id specified');
        $this->assertNotNull(DeparmentSetting::find($createdDeparmentSetting['id']), 'DeparmentSetting with given id must be in DB');
        $this->assertModelData($deparmentSetting, $createdDeparmentSetting);
    }

    /**
     * @test read
     */
    public function test_read_deparment_setting()
    {
        $deparmentSetting = DeparmentSetting::factory()->create();

        $dbDeparmentSetting = $this->deparmentSettingRepo->find($deparmentSetting->id);

        $dbDeparmentSetting = $dbDeparmentSetting->toArray();
        $this->assertModelData($deparmentSetting->toArray(), $dbDeparmentSetting);
    }

    /**
     * @test update
     */
    public function test_update_deparment_setting()
    {
        $deparmentSetting = DeparmentSetting::factory()->create();
        $fakeDeparmentSetting = DeparmentSetting::factory()->make()->toArray();

        $updatedDeparmentSetting = $this->deparmentSettingRepo->update($fakeDeparmentSetting, $deparmentSetting->id);

        $this->assertModelData($fakeDeparmentSetting, $updatedDeparmentSetting->toArray());
        $dbDeparmentSetting = $this->deparmentSettingRepo->find($deparmentSetting->id);
        $this->assertModelData($fakeDeparmentSetting, $dbDeparmentSetting->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_deparment_setting()
    {
        $deparmentSetting = DeparmentSetting::factory()->create();

        $resp = $this->deparmentSettingRepo->delete($deparmentSetting->id);

        $this->assertTrue($resp);
        $this->assertNull(DeparmentSetting::find($deparmentSetting->id), 'DeparmentSetting should not exist in DB');
    }
}
