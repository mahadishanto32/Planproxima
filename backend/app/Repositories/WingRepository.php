<?php

namespace App\Repositories;

use App\Models\Wing;
use App\Repositories\BaseRepository;

/**
 * Class WingRepository
 * @package App\Repositories
 * @version May 26, 2021, 3:54 am UTC
*/

class WingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        //'id',
        'dept_id',
        'status',
        'wing_title',
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
        return Wing::class;
    }
}
