<?php

namespace App\Http\Requests;

class MemberRequest extends Request
{
    public function rules()
    {
        switch($this->method()) {
            case 'POST':
            {
                return [
                    'name' => 'required|array',
                ];
            }
            case 'PATCH':
            {
                return [
                    'name' => 'between:3,25|required',
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
            'name.between' => '用户名必须介于 3 - 25 个字符之间',
            'name.required' => '用户名不能为空',
        ];
    }
}
