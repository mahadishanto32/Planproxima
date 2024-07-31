<?php namespace Tests\Repositories;

use App\Models\projects;
use App\Repositories\projectsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class projectsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var projectsRepository
     */
    protected $projectsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->projectsRepo = \App::make(projectsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_projects()
    {
        $projects = projects::factory()->make()->toArray();

        $createdprojects = $this->projectsRepo->create($projects);

        $createdprojects = $createdprojects->toArray();
        $this->assertArrayHasKey('id', $createdprojects);
        $this->assertNotNull($createdprojects['id'], 'Created projects must have id specified');
        $this->assertNotNull(projects::find($createdprojects['id']), 'projects with given id must be in DB');
        $this->assertModelData($projects, $createdprojects);
    }

    /**
     * @test read
     */
    public function test_read_projects()
    {
        $projects = projects::factory()->create();

        $dbprojects = $this->projectsRepo->find($projects->id);

        $dbprojects = $dbprojects->toArray();
        $this->assertModelData($projects->toArray(), $dbprojects);
    }

    /**
     * @test update
     */
    public function test_update_projects()
    {
        $projects = projects::factory()->create();
        $fakeprojects = projects::factory()->make()->toArray();

        $updatedprojects = $this->projectsRepo->update($fakeprojects, $projects->id);

        $this->assertModelData($fakeprojects, $updatedprojects->toArray());
        $dbprojects = $this->projectsRepo->find($projects->id);
        $this->assertModelData($fakeprojects, $dbprojects->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_projects()
    {
        $projects = projects::factory()->create();

        $resp = $this->projectsRepo->delete($projects->id);

        $this->assertTrue($resp);
        $this->assertNull(projects::find($projects->id), 'projects should not exist in DB');
    }
}
