<?php

namespace App\Http\Requests;

class BatchRequest extends Request
{
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
                    'name' => 'required|min:2',
                    'engineering_id' => 'required|numeric',
                    'longitude' => 'required|numeric|between:-180,180',
                    'latitude' => 'required|numeric|between:-90,90',
                    'range' => 'required|numeric|min:0',
                    'safe_distance' => 'required|numeric|min:0',
                    'finish_at' => 'required|date',
                    'start_at' => 'required|date|before:finish_at',
                    'description' => 'required|min:3',
                    'technicians' => 'required|array',
                    'custodians' => 'required|array',
                    'safety_officers' =>'required|array',
                    'powdermen' =>'required|array',
                    'manager' => 'required|numeric',
                    'detonator' => 'required|numeric',
                    'dynamite' => 'required|numeric',
                ];
            }
            case 'DELETE':
            {
                return [
                    'ids' => 'array',
                ];
            }
            case 'GET':
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
            'longitude.numeric' => '经度必须是数字',
            'longitude.between' => '经度必须介于 -180 至 180 之间',
            'latitude.numeric' => '纬度必须是数字',
            'latitude.between' => '纬度必须介于 -90 至 90 之间',
            'range.numeric' => '爆破范围必须是数字',
            'range.min' => '爆破范围必须大于0',
            'safe_distance.numeric' => '安全距离必须是数字',
            'safe_distance.min' => '安全距离必须大于0',
            'description.min.numeric' => '文章内容必须至少三个字符',
            'start_at.before' => '开始时间必须要早于结束时间',
            'technicians.required' => '工程技术员不能为空',
            'custodians.required' => '保管员不能为空',
            'safety_officers.required' =>'安全员不能为空',
            'powdermen.required' =>'爆破员不能为空',
            'manager.required' => '负责人不能为空',
            'ids.array' => '请填入正确格式',
        ];
    }
}
