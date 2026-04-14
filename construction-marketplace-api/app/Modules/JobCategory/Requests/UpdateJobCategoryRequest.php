<?php

namespace App\Modules\JobCategory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobCategoryRequest extends FormRequest
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
            'code' => ['sometimes', 'required', 'string', 'max:50', 'unique:job_categories,code,' . $id],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'translations' => ['nullable', 'array'],
            'translations.*.language_code' => ['required_with:translations', 'string', 'size:2', 'exists:languages,code'],
            'translations.*.name' => ['required_with:translations', 'string', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
            'attributes' => ['nullable', 'array'],
            'attributes.*.code' => ['required_with:attributes', 'string', 'max:50'],
            'attributes.*.name' => ['required_with:attributes', 'string', 'max:255'],
            'attributes.*.type' => ['required_with:attributes', 'string', 'in:text,number,boolean,select,multi_select'],
            'attributes.*.options' => ['nullable', 'array'],
            'attributes.*.is_required' => ['boolean'],
            'attributes.*.sort_order' => ['integer', 'min:0'],
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
            'code.required' => 'The job category code is required.',
            'code.unique' => 'The job category code has already been taken.',
            'name.required' => 'The job category name is required.',
            'translations.*.language_code.required_with' => 'The language code is required for each translation.',
            'translations.*.language_code.exists' => 'The selected language does not exist.',
            'translations.*.name.required_with' => 'The translated name is required for each translation.',
            'attributes.*.code.required_with' => 'The attribute code is required for each attribute.',
            'attributes.*.name.required_with' => 'The attribute name is required for each attribute.',
            'attributes.*.type.in' => 'The attribute type must be one of: text, number, boolean, select, multi_select.',
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
            'code' => 'job category code',
            'name' => 'job category name',
            'description' => 'job category description',
            'translations' => 'translations',
            'translations.*.language_code' => 'language code',
            'translations.*.name' => 'translated name',
            'translations.*.description' => 'translated description',
            'attributes' => 'attributes',
            'attributes.*.code' => 'attribute code',
            'attributes.*.name' => 'attribute name',
            'attributes.*.type' => 'attribute type',
            'attributes.*.options' => 'attribute options',
            'attributes.*.is_required' => 'attribute required',
            'attributes.*.sort_order' => 'attribute sort order',
        ];
    }
}
