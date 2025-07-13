<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitKerjaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => 'required|string',
            'unit_kerja_id' => 'nullable|integer',
            'urut' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return [
            'nama' => 'nama jabatan',
            'unit_kerja_id' => 'unit kerja',
            'urut' => 'nomor urut',
        ];
    }
}
