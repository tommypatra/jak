<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'judul' => 'required|string',
            'waktu' => 'required|date_format:Y-m-d H:i:s',
            'jenis_konten_id' => 'required|integer',
            'slug' => 'nullable',
            'deskripsi' => 'required',
            'file' => 'nullable|max:5120',
            'cover' => 'nullable|max:5120',
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
            'deskripsi' => 'deskripsi',
            'waktu' => 'waktu dokumen',
            'jenis_konten_id' => 'jenis dokumen',
            'isi' => 'isi',
            'file' => 'file upload',
            'slug' => 'slug',
            'cover' => 'cover',
        ];
    }
}
