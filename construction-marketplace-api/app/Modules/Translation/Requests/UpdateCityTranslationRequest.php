<?php

namespace App\Modules\Translation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCityTranslationRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'city_id' => ['sometimes', 'required', 'integer', 'exists:cities,id'],
            'language_code' => ['sometimes', 'required', 'string', 'size:2', 'exists:languages,code'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
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
            'city_id.required' => 'The city ID is required.',
            'city_id.integer' => 'The city ID must be an integer.',
            'city_id.exists' => 'The selected city does not exist.',
            'language_code.required' => 'The language code is required.',
            'language_code.size' => 'The language code must be exactly 2 characters.',
            'language_code.exists' => 'The selected language does not exist.',
            'name.required' => 'The translated name is required.',
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
            'city_id' => 'city ID',
            'language_code' => 'language code',
            'name' => 'translated name',
        ];
    }
}
