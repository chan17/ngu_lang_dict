<?php

namespace App\Repositories;

use App\Models\CommonType;
use App\Repositories\BaseRepository;

/**
 * Class CommonTypeRepository
 * @package App\Repositories
 * @version August 21, 2019, 9:53 am UTC
 *
 * @method CommonType findWithoutFail($id, $columns = ['*'])
 * @method CommonType find($id, $columns = ['*'])
 * @method CommonType first($columns = ['*'])
*/
class CommonTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CommonType::class;
    }
}
