<?php

namespace App\Repositories;

use App\Models\BuyerEnquiryList;
use App\Repositories\BaseRepository;

/**
 * Class BuyerEnquiryListRepository
 * @package App\Repositories
 * @version March 22, 2023, 4:46 pm +06
*/

class BuyerEnquiryListRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company',
        'productType',
        'country',
        'designation'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BuyerEnquiryList::class;
    }
}
