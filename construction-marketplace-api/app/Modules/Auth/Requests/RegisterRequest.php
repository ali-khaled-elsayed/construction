<?php

namespace App\Modules\Auth\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['sometimes', 'string', 'in:' . implode(',', UserRole::values())],
            'bio' => ['nullable', 'string', 'max:1000'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'country_code' => ['nullable', 'string', 'size:2', 'exists:countries,code'],
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
            'role.in' => 'The selected role is invalid. Must be one of: ' . implode(', ', UserRole::values()),
            'country_code.size' => 'The country code must be exactly 2 characters (ISO code).',
            'country_code.exists' => 'The selected country does not exist.',
        ];
    }
}
