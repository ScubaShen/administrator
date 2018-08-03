<?php

namespace App\Http\Requests\Api;

class BatchRequest extends FormRequest
{
    public function rules()
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string',
                    'description' => 'required|string',
                    'supervision_id' => 'required|exists:supervisions,id',
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'string',
                    'description' => 'string',
                    'supervision_id' => 'exists:supervisions,id',
                ];
                break;
        }
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
