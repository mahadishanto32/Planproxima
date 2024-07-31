<?php

namespace App\Repositories;

use App\Models\MosDataLog;
use App\Repositories\BaseRepository;

/**
 * Class MosDataLogRepository
 * @package App\Repositories
 * @version June 1, 2022, 6:39 am UTC
*/

class MosDataLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mos_data_id',
        'type',
        'january',
        'february',
        'march',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'november',
        'december',
        'year',
        'total',
        'insert_type'
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
        return MosDataLog::class;
    }
}
