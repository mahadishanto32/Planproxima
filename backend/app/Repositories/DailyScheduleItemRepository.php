<?php

namespace App\Repositories;

use App\Models\DailyScheduleItem;
use App\Repositories\BaseRepository;

/**
 * Class DailyScheduleItemRepository
 * @package App\Repositories
 * @version June 28, 2022, 7:36 am UTC
*/

class DailyScheduleItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'daily_schedules_id',
        'schedule_type_id',
        'schedule_details'
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
        return DailyScheduleItem::class;
    }
}
