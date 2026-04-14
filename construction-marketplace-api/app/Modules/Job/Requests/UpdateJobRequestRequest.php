<?php

namespace App\Modules\Job\Requests;

use App\Modules\Job\Enums\DescriptionType;
use App\Modules\Job\Enums\JobType;
use App\Modules\Job\Enums\ServiceType;
use App\Modules\Shared\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequestRequest extends FormRequest
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
            'unit_type' => ['sometimes', 'required', 'string', 'max:255'],
            'job_type' => ['sometimes', 'required', 'in:' . implode(',', JobType::values())],
            'service_type' => ['sometimes', 'required', 'in:' . implode(',', ServiceType::values())],
            'description_type' => ['sometimes', 'required', 'in:' . implode(',', DescriptionType::values())],
            'city_id' => ['sometimes', 'nullable', 'exists:cities,id'],
            'country_code' => ['sometimes', 'nullable', 'exists:countries,code'],
            'address' => ['sometimes', 'required', 'string'],

            // Basic description fields (required when description_type is 'basic')
            'basic_description' => ['nullable', 'array'],
            'basic_description.rooms_count' => ['sometimes', 'required_if:description_type,basic', 'integer', 'min:1'],
            'basic_description.wet_rooms_count' => ['nullable', 'integer', 'min:0'],
            'basic_description.external_rooms_count' => ['nullable', 'integer', 'min:0'],
            'basic_description.has_garden' => ['nullable', 'boolean'],
            'basic_description.has_roof' => ['nullable', 'boolean'],
            'basic_description.area' => ['nullable', 'numeric', 'min:0'],
            'basic_description.description' => ['nullable', 'string', 'max:1000'],

            // Rooms for detailed description
            'rooms' => ['nullable', 'array'],
            'rooms.*.room_type_id' => ['required_with:rooms', 'exists:room_types,id'],
            'rooms.*.area' => ['nullable', 'numeric', 'min:0'],
            'rooms.*.jobs' => ['nullable', 'array'],
            'rooms.*.jobs.*.category_id' => ['sometimes', 'required_with:rooms.*.jobs', 'exists:job_categories,id'],
            'rooms.*.jobs.*.fee_amount' => ['nullable', 'integer', 'min:0'],
            'rooms.*.jobs.*.size' => ['nullable', 'in:small,medium,large'],
            'rooms.*.jobs.*.description' => ['nullable', 'string', 'max:1000'],
            'rooms.*.jobs.*.urgency' => ['nullable', 'in:standard,urgent'],

            // Jobs for basic description (top-level)
            'jobs' => ['nullable', 'array'],
            'jobs.*.category_id' => ['sometimes', 'required_with:jobs', 'exists:job_categories,id'],
            'jobs.*.fee_amount' => ['nullable', 'integer', 'min:0'],
            'jobs.*.size' => ['nullable', 'in:small,medium,large'],
            'jobs.*.description' => ['nullable', 'string', 'max:1000'],
            'jobs.*.urgency' => ['nullable', 'in:standard,urgent'],
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
            'unit_type.required' => 'The unit type is required.',
            'job_type.required' => 'The job type is required.',
            'job_type.in' => 'The selected job type is invalid.',
            'service_type.required' => 'The service type is required.',
            'service_type.in' => 'The selected service type is invalid.',
            'description_type.required' => 'The description type is required.',
            'description_type.in' => 'The selected description type is invalid.',
            'city_id.required' => 'The city is required.',
            'city_id.exists' => 'The selected city is invalid.',
            'country_code.required' => 'The country is required.',
            'country_code.exists' => 'The selected country is invalid.',
            'address.required' => 'The address is required.',
            'basic_description.rooms_count.required_if' => 'The rooms count is required for basic descriptions.',
            'basic_description.rooms_count.min' => 'The rooms count must be at least 1.',
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
            'unit_type' => 'unit type',
            'job_type' => 'job type',
            'service_type' => 'service type',
            'description_type' => 'description type',
            'city_id' => 'city',
            'country_code' => 'country',
            'basic_description.rooms_count' => 'rooms count',
            'basic_description.wet_rooms_count' => 'wet rooms count',
            'basic_description.external_rooms_count' => 'external rooms count',
        ];
    }
}
