<?php

namespace App\Modules\RoomType\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoomTypeRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:100|unique:room_types,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'translations' => 'array',
            'translations.*.language_code' => 'required|string|size:2|exists:languages,code',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Room type code is required',
            'code.unique' => 'This room type code already exists',
            'name.required' => 'Room type name is required',
            'translations.*.language_code.exists' => 'The selected language does not exist',
        ];
    }
}
