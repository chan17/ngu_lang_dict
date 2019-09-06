<?php

namespace App\Models\Meta;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
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
    use ModelTree, AdminBuilder;

    public $table = 'meta_type';
    
    public $primaryKey = 'type_id';

    protected $dates = ['deleted_at'];
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('pid');
        $this->setOrderColumn('listorder');
        $this->setTitleColumn('title');
    }

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
