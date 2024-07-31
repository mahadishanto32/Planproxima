<?php namespace Tests\Repositories;

use App\Models\BuyerContactShare;
use App\Repositories\BuyerContactShareRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class BuyerContactShareRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var BuyerContactShareRepository
     */
    protected $buyerContactShareRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->buyerContactShareRepo = \App::make(BuyerContactShareRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_buyer_contact_share()
    {
        $buyerContactShare = BuyerContactShare::factory()->make()->toArray();

        $createdBuyerContactShare = $this->buyerContactShareRepo->create($buyerContactShare);

        $createdBuyerContactShare = $createdBuyerContactShare->toArray();
        $this->assertArrayHasKey('id', $createdBuyerContactShare);
        $this->assertNotNull($createdBuyerContactShare['id'], 'Created BuyerContactShare must have id specified');
        $this->assertNotNull(BuyerContactShare::find($createdBuyerContactShare['id']), 'BuyerContactShare with given id must be in DB');
        $this->assertModelData($buyerContactShare, $createdBuyerContactShare);
    }

    /**
     * @test read
     */
    public function test_read_buyer_contact_share()
    {
        $buyerContactShare = BuyerContactShare::factory()->create();

        $dbBuyerContactShare = $this->buyerContactShareRepo->find($buyerContactShare->id);

        $dbBuyerContactShare = $dbBuyerContactShare->toArray();
        $this->assertModelData($buyerContactShare->toArray(), $dbBuyerContactShare);
    }

    /**
     * @test update
     */
    public function test_update_buyer_contact_share()
    {
        $buyerContactShare = BuyerContactShare::factory()->create();
        $fakeBuyerContactShare = BuyerContactShare::factory()->make()->toArray();

        $updatedBuyerContactShare = $this->buyerContactShareRepo->update($fakeBuyerContactShare, $buyerContactShare->id);

        $this->assertModelData($fakeBuyerContactShare, $updatedBuyerContactShare->toArray());
        $dbBuyerContactShare = $this->buyerContactShareRepo->find($buyerContactShare->id);
        $this->assertModelData($fakeBuyerContactShare, $dbBuyerContactShare->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_buyer_contact_share()
    {
        $buyerContactShare = BuyerContactShare::factory()->create();

        $resp = $this->buyerContactShareRepo->delete($buyerContactShare->id);

        $this->assertTrue($resp);
        $this->assertNull(BuyerContactShare::find($buyerContactShare->id), 'BuyerContactShare should not exist in DB');
    }
}
