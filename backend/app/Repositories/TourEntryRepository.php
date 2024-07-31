<?php

namespace App\Repositories;

use App\Models\TourEntry;
use App\Repositories\BaseRepository;

/**
 * Class TourEntryRepository
 * @package App\Repositories
 * @version June 16, 2021, 4:52 am UTC
*/

class TourEntryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'point',
        'territory',
        'route',
        'objectives',
        'issues',
        'contactperson',
        'hq',
        'remarks',
        'feedback',
        'status',
        'approval'
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
        return TourEntry::class;
    }
}
