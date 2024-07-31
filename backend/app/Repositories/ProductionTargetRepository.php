<?php

namespace App\Repositories;

use App\Models\ProductionTarget;
use App\Repositories\BaseRepository;

/**
 * Class ProductionTargetRepository
 * @package App\Repositories
 * @version June 7, 2021, 3:25 am UTC
*/

class ProductionTargetRepository extends BaseRepository
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
        'summary_group_id',
        'year',
        'type',
        'material_code',
        'production_target',
        'created_by',
        'updated_by'
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
        return ProductionTarget::class;
    }
}
