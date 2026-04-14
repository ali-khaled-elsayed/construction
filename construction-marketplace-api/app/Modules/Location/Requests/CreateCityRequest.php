<?php

namespace App\Modules\Location\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCityRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'country_code' => ['required', 'string', 'size:2', 'exists:countries,code'],
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
            'name.required' => 'The city name is required.',
            'country_code.required' => 'The country code is required.',
            'country_code.size' => 'The country code must be exactly 2 characters.',
            'country_code.exists' => 'The selected country does not exist.',
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
            'name' => 'city name',
            'country_code' => 'country code',
            'translations' => 'translations',
            'translations.*.language_code' => 'language code',
            'translations.*.name' => 'translated name',
        ];
    }
}
