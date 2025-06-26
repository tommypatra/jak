<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublikasiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'is_publikasi' => 'required|integer',
            'catatan' => 'nullable|string',
            'konten_id' => 'nullable|integer',
            'file_id' => 'nullable|integer',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'is_publikasi' => 'status publikasi',
            'catatan' => 'catatan',
            'konten_id' => 'konten',
            'file_id' => 'file',
        ];
    }
}
