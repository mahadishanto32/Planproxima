<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Wastege_relation;

class Wastege_relationApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_wastege_relation()
    {
        $wastegeRelation = Wastege_relation::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/wastege_relations', $wastegeRelation
        );

        $this->assertApiResponse($wastegeRelation);
    }

    /**
     * @test
     */
    public function test_read_wastege_relation()
    {
        $wastegeRelation = Wastege_relation::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/wastege_relations/'.$wastegeRelation->id
        );

        $this->assertApiResponse($wastegeRelation->toArray());
    }

    /**
     * @test
     */
    public function test_update_wastege_relation()
    {
        $wastegeRelation = Wastege_relation::factory()->create();
        $editedWastege_relation = Wastege_relation::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/wastege_relations/'.$wastegeRelation->id,
            $editedWastege_relation
        );

        $this->assertApiResponse($editedWastege_relation);
    }

    /**
     * @test
     */
    public function test_delete_wastege_relation()
    {
        $wastegeRelation = Wastege_relation::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/wastege_relations/'.$wastegeRelation->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/wastege_relations/'.$wastegeRelation->id
        );

        $this->response->assertStatus(404);
    }
}
