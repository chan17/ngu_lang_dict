<?php

namespace App\Http\Requests\Meta;

use Illuminate\Foundation\Http\FormRequest;

class MetaTypeRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            // Crate
            case 'POST':
            {
                return [
                    'title' => 'required|max:50:nullable',
                    'remark' => 'max:255:nullable'
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                $id = $this->route('meta_type');
                return [
                    // 'title' => 'required|max:50:nullable',
                    'remark' => 'max:255:nullable'
                ];
            }
            default:
                break;
        }
    }
}
