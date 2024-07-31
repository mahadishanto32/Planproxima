<?php

namespace App\Repositories;

use App\Models\PriorityTaskComments;
use App\Repositories\BaseRepository;

/**
 * Class PriorityTaskCommentsRepository
 * @package App\Repositories
 * @version November 6, 2023, 12:01 pm +06
*/

class PriorityTaskCommentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
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
        return PriorityTaskComments::class;
    }
}
