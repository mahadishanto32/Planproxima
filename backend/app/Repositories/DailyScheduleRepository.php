<?php

namespace App\Repositories;

use App\Models\DailySchedule;
use App\Repositories\BaseRepository;

/**
 * Class DailyScheduleRepository
 * @package App\Repositories
 * @version April 24, 2021, 6:55 am UTC
*/

class DailyScheduleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'role_id',
        'user_id',
        'kra_id',
        'kpi_id',
        'mos_id',
        'dept_id',
        'wing_id',
        'date',
        'start_time',
        'end_time',
        'task',
        'top_priority'
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
        return DailySchedule::class;
    }
}
