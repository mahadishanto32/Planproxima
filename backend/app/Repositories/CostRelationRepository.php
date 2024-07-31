<?php

namespace App\Repositories;

use App\Models\CostRelation;
use App\Repositories\BaseRepository;

/**
 * Class CostRelationRepository
 * @package App\Repositories
 * @version January 17, 2022, 4:30 am UTC
*/

class CostRelationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'summary_group_id',
        'plant_id',
        'cost_center'
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
        return CostRelation::class;
    }
}
