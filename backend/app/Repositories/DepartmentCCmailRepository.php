<?php

namespace App\Repositories;

use App\Models\DepartmentCCmail;
use App\Repositories\BaseRepository;

/**
 * Class DepartmentCCmailRepository
 * @package App\Repositories
 * @version May 16, 2022, 3:48 am UTC
*/

class DepartmentCCmailRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'dept_id',
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
        return DepartmentCCmail::class;
    }
}
