<?php

namespace App\Repositories;

use App\Models\TourUser;
use App\Repositories\BaseRepository;

/**
 * Class TourUserRepository
 * @package App\Repositories
 * @version May 19, 2021, 6:37 am UTC
*/

class TourUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'employee_id',
        'designation',
        'business_type',
        'head_of_sales',
        'division_head',
        'sm',
        'dsm',
        'asm',
        'adsm',
        'rsm'
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
        return TourUser::class;
    }
}
