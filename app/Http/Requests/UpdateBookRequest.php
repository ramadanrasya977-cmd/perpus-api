<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $required = $this->isMethod('PUT') ? 'required' : 'sometimes';
        $book = $this->route('book');

        return [
            'title' => [$required, 'string', 'max:255'],
            'author' => [$required, 'string', 'max:255'],
            'publisher' => [$required, 'nullable', 'string', 'max:255'],
            'isbn' => [
                $required,
                'string',
                'max:20',
                Rule::unique('books', 'isbn')->ignore($book),
            ],
            'published_year' => [$required, 'integer', 'min:1000', 'max:'.now()->year],
            'stock' => [$required, 'integer', 'min:0'],
            'description' => [$required, 'nullable', 'string'],
        ];
    }
}
