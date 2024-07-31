<?php

namespace App\Repositories;

use App\Models\TourUserProfile;

/**
 * Class TourEntryRepository
 * @package App\Repositories
 * @version June 16, 2021, 4:52 am UTC
*/

class TourUserProfileEntryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'base_station_address',
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
        return TourUserProfile::class;
    }
}
