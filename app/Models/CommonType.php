<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CommonType
 * @package App\Models
 * @version August 21, 2019, 9:53 am UTC
 *
 * @property string title
 * @property string remark
 * @property String group
 * @property smallInteger listorder
 * @property timestamps 
 */
class CommonType extends Model
{
    use SoftDeletes;

    public $table = 'common_type';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'title',
        'remark',
        'group',
        'listorder',
        ''
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'remark' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|max:50:nullable',
        'remark' => 'max:255:nullable'
    ];

    
}
