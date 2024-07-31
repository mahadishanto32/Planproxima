<?php

namespace App\Repositories;

use App\Models\Production_product_name;
use App\Repositories\BaseRepository;

/**
 * Class Production_product_nameRepository
 * @package App\Repositories
 * @version August 5, 2021, 10:05 am UTC
*/

class Production_product_nameRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_name',
        'type',
        'factory_id',
        'user_id',
        'active'
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
        return Production_product_name::class;
    }
}
