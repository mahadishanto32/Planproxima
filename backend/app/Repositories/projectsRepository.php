<?php

namespace App\Repositories;

use App\Models\projects;
use App\Repositories\BaseRepository;

/**
 * Class projectsRepository
 * @package App\Repositories
 * @version March 1, 2023, 11:10 am +06
*/

class projectsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'dep',
        'wings'
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
        return projects::class;
    }
}
