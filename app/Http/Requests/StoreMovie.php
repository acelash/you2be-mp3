<?php

namespace App\Http\Requests;

class StoreMovie extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|max:250',
            'title_original' => 'max:250',
            'source_id' => 'required|max:100',
            'text' => 'required|max:9999',
            'user_id' => 'exists:users,id',
            'country_id' => 'exists:countries,id',
            'genre_id' => 'exists:genres,id',
            'state_id' => 'exists:states,id'
        ];
    }
    public function messages()
    {
        return [];
    }
}
