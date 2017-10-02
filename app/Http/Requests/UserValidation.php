<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserValidation extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|max:255',
            'lang' => 'in:en,ru,ro',
            'avatar' => 'image|mimes:jpg,jpeg,gif,png|max:50000',
            'name' => 'required|max:20|min:3|regex:/^[(a-zA-Z0-9А-Яа-я \s)]+$/u',
            //'available_cv_contacts' => 'integer'
        ];
    }
}
