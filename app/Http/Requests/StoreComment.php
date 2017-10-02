<?php

namespace App\Http\Requests;

class StoreComment extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'max:100',
            'text' => 'required|max:999',
            'movie_id' => 'required',
        ];
    }
    public function messages()
    {
        return [];
    }
}
