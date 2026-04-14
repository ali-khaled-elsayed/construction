<?php

namespace App\Modules\Location\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCountryRequest extends FormRequest
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
        $code = $this->route('code');

        return [
            'code' => ['sometimes', 'required', 'string', 'size:2', 'unique:countries,code,' . $code],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'translations' => ['nullable', 'array'],
            'translations.*.language_code' => ['required_with:translations', 'string', 'size:2', 'exists:languages,code'],
            'translations.*.name' => ['required_with:translations', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'The country code is required.',
            'code.size' => 'The country code must be exactly 2 characters.',
            'code.unique' => 'The country code has already been taken.',
            'name.required' => 'The country name is required.',
            'translations.*.language_code.required_with' => 'The language code is required for each translation.',
            'translations.*.language_code.size' => 'The language code must be exactly 2 characters.',
            'translations.*.language_code.exists' => 'The selected language does not exist.',
            'translations.*.name.required_with' => 'The translated name is required for each translation.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'code' => 'country code',
            'name' => 'country name',
            'translations' => 'translations',
            'translations.*.language_code' => 'language code',
            'translations.*.name' => 'translated name',
        ];
    }
}
