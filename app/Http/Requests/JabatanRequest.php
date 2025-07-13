<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JabatanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => 'required|string',
            'is_pimpinan_utama' => 'required|integer',
            'urut' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return [
            'nama' => 'nama jabatan',
            'is_pimpinan_utama' => 'jenis grup pimpinan utama',
            'urut' => 'nomor urut',
        ];
    }
}
