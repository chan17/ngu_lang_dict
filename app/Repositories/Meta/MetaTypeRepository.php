<?php

namespace App\Repositories\Meta;

use App\Models\Meta\MetaType;
use App\Repositories\BaseRepository;

/**
 * Class MetaTypeRepository
 * @package App\Repositories\Meta
 * @version August 22, 2019, 9:20 am UTC
 *
 * @method MetaType findWithoutFail($id, $columns = ['*'])
 * @method MetaType find($id, $columns = ['*'])
 * @method MetaType first($columns = ['*'])
*/
class MetaTypeRepository extends BaseRepository
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
        return MetaType::class;
    }
}
