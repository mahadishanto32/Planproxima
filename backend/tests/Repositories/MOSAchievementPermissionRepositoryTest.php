<?php namespace Tests\Repositories;

use App\Models\MOSAchievementPermission;
use App\Repositories\MOSAchievementPermissionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MOSAchievementPermissionRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MOSAchievementPermissionRepository
     */
    protected $mOSAchievementPermissionRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->mOSAchievementPermissionRepo = \App::make(MOSAchievementPermissionRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_m_o_s_achievement_permission()
    {
        $mOSAchievementPermission = MOSAchievementPermission::factory()->make()->toArray();

        $createdMOSAchievementPermission = $this->mOSAchievementPermissionRepo->create($mOSAchievementPermission);

        $createdMOSAchievementPermission = $createdMOSAchievementPermission->toArray();
        $this->assertArrayHasKey('id', $createdMOSAchievementPermission);
        $this->assertNotNull($createdMOSAchievementPermission['id'], 'Created MOSAchievementPermission must have id specified');
        $this->assertNotNull(MOSAchievementPermission::find($createdMOSAchievementPermission['id']), 'MOSAchievementPermission with given id must be in DB');
        $this->assertModelData($mOSAchievementPermission, $createdMOSAchievementPermission);
    }

    /**
     * @test read
     */
    public function test_read_m_o_s_achievement_permission()
    {
        $mOSAchievementPermission = MOSAchievementPermission::factory()->create();

        $dbMOSAchievementPermission = $this->mOSAchievementPermissionRepo->find($mOSAchievementPermission->id);

        $dbMOSAchievementPermission = $dbMOSAchievementPermission->toArray();
        $this->assertModelData($mOSAchievementPermission->toArray(), $dbMOSAchievementPermission);
    }

    /**
     * @test update
     */
    public function test_update_m_o_s_achievement_permission()
    {
        $mOSAchievementPermission = MOSAchievementPermission::factory()->create();
        $fakeMOSAchievementPermission = MOSAchievementPermission::factory()->make()->toArray();

        $updatedMOSAchievementPermission = $this->mOSAchievementPermissionRepo->update($fakeMOSAchievementPermission, $mOSAchievementPermission->id);

        $this->assertModelData($fakeMOSAchievementPermission, $updatedMOSAchievementPermission->toArray());
        $dbMOSAchievementPermission = $this->mOSAchievementPermissionRepo->find($mOSAchievementPermission->id);
        $this->assertModelData($fakeMOSAchievementPermission, $dbMOSAchievementPermission->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_m_o_s_achievement_permission()
    {
        $mOSAchievementPermission = MOSAchievementPermission::factory()->create();

        $resp = $this->mOSAchievementPermissionRepo->delete($mOSAchievementPermission->id);

        $this->assertTrue($resp);
        $this->assertNull(MOSAchievementPermission::find($mOSAchievementPermission->id), 'MOSAchievementPermission should not exist in DB');
    }
}
