<?php

namespace App\Repositories;

use App\Models\PriorityTaskItem;
use App\Repositories\BaseRepository;

/**
 * Class PriorityTaskItemRepository
 * @package App\Repositories
 * @version November 2, 2023, 11:04 am +06
*/

class PriorityTaskItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'priority_task_id'
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
        return PriorityTaskItem::class;
    }
}
