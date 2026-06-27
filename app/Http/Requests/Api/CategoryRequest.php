<?php

namespace App\Http\Requests\Api;

class CategoryRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'dec' => ['required', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
