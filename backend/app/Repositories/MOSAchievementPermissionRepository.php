<?php

namespace App\Repositories;

use App\Models\MOSAchievementPermission;
use App\Repositories\BaseRepository;

/**
 * Class MOSAchievementPermissionRepository
 * @package App\Repositories
 * @version September 5, 2022, 7:15 am UTC
*/

class MOSAchievementPermissionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'role_id',
        'mos_id',
        'jan',
        'feb',
        'mar',
        'apr',
        'may',
        'jun',
        'jul',
        'aug',
        'sep',
        'oct',
        'nov',
        'dec',
        'dept_id',
        'year',
        'type',
        'start_date',
        'end_date'
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
        return MOSAchievementPermission::class;
    }
}
