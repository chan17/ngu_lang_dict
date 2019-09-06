<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    use SoftDeletes;
    public $table = 'phonetics';
    
    public $primaryKey = 'phonetic_id';
    protected $dates = ['deleted_at'];

    public $fillable = [
        // 'phonetic_id',
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

    public function entry()
    {
        return $this->belongsTo('\App\Models\Cms\Entry', 'entry_id','entry_id');
    }
    public function meta_type()
    {
        return $this->belongsTo('\App\Models\Meta\MetaType', 'region_type','type_id');
    }
}
