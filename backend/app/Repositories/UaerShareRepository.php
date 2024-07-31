<?php

namespace App\Repositories;

use App\Models\UaerShare;
use App\Repositories\BaseRepository;

/**
 * Class UaerShareRepository
 * @package App\Repositories
 * @version November 8, 2022, 9:41 am +06
*/

class UaerShareRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id'
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
        return UaerShare::class;
    }
}
