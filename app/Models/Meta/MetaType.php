<?php

namespace App\Models\Meta;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MetaType
 * @package App\Models\Meta
 * @version August 22, 2019, 9:20 am UTC
 *
 * @property string title
 * @property smallInteger pid
 * @property string remark
 * @property String group
 * @property smallInteger listorder
 * @property timestamps 
 */
class MetaType extends Model
{
    use SoftDeletes;

    public $table = 'meta_type';
    
    public $primaryKey = 'type_id';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'title',
        'pid',
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

    public function PIDself() {
        return $this->hasOne(self::class,'type_id','pid');
    }
}
