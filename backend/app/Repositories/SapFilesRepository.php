<?php

namespace App\Repositories;

use App\Models\SapFiles;
use App\Repositories\BaseRepository;

/**
 * Class SapFilesRepository
 * @package App\Repositories
 * @version July 1, 2021, 1:06 pm UTC
*/

class SapFilesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'file_name',
        'comp_code',
        'note',
        'date'
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
        return SapFiles::class;
    }
}
