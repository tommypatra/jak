<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JenisKontenRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => 'required|string',
            'slug' => 'required|string',
            'deskripsi' => 'nullable',
            'kategori' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'nama' => 'nama jenis konten',
            'slug' => 'slug jenis konten',
            'deskripsi' => 'deskripsi ',
            'kategori' => 'kategori KONTEN atau FILE',
        ];
    }
}
