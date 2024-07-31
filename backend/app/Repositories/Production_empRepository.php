<?php

namespace App\Repositories;

use App\Models\Production_emp;
use App\Repositories\BaseRepository;

/**
 * Class Production_empRepository
 * @package App\Repositories
 * @version August 5, 2021, 9:17 am UTC
*/

class Production_empRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'factory_id',
        'product_id',
        'week',
        'month',
        'year',
        'number_of_join',
        'number_of_resig',
        'begining_emp',
        'ending_emp',
        'remarks',
        'user_id',
        'active'
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
        return Production_emp::class;
    }
}
