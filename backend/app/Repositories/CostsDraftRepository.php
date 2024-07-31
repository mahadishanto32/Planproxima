<?php

namespace App\Repositories;

use App\Models\CostsDraft;
use App\Repositories\BaseRepository;

/**
 * Class CostsDraftRepository
 * @package App\Repositories
 * @version June 6, 2021, 5:40 am UTC
*/

class CostsDraftRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'factory_code',
        'cost',
        'remarks',
        'cost_center',
        'error_note',
        'gl_code',
        'data'
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
        return CostsDraft::class;
    }
}
