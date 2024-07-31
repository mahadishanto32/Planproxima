<?php

namespace App\Repositories;

use App\Models\buyerEnquiryColumn;
use App\Repositories\BaseRepository;

/**
 * Class buyerEnquiryColumnRepository
 * @package App\Repositories
 * @version April 11, 2023, 2:09 pm +06
*/

class buyerEnquiryColumnRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'buyer_enquiry_id',
        'column_name'
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
        return buyerEnquiryColumn::class;
    }
}
