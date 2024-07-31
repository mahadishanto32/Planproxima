<?php

namespace App\Repositories;

use App\Models\MonthlyDateRange;
use App\Repositories\BaseRepository;

/**
 * Class MonthlyDateRangeRepository
 * @package App\Repositories
 * @version July 11, 2021, 5:07 am UTC
*/

class MonthlyDateRangeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'dept_id',
        'start_date',
        'end_date',
        'status',
        'permission_for'
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
        return MonthlyDateRange::class;
    }
}
