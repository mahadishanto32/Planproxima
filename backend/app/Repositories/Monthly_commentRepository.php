<?php

namespace App\Repositories;

use App\Models\Monthly_comment;
use App\Repositories\BaseRepository;

/**
 * Class Monthly_commentRepository
 * @package App\Repositories
 * @version September 6, 2021, 7:58 am UTC
*/

class Monthly_commentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'report_id',
        'user_id',
        'dept_id'
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
        return Monthly_comment::class;
    }
}
