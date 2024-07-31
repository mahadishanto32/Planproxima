<?php

namespace App\Repositories;

use App\Models\MonthlyReportFile;
use App\Repositories\BaseRepository;

/**
 * Class MonthlyReportFileRepository
 * @package App\Repositories
 * @version June 8, 2021, 10:04 am UTC
*/

class MonthlyReportFileRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'report_id',
        'file_name'
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
        return MonthlyReportFile::class;
    }
}
