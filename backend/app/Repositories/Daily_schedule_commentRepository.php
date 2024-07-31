<?php

namespace App\Repositories;

use App\Models\Daily_schedule_comment;
use App\Repositories\BaseRepository;

/**
 * Class Daily_schedule_commentRepository
 * @package App\Repositories
 * @version July 17, 2021, 6:34 am UTC
*/

class Daily_schedule_commentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'daily_schedule_id',
        'user_id',
        'comment'
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
        return Daily_schedule_comment::class;
    }
}
