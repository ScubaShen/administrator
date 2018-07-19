<?php

namespace App\Http\Requests;

class SearchRequest extends Request
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
                    'name'   => 'required_without_all:start_at,end_at|nullable|min:1',
                    'end_at'       => 'required_with:start_at|nullable|date',
                    'start_at'     => 'required_with:end_at|nullable|date|before:end_at',
                    'page'         => 'nullable|numeric|integer|min:1',
                    'rows_per_page'  => 'required|numeric|integer',
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
            'name.min' => '搜寻字段最少为1个字符',
            'name.required_without_all' => '搜寻字段最少为1个字符',
            'start_at.before' => '开始时间必须要早于结束时间',
            'page.numeric'   => '当前页数必须是正整数',
            'page.integer'   => '当前页数必须是正整数',
            'end_at.required_with' => '开始时间不能为空',
            'start_at.required_with' => '结束时间不能为空',
            'rows_per_page.required.numeric' => '每页条目必须是正整数',
            'rows_per_page.required.integer' => '每页条目必须是正整数',
        ];

    }
}
