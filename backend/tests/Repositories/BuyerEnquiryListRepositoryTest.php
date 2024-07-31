<?php namespace Tests\Repositories;

use App\Models\BuyerEnquiryList;
use App\Repositories\BuyerEnquiryListRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class BuyerEnquiryListRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var BuyerEnquiryListRepository
     */
    protected $buyerEnquiryListRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->buyerEnquiryListRepo = \App::make(BuyerEnquiryListRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_buyer_enquiry_list()
    {
        $buyerEnquiryList = BuyerEnquiryList::factory()->make()->toArray();

        $createdBuyerEnquiryList = $this->buyerEnquiryListRepo->create($buyerEnquiryList);

        $createdBuyerEnquiryList = $createdBuyerEnquiryList->toArray();
        $this->assertArrayHasKey('id', $createdBuyerEnquiryList);
        $this->assertNotNull($createdBuyerEnquiryList['id'], 'Created BuyerEnquiryList must have id specified');
        $this->assertNotNull(BuyerEnquiryList::find($createdBuyerEnquiryList['id']), 'BuyerEnquiryList with given id must be in DB');
        $this->assertModelData($buyerEnquiryList, $createdBuyerEnquiryList);
    }

    /**
     * @test read
     */
    public function test_read_buyer_enquiry_list()
    {
        $buyerEnquiryList = BuyerEnquiryList::factory()->create();

        $dbBuyerEnquiryList = $this->buyerEnquiryListRepo->find($buyerEnquiryList->id);

        $dbBuyerEnquiryList = $dbBuyerEnquiryList->toArray();
        $this->assertModelData($buyerEnquiryList->toArray(), $dbBuyerEnquiryList);
    }

    /**
     * @test update
     */
    public function test_update_buyer_enquiry_list()
    {
        $buyerEnquiryList = BuyerEnquiryList::factory()->create();
        $fakeBuyerEnquiryList = BuyerEnquiryList::factory()->make()->toArray();

        $updatedBuyerEnquiryList = $this->buyerEnquiryListRepo->update($fakeBuyerEnquiryList, $buyerEnquiryList->id);

        $this->assertModelData($fakeBuyerEnquiryList, $updatedBuyerEnquiryList->toArray());
        $dbBuyerEnquiryList = $this->buyerEnquiryListRepo->find($buyerEnquiryList->id);
        $this->assertModelData($fakeBuyerEnquiryList, $dbBuyerEnquiryList->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_buyer_enquiry_list()
    {
        $buyerEnquiryList = BuyerEnquiryList::factory()->create();

        $resp = $this->buyerEnquiryListRepo->delete($buyerEnquiryList->id);

        $this->assertTrue($resp);
        $this->assertNull(BuyerEnquiryList::find($buyerEnquiryList->id), 'BuyerEnquiryList should not exist in DB');
    }
}
