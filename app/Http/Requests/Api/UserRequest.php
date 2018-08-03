<?php

namespace App\Http\Requests\Api;

class UserRequest extends FormRequest
{
    public function rules()
    {
        switch($this->method()) {
            case 'PATCH':
                $userId = \Auth::guard('api')->id();
                return [
                    'name' => 'between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' .$userId,
                    'realname' => 'max:50',
//                    'avatar_image_id' => 'exists:images,id,type,avatar,user_id,'.$userId,
                ];
                break;
        }
    }

    public function messages()
    {
        return [
            'name.unique' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持英文、数字、横杆和下划线',
            'name.between' => '用户名必须介于 3 - 25 个字符之间',
            'realname.max' => '真实姓名不能超过50个字',
        ];
    }
}