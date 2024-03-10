<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentStoreRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => ['bail', 'required', config('constants.validations.alpha_spaces'), 'max:255'],
            'description' => ['bail', 'required', config('constants.validations.alpha_spaces'), 'max:255'],
            'file_type' => ['bail', 'sometimes', 'required', config('constants.validations.alpha_spaces'), 'max:255'],
            'file' => ['bail', 'required', 'mimes:jpg,jpeg,png,mp4,mpeg,mpg,mov,doc,docx,pdf', 'max:4096'],
        ];
    }
}
