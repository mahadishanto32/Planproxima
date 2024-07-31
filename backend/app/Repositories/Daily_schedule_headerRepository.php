<?php

namespace App\Repositories;

use App\Models\Daily_schedule_header;
use App\Repositories\BaseRepository;

/**
 * Class Daily_schedule_headerRepository
 * @package App\Repositories
 * @version August 2, 2021, 3:59 am UTC
*/

class Daily_schedule_headerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'headname',
        'dept_id'
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
        return Daily_schedule_header::class;
    }
}
