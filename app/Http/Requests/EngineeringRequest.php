<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EngineeringRequest extends FormRequest
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
            // CREATE
            case 'POST':
                // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'            => 'required|min:2',
                    'supervision_id' => 'required|numeric',
                    'description'    => 'required|min:3',
//                    'start_at'       => 'required|date_format:Y/m/d H:i',
//                    'finish_at'       => 'required|date_format:Y/m/d H:i',
                ];
            }
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            };
        }
    }

    public function messages()
    {
        return [
            'name.min' => '标题必须至少两个字符',
            'description.min' => '文章内容必须至少三个字符',
        ];
    }
}
