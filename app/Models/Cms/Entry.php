<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Entry
 * @package App\Models\Cms
 * @version September 4, 2019, 7:41 am UTC
 *
 * @property increments entry_id
 * @property string title
 * @property json explanation
 * @property json example
 * @property timestamps 
 */
class Entry extends Model
{
    use SoftDeletes;

    public $table = 'entries';
    protected $dates = ['deleted_at'];
    
    public $primaryKey = 'entry_id';

    public $fillable = [
        // 'entry_id',
        'title',
        'explanation',
        'example',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'explanation' => 'json',
        'example' => 'json',
    ];
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|max:32'
    ];

    public function getExplanationAttribute($explanation)
    {
        $result = array_values(json_decode($explanation, true) ?: []);
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                if ($value['_remove_']==1) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
    }

    public function setExplanationAttribute($explanation)
    {
        $this->attributes['explanation'] = json_encode(array_values($explanation));
    }

    public function getExampleAttribute($example)
    {
        $result = array_values(json_decode($example, true) ?: []);
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                if ($value['_remove_']==1) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
    }

    public function setExampleAttribute($example)
    {
        $this->attributes['example'] = json_encode(array_values($example));
    }
    
    public function phonetics()
    {
        return $this->hasMany(Phonetic::class, 'entry_id','entry_id');
    }
}
