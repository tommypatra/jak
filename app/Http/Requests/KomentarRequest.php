<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KomentarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'user_id' => 'nullable|integer',
            'nama' => 'required|string',
            'komentar' => 'required|string',
            'konten_id' => 'nullable|integer',
            'file_id' => 'nullable|integer',
            'is_publikasi' => 'nullable|integer',
        ];
        if ($this->isMethod('put')) {
            $rules['komentar'] = 'nullable|string';
            $rules['nama'] = 'nullable|string';
            $rules['is_publikasi'] = 'required|integer';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'user_id' => 'user id',
            'nama' => 'nama',
            'komentar' => 'komentar',
            'konten_id' => 'konten artikel',
            'file_id' => 'file web',
            'is_publikasi' => 'status publikasi',
        ];
    }
}
