<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'isbn' => 'required|integer|unique:books,isbn,' . $this->route('book'),
            'author_id' => 'required|exists:authors,id',
            'published_date' => 'required|date',
        ];
    }
}
