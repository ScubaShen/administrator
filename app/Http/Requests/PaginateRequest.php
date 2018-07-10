<?php

namespace App\Http\Requests;

class PaginateRequest extends Request
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
                    'rows_per_page'  => 'required|numeric|integer',
                    'current_page'   => 'required|numeric|integer|min:1',
                    'total_rows' => 'numeric|integer|',
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
            'rows_per_page.required.numeric' => '每页条目必须是正整数',
            'rows_per_page.required.integer' => '每页条目必须是正整数',
            'current_page.numeric'   => '当前页数必须是正整数',
            'current_page.integer'   => '当前页数必须是正整数',
            'total_rows.numeric' => '总条目必须是正整数',
            'total_rows.integer'   => '总条目必须是正整数',
        ];

    }
}