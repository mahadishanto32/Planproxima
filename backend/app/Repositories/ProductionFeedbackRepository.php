<?php

namespace App\Repositories;

use App\Models\ProductionFeedback;
use App\Repositories\BaseRepository;

/**
 * Class ProductionFeedbackRepository
 * @package App\Repositories
 * @version July 14, 2021, 5:34 am UTC
*/

class ProductionFeedbackRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'factory_id',
        'summary_group_id',
        'production_type',
        'section',
        'section_name',
        'comments',
        'type',
        'start_date',
        'end_date'
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
        return ProductionFeedback::class;
    }
}
