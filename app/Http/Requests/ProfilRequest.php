<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'user_id'         => 'required|integer',
            'jabatan_id'      => 'nullable|integer',
            'unit_kerja_id'   => 'nullable|integer',
            'gelar_depan'     => 'nullable|string|max:50',
            'gelar_belakang'  => 'nullable|string|max:50',
            'jenis_kelamin'   => 'nullable|in:L,P',
            'alamat'          => 'required|string|max:255',
            'tempat_lahir'    => 'required|string|max:100',
            'tanggal_lahir'   => 'required|date_format:Y-m-d',
            'is_administrasi' => 'nullable|boolean',
            'is_dosen'        => 'nullable|boolean',
            'nidn'            => 'nullable|string',
            'nomor_pegawai'   => 'nullable|string',
            'hp'              => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|max:15',
        ];

        // Kondisional untuk file foto
        if ($this->isMethod('post')) {
            $rules['foto'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        } elseif ($this->isMethod('put')) {
            $rules['foto'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }



    public function attributes()
    {
        return [
            'user_id' => 'user',
            'jabatan_id'      => 'jabatan',
            'unit_kerja_id'   => 'unit kerja',
            'gelar_depan' => 'gelar depan',
            'gelar_belakang' => 'gelar belakang',
            'jenis_kelamin' => 'jenis kelamin',
            'alamat' => 'alamat',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'is_administrasi' => 'administrasi',
            'is_dosen' => 'dosen',
            'nidn' => 'nidn',
            'nomor_pegawai' => 'nomor pegawai',
            'hp' => 'nomor hp',
        ];
    }
}
