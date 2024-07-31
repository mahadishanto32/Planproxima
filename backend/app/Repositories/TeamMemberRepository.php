<?php

namespace App\Repositories;

use App\Models\TeamMember;
use App\Repositories\BaseRepository;

/**
 * Class TeamMemberRepository
 * @package App\Repositories
 * @version March 20, 2022, 9:06 am UTC
*/

class TeamMemberRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'department_id',
        'wings_id',
        'team_id',
        'employee_id'
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
        return TeamMember::class;
    }
}
