<?php

namespace App\Repositories;

use App\Models\Wastege_relation;
use App\Repositories\BaseRepository;

/**
 * Class Wastege_relationRepository
 * @package App\Repositories
 * @version September 30, 2021, 4:13 am UTC
*/

class Wastege_relationRepository extends BaseRepository
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
        return Wastege_relation::class;
    }
}
