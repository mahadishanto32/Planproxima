<?php

namespace App\Repositories;

use App\Models\CostSummaryGroup;
use App\Repositories\BaseRepository;

/**
 * Class CostSummaryGroupRepository
 * @package App\Repositories
 * @version January 17, 2022, 4:26 am UTC
*/

class CostSummaryGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'group_name',
        'summary_group_id',
        'plant_id'
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
        return CostSummaryGroup::class;
    }
}
