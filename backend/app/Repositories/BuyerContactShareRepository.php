<?php

namespace App\Repositories;

use App\Models\BuyerContactShare;
use App\Repositories\BaseRepository;

/**
 * Class BuyerContactShareRepository
 * @package App\Repositories
 * @version March 30, 2023, 10:24 am +06
*/

class BuyerContactShareRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'b_id',
        'user_id'
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
        return BuyerContactShare::class;
    }
}
