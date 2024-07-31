<?php

namespace App\Repositories;

use App\Models\FollowUpDept;
use App\Repositories\BaseRepository;

/**
 * Class FollowUpDeptRepository
 * @package App\Repositories
 * @version August 23, 2021, 7:52 am UTC
*/

class FollowUpDeptRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'dept_id',
        'activity_id',
        'users',
        'users_id'
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
        return FollowUpDept::class;
    }
}
