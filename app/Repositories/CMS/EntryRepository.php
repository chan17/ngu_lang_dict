<?php

namespace App\Repositories\Cms;

use App\Models\Cms\Entry;
use App\Repositories\BaseRepository;

/**
 * Class EntryRepository
 * @package App\Repositories\Cms
 * @version September 4, 2019, 7:41 am UTC
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

    public function edit($data,$id){
        dd($this->model);
        unset($data['_token']);
        unset($data['_method']);
        \DB::beginTransaction();
        if (!$this->model->where('entry_id', $id)->save($data)) {
            \DB::rollBack();
            return '詞條數據出錯';
        }
        
        \DB::commit();
    }
}
