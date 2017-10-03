<?php

namespace App\Http\Requests;

class StoreSong extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|max:500',
        ];
    }
    public function messages()
    {
        return [];
    }
}
