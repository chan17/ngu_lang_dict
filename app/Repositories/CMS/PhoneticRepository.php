<?php

namespace App\Repositories\Cms;

use App\Models\Cms\Phonetic;
use App\Repositories\BaseRepository;

/**
 * Class PhoneticRepository
 * @package App\Repositories\Cms
 * @version September 4, 2019, 7:46 am UTC
 *
 * @method Phonetic findWithoutFail($id, $columns = ['*'])
 * @method Phonetic find($id, $columns = ['*'])
 * @method Phonetic first($columns = ['*'])
*/
class PhoneticRepository extends BaseRepository
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
        return Phonetic::class;
    }
}
