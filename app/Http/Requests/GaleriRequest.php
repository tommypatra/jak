<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GaleriRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'judul' => 'required|string',
            'file' => 'nullable|max:5120',
        ];
        if ($this->isMethod('post')) {
            $rules['file'] = 'required|max:5120';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'judul' => 'judul',
            'file' => 'file upload',
        ];
    }
}
