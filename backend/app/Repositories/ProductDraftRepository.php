<?php

namespace App\Repositories;

use App\Models\ProductDraft;
use App\Repositories\BaseRepository;

/**
 * Class ProductDraftRepository
 * @package App\Repositories
 * @version October 7, 2021, 4:57 am UTC
*/

class ProductDraftRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'plant',
        'product_group',
        'wastage_group',
        'material_code',
        'description',
        'material_group',
        'material_type',
        'base_unit_of_measure',
        'product_type',
        'error_note',
        'status'
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
        return ProductDraft::class;
    }
}
