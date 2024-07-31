<?php

namespace App\Repositories;

use App\Models\PriorityTask;
use App\Repositories\BaseRepository;

/**
 * Class PriorityTaskRepository
 * @package App\Repositories
 * @version November 2, 2023, 11:01 am +06
*/

class PriorityTaskRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'dept_id',
        'quarter_id',
        'status'
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
        return PriorityTask::class;
    }
}
