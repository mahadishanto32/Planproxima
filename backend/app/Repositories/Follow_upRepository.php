<?php

namespace App\Repositories;

use App\Models\Follow_up;
use App\Repositories\BaseRepository;

/**
 * Class follow_upRepository
 * @package App\Repositories
 * @version August 23, 2021, 5:26 am UTC
*/

class Follow_upRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'date',
        'details',
        'dept_id',
        'firstremind',
        'secondremind',
        'user_id',
        'status',
        'active',
        'dmdactive'
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
        return follow_up::class;
    }
}
