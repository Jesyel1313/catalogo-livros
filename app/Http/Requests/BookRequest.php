<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'nullable|exists:categories,id',
            'pages' => 'required|integer',
            'year' => 'required|integer',
            'isbn' => 'required|regex:/[0-9]{3}-[0-9]-[0-9]{2}-[0-9]{6}-[0-9]/',
            'synopsis' => 'required|string'
        ];
    }
}
