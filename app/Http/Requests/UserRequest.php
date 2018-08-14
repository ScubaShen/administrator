<?php

namespace App\Http\Requests;

class UserRequest extends Request
{
    public function rules()
    {
        switch($this->method()) {
            case 'PATCH':
                $userId = \Auth::user()->id;
                return [
                    'name' => 'between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . $userId,
                    'realname' => 'required',
                ];
        }
    }

    public function messages()
    {
        return [
            'name.unique' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持英文、数字、横杆和下划线',
            'name.between' => '用户名必须介于 3 - 25 个字符之间',
            'name.required' => '用户名不能为空',
            'realname.required' => '真实姓名不能为空',
        ];
    }
}
