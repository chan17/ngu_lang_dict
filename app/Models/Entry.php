<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Entry
 * @package App\Models
 * @version September 3, 2019, 4:13 pm UTC
 *
 * @property increments entry_id
 * @property string title
 * @property json explanation
 * @property json example
 * @property timestamps 
 */
class Entry extends Model
{

    public $table = 'entries';
    


    public $fillable = [
        'entry_id',
        'title',
        'explanation',
        'example',
        ''
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|max:32'
    ];

    
}
