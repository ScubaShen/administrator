<?php

namespace App\Http\Requests\Api;

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
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'supervision_id' => 'required|exists:supervisions,id',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '工程名称',
            'description' => '工程描述',
            'supervision_id' => '监理单位',
        ];
    }
}
