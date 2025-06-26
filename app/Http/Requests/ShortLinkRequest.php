<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShortLinkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nama' => 'required|string',
            'slug' => 'required|string',
            'slug' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9-_]+$/',
            ],
            'url_src' => 'required|url|active_url',
        ];

        if ($this->isMethod('post')) {
            $rules['slug'][] = 'unique:short_links,slug';
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'nama' => 'nama grup',
            'slug' => 'slug url',
            'url_src' => 'sumber url',
        ];
    }
}
