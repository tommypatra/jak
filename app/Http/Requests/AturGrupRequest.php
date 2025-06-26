<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AturGrupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'grup_id' => 'required|array',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'pengguna',
            'grup_id' => 'grup',
        ];
    }
}
