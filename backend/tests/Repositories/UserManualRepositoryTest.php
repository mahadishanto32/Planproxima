<?php namespace Tests\Repositories;

use App\Models\UserManual;
use App\Repositories\UserManualRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class UserManualRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var UserManualRepository
     */
    protected $userManualRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->userManualRepo = \App::make(UserManualRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_user_manual()
    {
        $userManual = UserManual::factory()->make()->toArray();

        $createdUserManual = $this->userManualRepo->create($userManual);

        $createdUserManual = $createdUserManual->toArray();
        $this->assertArrayHasKey('id', $createdUserManual);
        $this->assertNotNull($createdUserManual['id'], 'Created UserManual must have id specified');
        $this->assertNotNull(UserManual::find($createdUserManual['id']), 'UserManual with given id must be in DB');
        $this->assertModelData($userManual, $createdUserManual);
    }

    /**
     * @test read
     */
    public function test_read_user_manual()
    {
        $userManual = UserManual::factory()->create();

        $dbUserManual = $this->userManualRepo->find($userManual->id);

        $dbUserManual = $dbUserManual->toArray();
        $this->assertModelData($userManual->toArray(), $dbUserManual);
    }

    /**
     * @test update
     */
    public function test_update_user_manual()
    {
        $userManual = UserManual::factory()->create();
        $fakeUserManual = UserManual::factory()->make()->toArray();

        $updatedUserManual = $this->userManualRepo->update($fakeUserManual, $userManual->id);

        $this->assertModelData($fakeUserManual, $updatedUserManual->toArray());
        $dbUserManual = $this->userManualRepo->find($userManual->id);
        $this->assertModelData($fakeUserManual, $dbUserManual->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_user_manual()
    {
        $userManual = UserManual::factory()->create();

        $resp = $this->userManualRepo->delete($userManual->id);

        $this->assertTrue($resp);
        $this->assertNull(UserManual::find($userManual->id), 'UserManual should not exist in DB');
    }
}
