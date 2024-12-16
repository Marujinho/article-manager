<?php

namespace App\Http\Requests\Preference;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;


class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $preference = $this->route('preference');

        return Gate::allows('update', $preference);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        
        return [
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
                Rule::unique('preferences')->where(function ($query) {
                    $query->where('user_id', $this->user()->id);
                }),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'The category field is required.',
            'category_id.string'   => 'The category field must be a string.',
            'category_id.unique' => 'You have already selected this category as a preference.',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 400, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
