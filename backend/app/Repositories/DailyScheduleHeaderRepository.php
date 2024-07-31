<?php

namespace App\Repositories;

use App\Models\DailyScheduleHeader;
use App\Repositories\BaseRepository;

/**
 * Class DailyScheduleHeaderRepository
 * @package App\Repositories
 * @version June 29, 2022, 5:15 pm UTC
*/

class DailyScheduleHeaderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'headname',
        'dept_id',
        'active',
        'serialno'
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
        return DailyScheduleHeader::class;
    }
}
