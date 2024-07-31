<?php

namespace App\Repositories;

use App\Models\UserManualFile;
use App\Repositories\BaseRepository;

/**
 * Class UserManualFileRepository
 * @package App\Repositories
 * @version July 25, 2022, 6:23 am UTC
*/

class UserManualFileRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_manual_id',
        'file_name',
        'order_by'
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
        return UserManualFile::class;
    }
}
