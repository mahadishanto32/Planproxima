<?php

namespace App\Repositories;

use App\Models\DailyScheduleType;
use App\Repositories\BaseRepository;

/**
 * Class DailyScheduleTypeRepository
 * @package App\Repositories
 * @version June 28, 2022, 3:27 am UTC
*/

class DailyScheduleTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
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
        return DailyScheduleType::class;
    }
}
