<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class AuthorRequest extends FormRequest
{
    
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'nationality'=> 'required|string|max:255',
        ];
    }
}
