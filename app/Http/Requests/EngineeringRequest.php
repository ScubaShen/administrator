<?php

namespace App\Http\Requests;

class EngineeringRequest extends Request
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
                    'name'              => 'required|min:2',
                    'supervision_id'   => 'required|numeric',
                    'description'      => 'required|min:3',
                    'technician[]'     => 'array',
                    'custodian[]'      => 'array',
                    'safety_officer[]' =>'array',
                    'finish_at'       => 'required|date',
                    'start_at'       => 'required|date|before:finish_at',
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
            'description.min' => '文章内容必须至少三个字符',
            'start_at.before' => '开始时间必须要早于结束时间',
            'ids.array' => '请填入正确格式',
        ];
    }
}
