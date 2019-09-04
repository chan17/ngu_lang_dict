<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Phonetic
 * @package App\Models\Cms
 * @version September 4, 2019, 7:46 am UTC
 *
 * @property increments phonetic_id
 * @property smallInteger region_type
 * @property integer entry_id
 * @property string value
 * @property timestamps 
 */
class Phonetic extends Model
{

    public $table = 'phonetics';
    


    public $fillable = [
        'phonetic_id',
        'region_type',
        'entry_id',
        'value',
        ''
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'entry_id' => 'integer',
        'value' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
