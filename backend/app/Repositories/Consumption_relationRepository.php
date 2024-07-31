<?php

namespace App\Repositories;

use App\Models\Consumption_relation;
use App\Repositories\BaseRepository;

/**
 * Class Consumption_relationRepository
 * @package App\Repositories
 * @version September 30, 2021, 4:16 am UTC
*/

class Consumption_relationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'wastage_summary_group_id',
        'product_id'
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
        return Consumption_relation::class;
    }
}
