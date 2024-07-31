<?php

namespace App\Repositories;

use App\Models\FactoryStandard;
use App\Repositories\BaseRepository;

/**
 * Class FactoryStandardRepository
 * @package App\Repositories
 * @version June 6, 2021, 9:00 am UTC
*/

class FactoryStandardRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'year',
        'type',
        'cost_center',
        'gl_code',
        'gl_text',
        'cost_amount',
        'cost_center_id'
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
        return FactoryStandard::class;
    }
}
