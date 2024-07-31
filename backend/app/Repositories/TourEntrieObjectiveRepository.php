<?php

namespace App\Repositories;

use App\Models\TourEntrieObjective;
use App\Repositories\BaseRepository;

/**
 * Class TourEntrieObjectiveRepository
 * @package App\Repositories
 * @version June 30, 2022, 4:23 am UTC
*/

class TourEntrieObjectiveRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tour_entrie_id',
        'objective'
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
        return TourEntrieObjective::class;
    }
}
