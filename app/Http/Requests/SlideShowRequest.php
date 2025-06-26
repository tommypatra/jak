<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlideShowRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'is_publikasi' => 'required|boolean',
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
            'deskripsi' => 'deskripsi',
            'is_publikasi' => 'status publikasi',
            'file' => 'gambar',
        ];
    }
}
