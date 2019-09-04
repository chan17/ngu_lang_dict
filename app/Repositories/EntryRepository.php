<?php

namespace App\Repositories;

use App\Models\Entry;
use App\Repositories\BaseRepository;

/**
 * Class EntryRepository
 * @package App\Repositories
 * @version September 3, 2019, 4:13 pm UTC
 *
 * @method Entry findWithoutFail($id, $columns = ['*'])
 * @method Entry find($id, $columns = ['*'])
 * @method Entry first($columns = ['*'])
*/
class EntryRepository extends BaseRepository
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
        return Entry::class;
    }
}
