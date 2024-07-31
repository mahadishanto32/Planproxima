<?php

namespace App\Repositories;

use App\Models\FactoryCapacity;
use App\Repositories\BaseRepository;

/**
 * Class FactoryCapacityRepository
 * @package App\Repositories
 * @version June 6, 2021, 10:02 am UTC
*/

class FactoryCapacityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'jan',
        'feb',
        'mar',
        'apr',
        'may',
        'jun',
        'jul',
        'aug',
        'sep',
        'oct',
        'nov',
        'dec',
        'summary_group_id',
        'year',
        'type',
        'created_by',
        'updated_by',
        'total_capacity'
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
        return FactoryCapacity::class;
    }
}
