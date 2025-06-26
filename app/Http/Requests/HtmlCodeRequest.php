<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HtmlCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'judul' => 'required|string',
            'slug' => 'required|string',
            'code' => 'required|string',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'judul' => 'judul',
            'slug' => 'slug',
            'code' => 'code html',
        ];
    }
}
