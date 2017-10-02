<?php

namespace App\Http\Requests;

class StoreList extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:250',
        ];
    }
    public function messages()
    {
        return [];
    }
}
