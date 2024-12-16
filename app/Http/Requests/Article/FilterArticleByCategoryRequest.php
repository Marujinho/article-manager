<?php

namespace App\Http\Requests\Article;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Article\Article;

class FilterArticleByCategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'per_page' => 'integer|min:1|max:10',
            'keycategory' => 'sometimes|string|max:255|exists:articles,category',
        ];
    }

    public function messages()
    {
        return [
            'per_page.integer' => 'The number of items per page must be an integer.',
            'per_page.min' => 'The number of items per page must be at least 1.',
            'per_page.max' => 'The number of items per page cannot exceed 10.',
            'keycategory.string' => 'The keyword must be a valid string.',
            'keycategory.max' => 'The keyword cannot exceed 255 characters.',
            'keycategory.exists' => 'The article with the indicated category does not exist.',
        ];
    }

    public function validationData()
    {
        return array_merge($this->all(), $this->route()->parameters());
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 400, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
