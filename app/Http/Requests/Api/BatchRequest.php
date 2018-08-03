<?php

namespace App\Http\Requests\Api;

class BatchRequest extends FormRequest
{
    public function rules()
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|min:2',
                    'engineering_id' => 'required|numeric',
                    'longitude' => 'required|numeric|between:-180,180',
                    'latitude' => 'required|numeric|between:-90,90',
                    'range' => 'required|numeric|min:0',
                    'safe_distance' => 'required|numeric|min:0',
                    'finish_at' => 'date',
                    'start_at' => 'required|date',
                    'description' => 'required|min:3',
                    'technicians' => 'required|array',
                    'custodians' => 'required|array',
                    'safety_officers' =>'required|array',
                    'powdermen' =>'required|array',
                    'manager' => 'required|numeric',
                    'detonator' => 'required|numeric',
                    'dynamite' => 'required|numeric',
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'min:2',
                    'engineering_id' => 'numeric',
                    'longitude' => 'numeric|between:-180,180',
                    'latitude' => 'numeric|between:-90,90',
                    'range' => 'numeric|min:0',
                    'safe_distance' => 'numeric|min:0',
                    'finish_at' => 'date',
                    'start_at' => 'date|before:finish_at',
                    'description' => 'min:3',
                    'technicians' => 'array',
                    'custodians' => 'array',
                    'safety_officers' =>'array',
                    'powdermen' =>'array',
                    'manager' => 'numeric',
                    'detonator' => 'numeric',
                    'dynamite' => 'numeric',
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'name' => '批次名称',
            'engineering_id' => '所属工程',
            'longitude' => '经度',
            'latitude' => '纬度',
            'range' => '爆破范围',
            'safe_distance' => '安全距离',
            'description' => '工程描述',
            'start_at' => '开始时间',
            'finish_at' => '结束时间',
            'technicians' => '工程技术员',
            'custodians' => '保管员',
            'safety_officers' =>'安全员',
            'powdermen' =>'爆破员',
            'manager' => '负责人',
            'detonator' => '雷管数量',
            'dynamite' => '炸药数量',
        ];
    }

//    public function messages()
//    {
//        return [
//            'name.min' => '批次名称必须至少两个字符',
//            'longitude.numeric' => '经度必须是数字',
//            'longitude.between' => '经度必须介于 -180 至 180 之间',
//            'latitude.numeric' => '纬度必须是数字',
//            'latitude.between' => '纬度必须介于 -90 至 90 之间',
//            'range.numeric' => '爆破范围必须是数字',
//            'range.min' => '爆破范围必须大于0',
//            'safe_distance.numeric' => '安全距离必须是数字',
//            'safe_distance.min' => '安全距离必须大于0',
//            'description.min' => '工程描述至少须三个字符',
//            'start_at.before' => '开始时间必须要早于结束时间',
//            'technicians.required' => '工程技术员不能为空',
//            'custodians.required' => '保管员不能为空',
//            'safety_officers.required' =>'安全员不能为空',
//            'powdermen.required' =>'爆破员不能为空',
//            'manager.required' => '负责人不能为空',
//        ];
//    }
}
