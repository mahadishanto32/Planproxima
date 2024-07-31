<?php

namespace App\Repositories;

use App\Models\UserManual;
use App\Repositories\BaseRepository;

/**
 * Class UserManualRepository
 * @package App\Repositories
 * @version July 25, 2022, 6:19 am UTC
*/

class UserManualRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'details',
        'status'
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
        return UserManual::class;
    }
}
