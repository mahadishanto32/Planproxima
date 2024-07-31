<?php namespace Tests\Repositories;

use App\Models\buyerEnquiryColumn;
use App\Repositories\buyerEnquiryColumnRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class buyerEnquiryColumnRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var buyerEnquiryColumnRepository
     */
    protected $buyerEnquiryColumnRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->buyerEnquiryColumnRepo = \App::make(buyerEnquiryColumnRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_buyer_enquiry_column()
    {
        $buyerEnquiryColumn = buyerEnquiryColumn::factory()->make()->toArray();

        $createdbuyerEnquiryColumn = $this->buyerEnquiryColumnRepo->create($buyerEnquiryColumn);

        $createdbuyerEnquiryColumn = $createdbuyerEnquiryColumn->toArray();
        $this->assertArrayHasKey('id', $createdbuyerEnquiryColumn);
        $this->assertNotNull($createdbuyerEnquiryColumn['id'], 'Created buyerEnquiryColumn must have id specified');
        $this->assertNotNull(buyerEnquiryColumn::find($createdbuyerEnquiryColumn['id']), 'buyerEnquiryColumn with given id must be in DB');
        $this->assertModelData($buyerEnquiryColumn, $createdbuyerEnquiryColumn);
    }

    /**
     * @test read
     */
    public function test_read_buyer_enquiry_column()
    {
        $buyerEnquiryColumn = buyerEnquiryColumn::factory()->create();

        $dbbuyerEnquiryColumn = $this->buyerEnquiryColumnRepo->find($buyerEnquiryColumn->id);

        $dbbuyerEnquiryColumn = $dbbuyerEnquiryColumn->toArray();
        $this->assertModelData($buyerEnquiryColumn->toArray(), $dbbuyerEnquiryColumn);
    }

    /**
     * @test update
     */
    public function test_update_buyer_enquiry_column()
    {
        $buyerEnquiryColumn = buyerEnquiryColumn::factory()->create();
        $fakebuyerEnquiryColumn = buyerEnquiryColumn::factory()->make()->toArray();

        $updatedbuyerEnquiryColumn = $this->buyerEnquiryColumnRepo->update($fakebuyerEnquiryColumn, $buyerEnquiryColumn->id);

        $this->assertModelData($fakebuyerEnquiryColumn, $updatedbuyerEnquiryColumn->toArray());
        $dbbuyerEnquiryColumn = $this->buyerEnquiryColumnRepo->find($buyerEnquiryColumn->id);
        $this->assertModelData($fakebuyerEnquiryColumn, $dbbuyerEnquiryColumn->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_buyer_enquiry_column()
    {
        $buyerEnquiryColumn = buyerEnquiryColumn::factory()->create();

        $resp = $this->buyerEnquiryColumnRepo->delete($buyerEnquiryColumn->id);

        $this->assertTrue($resp);
        $this->assertNull(buyerEnquiryColumn::find($buyerEnquiryColumn->id), 'buyerEnquiryColumn should not exist in DB');
    }
}
