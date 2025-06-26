<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KontenRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'judul' => 'required|string',
            'isi' => 'required|string',
            'waktu' => 'required|date_format:Y-m-d H:i:s',
            'jenis_konten_id' => 'required|integer',
            'slug' => 'nullable',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        if ($this->isMethod('post')) {
            $rules['file'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'judul' => 'judul',
            'isi' => 'isi',
            'waktu' => 'tanggal berita',
            'jenis_konten_id' => 'jenis konten',
            'slug' => 'slug',
        ];
    }
}
