<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KotakSaranRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nama' => 'required|string',
            'komentar' => 'required|string',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'nama' => 'nama',
            'komentar' => 'komentar',
        ];
    }
}
