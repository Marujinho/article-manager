<?php

namespace App\Http\Requests\Preference;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;


class DeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $preference = $this->route('preference');

        return Gate::allows('delete', $preference);
    }

    // /**
    //  * Get the validation rules that apply to the request.
    //  *
    //  * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    //  */
    // // public function rules(): array
    // {
    //     return [
    //         'id' => 'required|numeric|exists:preferences,id',
    //     ];
    // }

    // public function messages(): array {
    //     return [
    //         'id.required' => 'The ID field is required.',
    //         'id.numeric'  => 'The ID field must be a numeric value.',
    //         'id.exists'   => 'The specified ID does not exist in the preferences table.',
    //     ];
    // }

    // public function validationData()
    // {
    //     return array_merge($this->all(), $this->route()->parameters());
    // }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 400, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

}
