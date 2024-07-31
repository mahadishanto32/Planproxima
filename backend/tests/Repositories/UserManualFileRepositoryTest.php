<?php namespace Tests\Repositories;

use App\Models\UserManualFile;
use App\Repositories\UserManualFileRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class UserManualFileRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var UserManualFileRepository
     */
    protected $userManualFileRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->userManualFileRepo = \App::make(UserManualFileRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_user_manual_file()
    {
        $userManualFile = UserManualFile::factory()->make()->toArray();

        $createdUserManualFile = $this->userManualFileRepo->create($userManualFile);

        $createdUserManualFile = $createdUserManualFile->toArray();
        $this->assertArrayHasKey('id', $createdUserManualFile);
        $this->assertNotNull($createdUserManualFile['id'], 'Created UserManualFile must have id specified');
        $this->assertNotNull(UserManualFile::find($createdUserManualFile['id']), 'UserManualFile with given id must be in DB');
        $this->assertModelData($userManualFile, $createdUserManualFile);
    }

    /**
     * @test read
     */
    public function test_read_user_manual_file()
    {
        $userManualFile = UserManualFile::factory()->create();

        $dbUserManualFile = $this->userManualFileRepo->find($userManualFile->id);

        $dbUserManualFile = $dbUserManualFile->toArray();
        $this->assertModelData($userManualFile->toArray(), $dbUserManualFile);
    }

    /**
     * @test update
     */
    public function test_update_user_manual_file()
    {
        $userManualFile = UserManualFile::factory()->create();
        $fakeUserManualFile = UserManualFile::factory()->make()->toArray();

        $updatedUserManualFile = $this->userManualFileRepo->update($fakeUserManualFile, $userManualFile->id);

        $this->assertModelData($fakeUserManualFile, $updatedUserManualFile->toArray());
        $dbUserManualFile = $this->userManualFileRepo->find($userManualFile->id);
        $this->assertModelData($fakeUserManualFile, $dbUserManualFile->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_user_manual_file()
    {
        $userManualFile = UserManualFile::factory()->create();

        $resp = $this->userManualFileRepo->delete($userManualFile->id);

        $this->assertTrue($resp);
        $this->assertNull(UserManualFile::find($userManualFile->id), 'UserManualFile should not exist in DB');
    }
}
