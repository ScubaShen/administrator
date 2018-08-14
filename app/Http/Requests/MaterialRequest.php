<?php

namespace App\Http\Requests;

class MaterialRequest extends Request
{
    public function rules()
    {
        switch($this->method()) {
            case 'POST':
            case 'PATCH':
            {
                return [
                    'name' => 'required|unique:materials,name',
                ];
            }
            case 'DELETE':
            {
                return [
                    'ids' => 'array',
                ];
            }
        }
    }

    public function messages()
    {
        return [
            'name.required' => '材料名称不能为空',
            'name.unique' => '材料名称已经存在'
        ];
    }
}
