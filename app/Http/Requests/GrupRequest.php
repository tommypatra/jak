<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'nama' => 'nama grup',
        ];
    }
}
