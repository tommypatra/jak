<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengaturanWebRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nama' => 'required|string',
            'deskripsi' => 'required|string',
            'keywords' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'alamat' => 'nullable',
            'helpdesk' => 'nullable',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fb' => 'nullable',
            'youtube' => 'nullable',
            'ig' => 'nullable',
            'email' => 'nullable',
            'twitter' => 'nullable',
        ];

        if ($this->isMethod('post')) {
            $rules['icon'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['logo'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }


        return $rules;
    }

    public function attributes()
    {
        return [
            'nama' => 'nama website',
            'deskripsi' => 'deskripsi singkat',
            'keywords' => 'keyowrd website',
            'longitude' => 'longitude',
            'latitude' => 'latitude',
            'icon' => 'icon web',
            'logo' => 'logo web',
            'alamat' => 'alamat instansi',
            'helpdesk' => 'daftar nomor helpdesk',
            'fb' => 'akun facebook',
            'youtube' => 'channel youtube',
            'ig' => 'akun instagram',
            'email' => 'email resmi',
            'twitter' => 'akun twitter',
        ];
    }
}
