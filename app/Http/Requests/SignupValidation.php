<?php

namespace App\Http\Requests;

class SignupValidation extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:255|unique:users',
            'parola' => 'required|min:6|confirmed',
            'parola_confirmation' => 'required|min:6',
            'agree_terms'=>'accepted'
        ];
    }
    public function messages()
    {
        return [
            '*.required' => 'Câmpul este obligatoriu.',
            'agree_terms.accepted'  => 'Câmpul trebuie să fie acceptat.',
        ];
    }
}
