<?php

namespace App\Repositories;

use App\Models\MonthlyReport;
use App\Repositories\BaseRepository;

/**
 * Class MonthlyReportRepository
 * @package App\Repositories
 * @version April 28, 2021, 8:56 am UTC
*/

class MonthlyReportRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'dept_id',
        'task_name',
        'monthly_work',
        'topforcurrentmonth',
        'valueadd',
        'reason',
        'month',
        'year',
        'date',
        'worktype',
        'user_id'
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
        return MonthlyReport::class;
    }
}
