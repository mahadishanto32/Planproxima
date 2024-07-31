<?php

namespace App\Repositories;

use App\Models\ProductionPlans;
use App\Repositories\BaseRepository;

/**
 * Class ProductionPlansRepository
 * @package App\Repositories
 * @version June 6, 2021, 10:56 am UTC
*/

class ProductionPlansRepository extends BaseRepository
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
        'production_plan',
        'material_code'
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
        return ProductionPlans::class;
    }
}
