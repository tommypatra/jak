<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'menu_id' => 'nullable',
            'nama' => 'required|string',
            'url' => 'nullable|string',
            'endpoint' => 'nullable|string',
        ];

        if ($this->isMethod('post')) {
            $rules['urut'] = 'nullable';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['urut'] = 'required|integer';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'menu_id' => 'menu parent',
            'nama' => 'nama menu',
            'url' => 'url menu tujuan',
            'endpoint' => 'endpoint api tujuan',
        ];
    }
}
