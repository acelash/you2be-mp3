<?php

namespace App\Http\Requests;

class StoreProfile extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //'email' => 'required|email|max:250',
            'name' => 'required|max:250',
            'firstname' => 'max:20',
            'lastname' => 'max:20',
            'sex' => 'integer',
            //'birth_date' => 'date'
        ];
    }
}
